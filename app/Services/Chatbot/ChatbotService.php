<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Models\ChatbotConversation;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotMessage;
use App\Models\ChatbotSetting;
use App\Models\ChatbotSuggestedQuestion;
use App\Models\ChatbotUnansweredQuestion;
use App\Models\Department;
use App\Models\User;
use App\Support\ChatbotText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatbotService
{
    public function __construct(
        private readonly KnowledgeBaseMatcher $knowledgeMatcher,
        private readonly WebsiteContentSearchService $websiteSearch,
        private readonly ExternalKnowledgeSourceService $externalKnowledge,
        private readonly AiProviderManager $providerManager,
        private readonly PromptGuard $promptGuard,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function bootstrap(Request $request, ChatbotSetting $settings): array
    {
        $suggestions = $settings->suggestions_enabled
            ? ChatbotSuggestedQuestion::query()
                ->where('is_active', true)
                ->where('show_on_welcome', true)
                ->orderBy('display_order')
                ->limit((int) config('chatbot.related_limit', 5))
                ->pluck('question')
                ->all()
            : [];

        $history = [];
        $conversation = $this->currentConversation($request);

        if ($settings->save_history && $conversation) {
            $history = $conversation->messages()
                ->orderByDesc('id')
                ->limit((int) config('chatbot.history_limit', 30))
                ->get()
                ->reverse()
                ->values()
                ->map(fn (ChatbotMessage $message) => [
                    'role' => $message->role,
                    'content' => $message->content,
                    'source' => $message->answer_source,
                    'created_at' => $message->created_at?->toIso8601String(),
                ])
                ->all();
        }

        return [
            'settings' => [
                'name' => $settings->chatbot_name,
                'title' => $settings->header_title,
                'welcome_message' => $settings->welcome_message,
                'placeholder' => $settings->placeholder_text,
                'icon' => $settings->chatbot_icon,
                'primary_color' => $settings->primary_color,
                'privacy_message' => $settings->privacy_message,
                'max_message_length' => $settings->max_message_length,
            ],
            'suggestions' => $suggestions,
            'history' => $history,
            'profile' => $this->profile($request),
            'departments' => Department::active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Department $department) => [
                    'id' => $department->id,
                    'name' => $department->name,
                ])
                ->all(),
        ];
    }

    /**
     * @return array{student_id: string, full_name: string, department_id: int, department_name: string}|null
     */
    public function profile(Request $request): ?array
    {
        $profile = $request->session()->get('chatbot_profile');

        if (! is_array($profile)
            || empty($profile['student_id'])
            || empty($profile['full_name'])
            || ! array_key_exists('department_id', $profile)
            || ! array_key_exists('department_name', $profile)) {
            return null;
        }

        return [
            'student_id' => (string) $profile['student_id'],
            'full_name' => (string) $profile['full_name'],
            'department_id' => (int) $profile['department_id'],
            'department_name' => (string) $profile['department_name'],
        ];
    }

    /**
     * @param  array{student_id: string, full_name: string, department_id: int, department_name: string}  $profile
     * @return array{student_id: string, full_name: string, department_id: int, department_name: string}
     */
    public function saveProfile(Request $request, array $profile): array
    {
        $request->session()->put('chatbot_profile', $profile);

        if ($conversation = $this->currentConversation($request)) {
            $metadata = $conversation->metadata ?? [];
            $metadata['student_profile'] = $profile;
            $conversation->update(['metadata' => $metadata]);
        }

        return $profile;
    }

    /**
     * @return array<string, mixed>
     */
    public function answer(string $question, Request $request, ChatbotSetting $settings): array
    {
        $startedAt = hrtime(true);
        $question = ChatbotText::plainText($question, $settings->max_message_length);
        $isLanguageInstruction = $this->isLanguageInstruction($question);
        $guard = $this->promptGuard->inspect($question);

        if ($guard['blocked']) {
            return $this->complete(
                $request,
                $settings,
                $question,
                (string) $guard['message'],
                'security',
                $startedAt,
                status: 'blocked',
            );
        }

        // Resolve pending context — if user says "yes/han/ji" use the previous topic
        $question = $this->resolvePendingContext($request, $question);

        if ($match = $this->knowledgeMatcher->find($question)) {
            $matchAnswer = ChatbotText::plainText($match->knowledge->answer);

            // Skip knowledge base match if the answer is just a clarification question
            // (it would create an infinite loop of asking the same question)
            if (! $this->isClarificationAnswer($matchAnswer)) {
                return $this->complete(
                    $request,
                    $settings,
                    $question,
                    $matchAnswer,
                    'knowledge_base',
                    $startedAt,
                    related: $this->relatedQuestions($match->knowledge->category_id, $match->knowledge),
                    knowledgeId: $match->knowledge->id,
                    categoryId: $match->knowledge->category_id,
                    metadata: ['match_score' => $match->score],
                );
            }
        }

        $suggested = ChatbotSuggestedQuestion::query()
            ->where('is_active', true)
            ->whereNotNull('answer')
            ->get()
            ->first(fn (ChatbotSuggestedQuestion $item) => ChatbotText::hash($item->question) === ChatbotText::hash($question));

        if ($suggested) {
            return $this->complete(
                $request,
                $settings,
                $question,
                ChatbotText::plainText($suggested->answer),
                'admin_answer',
                $startedAt,
                related: $this->relatedQuestions($suggested->category_id),
                categoryId: $suggested->category_id,
            );
        }

        $website = $this->websiteSearch->search($question);
        $provider = $settings->ai_fallback_enabled
            ? $this->providerManager->activeProvider()
            : null;
        $sourceResult = null;
        $providerFailure = null;
        $responseLanguage = $this->detectResponseLanguage($question);

        if (! $provider && $website && $website['score'] >= 65) {
            return $this->complete(
                $request,
                $settings,
                $question,
                $website['answer'],
                'website_data',
                $startedAt,
                metadata: ['url' => $website['url'], 'match_score' => $website['score']],
            );
        }

        if ($provider) {
            $history = $this->recentHistory($request, $settings);
            $languageInstruction = $this->responseLanguageInstruction($responseLanguage);

            $profile = $this->profile($request);
            $departmentContext = $profile
                ? "The visitor selected the {$profile['department_name']} department. Use this only to clarify department-specific questions. Never ask for, reveal, or repeat the visitor's student ID."
                : '';

            // Intent-based knowledge search (fast, from database)
            $intentDetector = new \App\Services\Chatbot\IntentDetector();
            $detectedIntent = $intentDetector->detect($question);
            $knowledgeData = \App\Models\ChatbotKnowledgeData::search($question, $detectedIntent, 3);

            // If no results with detected intent, try without intent filter
            if ($knowledgeData->isEmpty()) {
                $knowledgeData = \App\Models\ChatbotKnowledgeData::search($question, null, 3);
            }

            $knowledgeContext = $knowledgeData->isNotEmpty()
                ? $knowledgeData->map(fn ($item) => "--- {$item->title} ---\n{$item->content}")->implode("\n\n")
                : '';

            // Only fetch external sources if knowledge data didn't provide enough
            $sourceResult = null;
            if ($knowledgeContext === '') {
                $sourceResult = $this->externalKnowledge->fetch($provider, $question);
            }

            $context = collect([
                $knowledgeContext ?: null,
                $website['context'] ?? null,
                $sourceResult['context'] ?? null,
            ])->filter()->implode("\n\n");
            $instructions = trim(implode("\n\n", array_filter([
                $settings->system_prompt,
                $provider->system_prompt,
                $departmentContext,
                $this->responseQualityInstructions(),
                $languageInstruction,
            ])));
            $response = $this->providerManager->generate(
                $provider,
                $languageInstruction."\n\nCurrent user question:\n".$question,
                $instructions,
                $history,
                $context !== '' ? $context : null,
            );

            if ($response->successful && $response->answer) {
                if ($this->isUsefulAiAnswer(
                    $response->answer,
                    $this->hasTargetedSource($provider, $sourceResult),
                )) {
                    $source = $provider->type === 'custom' ? 'custom_api' : $provider->type;
                    $metadata = $response->metadata;

                    if ($sourceResult) {
                        $metadata['knowledge_sources'] = $sourceResult['sources'];

                        if ($sourceResult['errors'] !== []) {
                            $metadata['knowledge_source_errors'] = $sourceResult['errors'];
                        }
                    }

                    return $this->complete(
                        $request,
                        $settings,
                        $question,
                        $response->answer,
                        $source,
                        $startedAt,
                        provider: $provider,
                        metadata: $metadata,
                    );
                }

                $providerFailure = $response->answer;
            } else {
                $providerFailure = $response->error;
            }
        }

        if ($isLanguageInstruction) {
            $hasHistory = $this->recentHistory($request, $settings) !== [];

            return $this->complete(
                $request,
                $settings,
                $question,
                $hasHistory
                    ? 'Sorry, main pichlay jawab ko abhi rewrite nahi kar saka. Ek dafa dobara try karein.'
                    : 'Zaroor. Kis pichlay jawab ko rewrite ya simple karna hai?',
                'follow_up',
                $startedAt,
            );
        }

        if ($provider
            && $sourceResult
            && $this->hasTargetedSource($provider, $sourceResult)) {
            return $this->complete(
                $request,
                $settings,
                $question,
                $this->sourceFallbackAnswer($sourceResult, $responseLanguage),
                'website_data',
                $startedAt,
                provider: $provider,
                metadata: array_filter([
                    'knowledge_sources' => $sourceResult['sources'],
                    'knowledge_source_errors' => $sourceResult['errors'],
                    'provider_error' => $providerFailure,
                ]),
            );
        }

        $this->recordUnanswered($request, $question, $provider, $providerFailure);

        return $this->complete(
            $request,
            $settings,
            $question,
            $settings->no_answer_message,
            'unanswered',
            $startedAt,
            provider: $provider,
            status: 'unanswered',
        );
    }

    public function clear(Request $request): void
    {
        if ($conversation = $this->currentConversation($request)) {
            $conversation->update(['status' => 'cleared']);
            $conversation->delete();
        }

        $request->session()->forget('chatbot_conversation_uuid');
        $request->session()->forget('chatbot_recent_messages');
    }

    private function currentUser(): ?User
    {
        return Auth::guard('student')->user() ?: Auth::guard('web')->user();
    }

    private function guestId(Request $request): string
    {
        if (! $request->session()->has('chatbot_guest_uuid')) {
            $request->session()->put('chatbot_guest_uuid', (string) Str::uuid());
        }

        return (string) $request->session()->get('chatbot_guest_uuid');
    }

    private function currentConversation(Request $request): ?ChatbotConversation
    {
        $uuid = $request->session()->get('chatbot_conversation_uuid');

        if (! $uuid) {
            return null;
        }

        $user = $this->currentUser();
        $query = ChatbotConversation::query()->where('uuid', $uuid)->where('status', 'active');

        $user
            ? $query->where('user_id', $user->id)
            : $query->where('guest_session_id', $this->guestId($request));

        return $query->first();
    }

    private function conversation(Request $request): ChatbotConversation
    {
        if ($conversation = $this->currentConversation($request)) {
            return $conversation;
        }

        $user = $this->currentUser();
        $conversation = ChatbotConversation::create([
            'user_id' => $user?->id,
            'guest_session_id' => $user ? null : $this->guestId($request),
            'status' => 'active',
            'last_message_at' => now(),
            'metadata' => [
                'started_from' => $request->path(),
                'student_profile' => $this->profile($request),
            ],
        ]);
        $request->session()->put('chatbot_conversation_uuid', $conversation->uuid);

        return $conversation;
    }

    /**
     * @param  array<int, string>  $related
     * @param  array<string, mixed>  $metadata
     * @return array<string, mixed>
     */
    private function complete(
        Request $request,
        ChatbotSetting $settings,
        string $question,
        string $answer,
        string $source,
        int $startedAt,
        array $related = [],
        ?AiProvider $provider = null,
        ?int $knowledgeId = null,
        ?int $categoryId = null,
        string $status = 'answered',
        array $metadata = [],
    ): array {
        $elapsed = max(1, (int) round((hrtime(true) - $startedAt) / 1_000_000));

        if ($settings->save_history) {
            $conversation = $this->conversation($request);
            $common = [
                'conversation_id' => $conversation->id,
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            ];
            $userMessage = ChatbotMessage::create($common + [
                'role' => 'user',
                'content' => $question,
                'status' => 'received',
            ]);
            ChatbotMessage::create($common + [
                'parent_message_id' => $userMessage->id,
                'role' => 'assistant',
                'content' => ChatbotText::plainText($answer),
                'answer_source' => $source,
                'ai_provider_id' => $provider?->id,
                'knowledge_base_id' => $knowledgeId,
                'category_id' => $categoryId,
                'response_time_ms' => $elapsed,
                'status' => $status,
                'metadata' => $metadata ?: null,
            ]);
            $conversation->update(['last_message_at' => now()]);
        }

        $this->rememberRecentExchange($request, $question, $answer);

        // Store pending context if AI asked a clarification question
        $this->storePendingContext($request, $question, $answer);

        $related = $settings->suggestions_enabled
            ? ($related ?: $this->relatedQuestions($categoryId))
            : [];

        return [
            'answer' => ChatbotText::plainText($answer),
            'source' => $source,
            'related_questions' => array_values(array_unique($related)),
            'response_time_ms' => $elapsed,
        ];
    }

    private function recordUnanswered(
        Request $request,
        string $question,
        ?AiProvider $provider = null,
        ?string $providerError = null,
    ): void {
        if ($this->isLanguageInstruction($question)) {
            return;
        }

        $profile = $this->profile($request);
        $departmentId = ($profile['department_id'] ?? null) ?: null; // Convert 0 to null
        $hash = ChatbotText::hash($question);
        $existing = ChatbotUnansweredQuestion::query()
            ->where('question_hash', $hash)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            $existing->update([
                'asked_count' => $existing->asked_count + 1,
                'last_asked_at' => now(),
                'ai_provider_id' => $provider?->id,
                'ai_response' => $providerError,
                'student_name' => $profile['full_name'] ?? null,
                'student_id' => $profile['student_id'] ?? null,
                'department_id' => $departmentId,
            ]);

            return;
        }

        $user = $this->currentUser();
        ChatbotUnansweredQuestion::create([
            'user_id' => $user?->id,
            'guest_session_id' => $user ? null : $this->guestId($request),
            'student_name' => $profile['full_name'] ?? null,
            'student_id' => $profile['student_id'] ?? null,
            'department_id' => $departmentId,
            'user_question' => $question,
            'normalized_question' => ChatbotText::normalize($question),
            'question_hash' => $hash,
            'ai_provider_id' => $provider?->id,
            'ai_response' => $providerError,
            'status' => 'pending',
            'asked_count' => 1,
            'first_asked_at' => now(),
            'last_asked_at' => now(),
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function relatedQuestions(?int $categoryId = null, ?ChatbotKnowledgeBase $knowledge = null): array
    {
        $limit = (int) config('chatbot.related_limit', 5);
        $questions = collect();

        if ($knowledge) {
            $questions = $questions->merge(
                $knowledge->relatedQuestions
                    ->where('is_active', true)
                    ->sortBy('sort_order')
                    ->map(fn ($related) => $related->relatedKnowledge?->question ?: $related->question)
            );
            $questions = $questions->merge($knowledge->alternatives->pluck('question'));
        }

        if ($categoryId) {
            $questions = $questions->merge(
                ChatbotKnowledgeBase::query()
                    ->approved()
                    ->where('category_id', $categoryId)
                    ->when($knowledge, fn ($query) => $query->where('id', '!=', $knowledge->id))
                    ->orderByDesc('priority')
                    ->limit($limit)
                    ->pluck('question')
            );
        }

        $questions = $questions->merge(
            ChatbotSuggestedQuestion::query()
                ->where('is_active', true)
                ->when($categoryId, fn ($query) => $query->where(function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId)->orWhereNull('category_id');
                }))
                ->orderBy('display_order')
                ->limit($limit)
                ->pluck('question')
        );

        return $questions->filter()->unique()->take($limit)->values()->all();
    }

    private function isUsefulAiAnswer(string $answer, bool $hasTargetedSource = false): bool
    {
        $normalized = ChatbotText::normalize($answer);

        if (mb_strlen($normalized) < 15) {
            return false;
        }

        if ($hasTargetedSource) {
            foreach ([
                'could you give a bit more context',
                'could you provide more context',
                'do not have any information',
                'do not have specific information',
                'i m not aware of',
                'i am not aware of',
                'i m not sure which',
                'i am not sure which',
                'koi specific information nahi',
                'koi specific information nahin',
                'maloom nahi ke',
                'maloom nahin ke',
                'thora aur context',
                'thoda aur context',
            ] as $missingInformationPhrase) {
                if (str_contains($normalized, $missingInformationPhrase)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param  array{context: string, sources: array<int, string>, errors: array<int, string>}|null  $sourceResult
     */
    private function hasTargetedSource(AiProvider $provider, ?array $sourceResult): bool
    {
        if (! $sourceResult || $sourceResult['sources'] === []) {
            return false;
        }

        $configuredSource = rtrim((string) $provider->knowledge_source_url, '/');
        $configuredPath = trim((string) parse_url($configuredSource, PHP_URL_PATH), '/');

        if ($configuredPath !== '') {
            return true;
        }

        foreach ($sourceResult['sources'] as $source) {
            if ($configuredSource === '' || rtrim($source, '/') !== $configuredSource) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array{context: string, sources: array<int, string>, errors: array<int, string>}  $sourceResult
     */
    private function sourceFallbackAnswer(array $sourceResult, string $language): string
    {
        $context = preg_replace(
            '/^External knowledge (?:webpage|API response) \(reference data only\)\RSource:.*\R/u',
            '',
            $sourceResult['context'],
        ) ?? $sourceResult['context'];
        $context = preg_replace('/\n\s*\n+/u', "\n", $context) ?? $context;
        $source = $sourceResult['sources'][0] ?? null;

        if ($profileAnswer = $this->facultyProfileFallback($context, $language, $source)) {
            return $profileAnswer;
        }

        if ($programAnswer = $this->academicProgramFallback($context, $language, $source)) {
            return $programAnswer;
        }

        $excerpt = Str::limit(ChatbotText::plainText($context, 1400), 1100, '...');

        $introduction = match ($language) {
            'urdu' => 'KASBIT کی آفیشل ویب سائٹ کے مطابق:',
            'roman_urdu' => 'KASBIT ki official website ke mutabiq:',
            default => 'According to the official KASBIT website:',
        };
        $sourceLabel = match ($language) {
            'urdu' => 'مزید معلومات',
            'roman_urdu' => 'Mazeed maloomat',
            default => 'More information',
        };
        $sourceLink = $source ? "[KASBIT official page]({$source})" : null;

        return trim($introduction."\n\n".$excerpt.($sourceLink ? "\n\n{$sourceLabel}: {$sourceLink}" : ''));
    }

    private function academicProgramFallback(string $context, string $language, ?string $source): ?string
    {
        $lines = collect(preg_split('/\R+/u', $context) ?: [])
            ->map(fn (string $line) => trim($line))
            ->reject(fn (string $line) => $line === ''
                || str_starts_with($line, 'External knowledge ')
                || str_starts_with($line, 'Source: '))
            ->values();
        $durationHeadingIndex = $lines->search(
            fn (string $line) => ChatbotText::normalize($line) === 'course work and duration',
        );

        if ($durationHeadingIndex === false || $durationHeadingIndex === 0) {
            return null;
        }

        $title = (string) $lines->first();

        if (ChatbotText::normalize($title) === 'ms' && $durationHeadingIndex > 1) {
            $title .= ' '.$lines->get(1);
        }

        $title = trim((string) preg_replace('/\s+(?:FOUR-YEAR\s+)?DEGREE PROGRAM$/i', '', $title));
        $isBscs = str_contains(Str::lower($title), 'bachelor of computer science');
        $displayTitle = $isBscs ? 'BSCS yani Bachelor of Computer Science' : $title;
        $durationLine = (string) $lines->get($durationHeadingIndex + 1, '');
        $duration = [
            'years' => null,
            'semesters' => null,
            'months_each' => null,
            'courses' => null,
            'projects' => null,
            'credit_hours' => null,
        ];

        if (preg_match(
            '/(\d+)-Year,\s*(\d+)-Semester,\s*\(?\s*(\d+)\s*Courses(?:\s*\+\s*(\d+)\s*FYP)?\s*\)?,\s*(\d+)\s*(?:CH(?: Degree Program)?|Credit Hours)/i',
            $durationLine,
            $match,
        )) {
            $duration = [
                'years' => $match[1],
                'semesters' => $match[2],
                'months_each' => null,
                'courses' => $match[3],
                'projects' => $match[4] ?: null,
                'credit_hours' => $match[5],
            ];
        } else {
            if (preg_match('/(\d+)\s+semesters? of\s+(\d+)\s+months? each/i', $durationLine, $match)) {
                $duration['semesters'] = (string) (int) $match[1];
                $duration['months_each'] = (string) (int) $match[2];
            }

            if (preg_match('/(\d+(?:\.\d+)?)\s*years?/i', $durationLine, $match)) {
                $duration['years'] = $match[1];
            } elseif (preg_match('/\b(Two|Three|Four)\s+years?/i', $durationLine, $match)) {
                $duration['years'] = ['two' => '2', 'three' => '3', 'four' => '4'][Str::lower($match[1])];
            }

            $duration['courses'] = $this->lineAfterLabel($lines, 'Total Courses');
            $duration['credit_hours'] = $this->lineAfterLabel($lines, 'Total Credit Hours');
        }

        $intake = $this->lineAfterLabel($lines, 'Intake');
        $maximumLoad = $this->lineAfterLabel($lines, 'Maximum Load');
        $timeDuration = $this->lineAfterLabel($lines, 'Time Duration');
        $courses = $this->programCourseHighlights($lines);
        $eligibility = $this->programEligibilitySummary($lines, $language);
        $normalizedContext = Str::lower($context);
        $hasAdmissionTest = str_contains($normalizedContext, 'admission test')
            || str_contains($normalizedContext, 'entrance test')
            || str_contains($normalizedContext, 'gre/nts');
        $hasInterview = str_contains($normalizedContext, 'final interview')
            || str_contains($normalizedContext, 'completion of an interview');

        $durationFact = match ($language) {
            'urdu' => $duration['years'] && $duration['semesters']
                ? "یہ {$duration['years']} سال اور {$duration['semesters']} سمسٹرز کا پروگرام ہے۔"
                : ($duration['semesters']
                    ? "یہ {$duration['semesters']} سمسٹرز کا پروگرام ہے".($duration['months_each'] ? " اور ہر سمسٹر {$duration['months_each']} ماہ کا ہے۔" : '۔')
                    : ($duration['years'] ? "یہ {$duration['years']} سال کا پروگرام ہے۔" : null)),
            'roman_urdu' => $duration['years'] && $duration['semesters']
                ? "Yeh {$duration['years']} saal aur {$duration['semesters']} semesters ka program hai."
                : ($duration['semesters']
                    ? "Yeh {$duration['semesters']} semesters ka program hai".($duration['months_each'] ? " aur har semester {$duration['months_each']} months ka hai." : '.')
                    : ($duration['years'] ? "Yeh {$duration['years']} saal ka program hai." : null)),
            default => $duration['years'] && $duration['semesters']
                ? "It is a {$duration['years']}-year, {$duration['semesters']}-semester program."
                : ($duration['semesters']
                    ? "It has {$duration['semesters']} semesters".($duration['months_each'] ? ", each lasting {$duration['months_each']} months." : '.')
                    : ($duration['years'] ? "It is a {$duration['years']}-year program." : null)),
        };
        $courseCount = $duration['courses'] ? trim((string) $duration['courses']) : null;
        $creditHours = $duration['credit_hours'] ? trim((string) $duration['credit_hours']) : null;
        $metricsFact = match ($language) {
            'urdu' => $courseCount || $creditHours
                ? 'اس میں '.collect([
                    $courseCount ? $courseCount.(preg_match('/course/i', $courseCount) ? '' : ' کورسز') : null,
                    $duration['projects'] ? "{$duration['projects']} فائنل ایئر پراجیکٹس" : null,
                    $creditHours ? 'کل '.$creditHours.(preg_match('/credit/i', $creditHours) ? '' : ' کریڈٹ آورز') : null,
                ])->filter()->implode('، ').' شامل ہیں۔'
                : null,
            'roman_urdu' => $courseCount || $creditHours
                ? 'Is mein '.collect([
                    $courseCount ? $courseCount.(preg_match('/course/i', $courseCount) ? '' : ' courses') : null,
                    $duration['projects'] ? "{$duration['projects']} Final Year Projects" : null,
                    $creditHours ? 'total '.$creditHours.(preg_match('/credit/i', $creditHours) ? '' : ' credit hours') : null,
                ])->filter()->implode(', ').' hain.'
                : null,
            default => $courseCount || $creditHours
                ? 'It includes '.collect([
                    $courseCount ? $courseCount.(preg_match('/course/i', $courseCount) ? '' : ' courses') : null,
                    $duration['projects'] ? "{$duration['projects']} Final Year Projects" : null,
                    $creditHours ? $creditHours.(preg_match('/credit/i', $creditHours) ? '' : ' credit hours') : null,
                ])->filter()->implode(', ').'.'
                : null,
        };
        $intakeFact = $intake && str_contains(Str::lower($intake), 'twice a year')
            ? match ($language) {
                'urdu' => 'داخلے سال میں دو مرتبہ، اسپرنگ اور فال میں ہوتے ہیں۔',
                'roman_urdu' => 'Intake saal mein do dafa, Spring aur Fall mein hota hai.',
                default => 'Admissions are offered twice a year, in Spring and Fall.',
            }
            : null;
        $maximumLoadFact = $maximumLoad ? match ($language) {
            'urdu' => "زیادہ سے زیادہ لوڈ: {$maximumLoad}۔",
            'roman_urdu' => "Maximum load {$maximumLoad} hai.",
            default => "The maximum load is {$maximumLoad}.",
        } : null;
        $timeDurationFact = $timeDuration ? match ($language) {
            'urdu' => "مکمل کرنے کی مدت: {$timeDuration}۔",
            'roman_urdu' => "Completion duration {$timeDuration} hai.",
            default => "The completion duration is {$timeDuration}.",
        } : null;

        $paragraphs = match ($language) {
            'urdu' => collect([
                "KASBIT {$displayTitle} پروگرام آفر کرتا ہے۔",
                $durationFact,
                $metricsFact,
                $intakeFact,
                $maximumLoadFact,
                $timeDurationFact,
                $courses->isNotEmpty() ? 'اہم مضامین: '.$courses->implode('، ').'۔' : null,
                $eligibility,
                $hasAdmissionTest || $hasInterview
                    ? 'داخلے کے عمل میں '.collect([
                        $hasAdmissionTest ? 'مطلوبہ داخلہ ٹیسٹ' : null,
                        $hasInterview ? 'فائنل انٹرویو' : null,
                    ])->filter()->implode(' اور ').' شامل ہیں۔'
                    : null,
            ]),
            'roman_urdu' => collect([
                "KASBIT {$displayTitle} program offer karta hai.",
                $durationFact,
                $metricsFact,
                $intakeFact,
                $maximumLoadFact,
                $timeDurationFact,
                $courses->isNotEmpty() ? 'Main subjects mein '.$courses->implode(', ').' shamil hain.' : null,
                $eligibility,
                $hasAdmissionTest || $hasInterview
                    ? 'Admission process mein '.collect([
                        $hasAdmissionTest ? 'required admission test' : null,
                        $hasInterview ? 'final interview' : null,
                    ])->filter()->implode(' aur ').' shamil hain.'
                    : null,
            ]),
            default => collect([
                "KASBIT offers the {$displayTitle} program.",
                $durationFact,
                $metricsFact,
                $intakeFact,
                $maximumLoadFact,
                $timeDurationFact,
                $courses->isNotEmpty() ? 'Key subjects include '.$courses->implode(', ').'.' : null,
                $eligibility,
                $hasAdmissionTest || $hasInterview
                    ? 'The admission process includes '.collect([
                        $hasAdmissionTest ? 'the required admission test' : null,
                        $hasInterview ? 'a final interview' : null,
                    ])->filter()->implode(' and ').'.'
                    : null,
            ]),
        };
        $sourceLabel = match ($language) {
            'urdu' => 'مزید معلومات',
            'roman_urdu' => 'Mazeed maloomat',
            default => 'More information',
        };
        $linkLabel = $isBscs ? 'KASBIT BSCS official page' : 'KASBIT official programme page';
        $sourceLine = $source ? "\n\n{$sourceLabel}: [{$linkLabel}]({$source})" : '';

        return trim($paragraphs->filter()->implode("\n\n").$sourceLine);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, string>  $lines
     * @return \Illuminate\Support\Collection<int, string>
     */
    private function programCourseHighlights(\Illuminate\Support\Collection $lines): \Illuminate\Support\Collection
    {
        $schemaIndex = $lines->search(
            fn (string $line) => ChatbotText::normalize($line) === 'program schema',
        );

        if ($schemaIndex === false) {
            return collect();
        }

        return $lines->slice($schemaIndex + 1)
            ->reject(function (string $line) {
                $normalized = ChatbotText::normalize($line);

                return $line === '<'
                    || preg_match('/^\d+(?:\s*\+\s*\d+)?$/', $line)
                    || preg_match('/^semester\s+(?:[ivx]+|\d+)$/i', $line)
                    || preg_match('/^semester credit hours$/i', $line)
                    || in_array($normalized, [
                        'subject', 'subjects', 'credit hours', 'deficiency courses',
                    ], true)
                    || mb_strlen($line) > 120;
            })
            ->filter(fn (string $line) => preg_match('/[\pL]/u', $line) === 1)
            ->map(fn (string $line) => trim((string) preg_replace('/\s*\([^)]*\)\s*$/u', '', $line)))
            ->unique(fn (string $line) => ChatbotText::normalize($line))
            ->take(8)
            ->values();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, string>  $lines
     */
    private function programEligibilitySummary(\Illuminate\Support\Collection $lines, string $language): ?string
    {
        $eligibilityIndex = $lines->search(
            fn (string $line) => ChatbotText::normalize($line) === 'eligibility',
        );
        $schemaIndex = $lines->search(
            fn (string $line) => ChatbotText::normalize($line) === 'program schema',
        );

        if ($eligibilityIndex === false) {
            return null;
        }

        $eligibilityText = $lines
            ->slice($eligibilityIndex + 1, $schemaIndex !== false ? $schemaIndex - $eligibilityIndex - 1 : 12)
            ->implode(' ');
        $educationYears = null;

        if (preg_match('/(?:completed|at least)\s+(\d+)\s+years? (?:of )?(?:formal )?education/i', $eligibilityText, $match)) {
            $educationYears = $match[1];
        }

        $criteria = collect([
            str_contains($eligibilityText, '50%') ? '50% marks' : null,
            preg_match('/minimum\s+1st Division/i', $eligibilityText) ? '1st Division' : null,
            preg_match('/minimum\s+2nd Division/i', $eligibilityText) ? '2nd Division' : null,
            preg_match('/2\.5\s+CGPA/i', $eligibilityText) ? '2.5 CGPA' : null,
            preg_match('/CGPA of\s+3\.00|minimum CGPA of\s+3\.00/i', $eligibilityText) ? '3.00 CGPA' : null,
        ])->filter()->unique()->values();

        if (! $educationYears && $criteria->isEmpty()) {
            return null;
        }

        return match ($language) {
            'urdu' => 'اہلیت کے لیے '.collect([
                $educationYears ? "{$educationYears} سالہ تعلیم یا مساوی قابلیت" : null,
                $criteria->isNotEmpty() ? 'کم از کم '.$criteria->implode(' / ') : null,
            ])->filter()->implode(' اور ').' ضروری ہے۔',
            'roman_urdu' => 'Eligibility ke liye '.collect([
                $educationYears ? "{$educationYears} years education ya equivalent qualification" : null,
                $criteria->isNotEmpty() ? 'kam az kam '.$criteria->implode(' / ') : null,
            ])->filter()->implode(' aur ').' required hai.',
            default => 'Eligibility requires '.collect([
                $educationYears ? "{$educationYears} years of education or an equivalent qualification" : null,
                $criteria->isNotEmpty() ? 'at least '.$criteria->implode(' / ') : null,
            ])->filter()->implode(' and ').'.',
        };
    }

    private function facultyProfileFallback(string $context, string $language, ?string $source): ?string
    {
        $lines = collect(preg_split('/\R+/u', $context) ?: [])
            ->map(fn (string $line) => trim($line))
            ->reject(fn (string $line) => $line === ''
                || str_starts_with($line, 'External knowledge ')
                || str_starts_with($line, 'Source: '))
            ->values();
        $name = $this->lineAfterLabel($lines, 'Name');

        if (! $name || ! preg_match('/^[\pL][\pL .\'-]{2,100}$/u', $name)) {
            return null;
        }

        $role = $lines->first(fn (string $line) => mb_strlen($line) <= 100
            && preg_match('/\b(?:assistant professor|associate professor|professor|lecturer|cluster head|dean|director|registrar|department head)\b/i', $line));
        $department = $lines->first(fn (string $line) => mb_strlen($line) <= 100
            && preg_match('/\b(?:computer sciences?|business administration|management sciences?|social sciences?|commerce|engineering|humanities)\b/i', $line));
        $summary = $this->lineAfterLabel($lines, 'Profile Summary');
        $research = $this->lineAfterLabel($lines, 'Research Interests');

        if (! $role && ! $summary) {
            return null;
        }

        $lead = match ($language) {
            'urdu' => $department && $role
                ? "{$name} KASBIT کے {$department} ڈیپارٹمنٹ میں {$role} ہیں۔"
                : "{$name} KASBIT میں {$role} ہیں۔",
            'roman_urdu' => $department && $role
                ? "{$name} KASBIT ke {$department} department mein {$role} hain."
                : "{$name} KASBIT mein {$role} hain.",
            default => $department && $role
                ? "{$name} is {$role} in KASBIT's {$department} department."
                : "{$name} is {$role} at KASBIT.",
        };
        $details = $this->facultyProfileDetails($summary, $research, $language);
        $sourceLabel = match ($language) {
            'urdu' => 'مزید معلومات',
            'roman_urdu' => 'Mazeed maloomat',
            default => 'More information',
        };
        $sourceLine = $source ? "\n\n{$sourceLabel}: [KASBIT official profile]({$source})" : '';

        return trim($lead.($details !== '' ? "\n\n{$details}" : '').$sourceLine);
    }

    private function facultyProfileDetails(?string $summary, ?string $research, string $language): string
    {
        if ($language !== 'roman_urdu') {
            return collect([
                $summary ? $this->completeSourceText($summary, 700) : null,
                $research ? match ($language) {
                    'urdu' => 'تحقیقی دلچسپیاں: '.$this->completeSourceText($research, 350),
                    default => 'Research interests: '.$this->completeSourceText($research, 350),
                } : null,
            ])->filter()->implode("\n\n");
        }

        $details = collect();

        if ($summary && preg_match('/\baround\s+([a-z]+|\d+)\s+years? of experience\b/i', $summary, $match)) {
            $years = [
                'twenty' => '20',
                'twenty-five' => '25',
                'thirty' => '30',
                'thirty-five' => '35',
                'forty' => '40',
            ][Str::lower($match[1])] ?? $match[1];
            $details->push("Inke paas taqreeban {$years} saal ka professional tajurba hai.");
        }

        if ($summary && preg_match('/\bat KASBIT since (?:the )?year\s+(\d{4})\b/i', $summary, $match)) {
            $details->push("Yeh {$match[1]} se KASBIT se wabasta hain.");
        }

        if ($summary && $details->isEmpty()) {
            $details->push($this->completeSourceText($summary, 700));
        }

        if ($research) {
            $interests = null;

            if (preg_match('/\binclude\s+(.+?)(?:\s+etc\.)?(?:\s+(?:His|Her|Their) work\b|$)/i', $research, $match)) {
                $interests = trim(rtrim($match[1], " ,.;"));
            }

            $details->push($interests
                ? "Inki research interests mein {$interests} shamil hain."
                : 'Research interests: '.$this->completeSourceText($research, 350));
        }

        return $details->filter()->implode("\n\n");
    }

    private function completeSourceText(string $text, int $limit): string
    {
        $text = Str::limit(trim($text), $limit, '...');

        if (! str_ends_with($text, '...')
            && ! preg_match('/[.!?]$/u', $text)
            && preg_match('/^(.+[.!?])\s+[^.!?]*$/us', $text, $match)) {
            return trim($match[1]);
        }

        return $text;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, string>  $lines
     */
    private function lineAfterLabel(\Illuminate\Support\Collection $lines, string $label): ?string
    {
        $normalizedLabel = Str::lower(rtrim($label, ':'));

        foreach ($lines as $index => $line) {
            $normalizedLine = Str::lower(trim($line));
            $lineLabel = rtrim($normalizedLine, ':');

            if ($lineLabel === $normalizedLabel) {
                return $lines->get($index + 1);
            }

            if (preg_match('/^'.preg_quote(rtrim($label, ':'), '/').'\s*:\s*(.*)$/iu', $line, $match)) {
                $value = trim($match[1]);

                return $value !== '' ? $value : $lines->get($index + 1);
            }
        }

        return null;
    }

    /**
     * @return array<int, array{role: string, content: string}>
     */
    private function recentHistory(Request $request, ChatbotSetting $settings): array
    {
        $sessionHistory = $request->session()->get('chatbot_recent_messages', []);

        if (is_array($sessionHistory) && $sessionHistory !== []) {
            return collect($sessionHistory)
                ->filter(fn ($message) => is_array($message)
                    && in_array($message['role'] ?? null, ['user', 'assistant'], true)
                    && is_string($message['content'] ?? null))
                ->take(-12)
                ->values()
                ->all();
        }

        if (! $settings->save_history || ! ($conversation = $this->currentConversation($request))) {
            return [];
        }

        return $conversation->messages()
            ->orderByDesc('id')
            ->limit(12)
            ->get()
            ->reverse()
            ->map(fn (ChatbotMessage $message) => [
                'role' => $message->role,
                'content' => $message->content,
            ])
            ->values()
            ->all();
    }

    /**
     * Check if an answer is just a clarification question (not a real answer).
     */
    private function isClarificationAnswer(string $answer): bool
    {
        $normalized = ChatbotText::normalize($answer);

        // If the answer ends with "?" and contains clarification phrases, it's not a real answer
        if (! str_contains($answer, '?')) {
            return false;
        }

        $clarificationPhrases = [
            'kya aap', 'ke baare', 'pooch rahe', 'puch rahe',
            'do you mean', 'are you asking', 'which', 'konsa', 'kaun sa',
            'could you clarif', 'can you clarif', 'please clarif',
            'bataye', 'batao', 'specify',
        ];

        foreach ($clarificationPhrases as $phrase) {
            if (str_contains($normalized, $phrase)) {
                return true;
            }
        }

        // If the answer is very short (under 100 chars) and is just a question
        if (Str::length($answer) < 100 && str_ends_with(trim($answer), '?')) {
            return true;
        }

        return false;
    }

    /**
     * If the user says "yes", "han", "ji", etc. resolve the pending topic from previous clarification.
     */
    private function resolvePendingContext(Request $request, string $question): string
    {
        $normalized = ChatbotText::normalize($question);
        $affirmatives = [
            'yes', 'yeah', 'yep', 'yup', 'sure', 'ok', 'okay',
            'han', 'haan', 'ji', 'ji han', 'ha', 'haji',
            'yes please', 'han ji', 'bilkul', 'zaroor', 'theek hai',
            'thik hai', 'right', 'correct', 'exactly',
        ];

        $isAffirmative = in_array($normalized, $affirmatives, true)
            || Str::length($normalized) <= 12 && collect($affirmatives)->contains(fn ($word) => str_contains($normalized, $word));

        if (! $isAffirmative) {
            // Not an affirmative — clear any pending context and use question as-is
            $request->session()->forget('chatbot_pending_context');
            return $question;
        }

        $pending = $request->session()->get('chatbot_pending_context');

        if (! $pending || ! is_string($pending)) {
            return $question;
        }

        // Clear the pending context and use the stored topic
        $request->session()->forget('chatbot_pending_context');

        return $pending;
    }

    /**
     * Store the user's question as pending context if the AI's answer is a clarification question.
     */
    private function storePendingContext(Request $request, string $question, string $answer): void
    {
        $answerNormalized = ChatbotText::normalize($answer);

        // If the answer contains "?" it's likely asking for clarification
        $isClarification = str_contains($answer, '?')
            && (str_contains($answerNormalized, 'kya aap')
                || str_contains($answerNormalized, 'do you mean')
                || str_contains($answerNormalized, 'are you asking')
                || str_contains($answerNormalized, 'ke baare')
                || str_contains($answerNormalized, 'which')
                || str_contains($answerNormalized, 'konsa')
                || str_contains($answerNormalized, 'kaun sa')
                || str_contains($answerNormalized, 'clarif')
                || str_contains($answerNormalized, 'pooch rahe')
                || str_contains($answerNormalized, 'puch rahe'));

        if ($isClarification) {
            $request->session()->put('chatbot_pending_context', $question);
        } else {
            $request->session()->forget('chatbot_pending_context');
        }
    }

    private function rememberRecentExchange(Request $request, string $question, string $answer): void
    {
        $history = collect($request->session()->get('chatbot_recent_messages', []))
            ->filter(fn ($message) => is_array($message))
            ->push(['role' => 'user', 'content' => ChatbotText::plainText($question, 3000)])
            ->push(['role' => 'assistant', 'content' => ChatbotText::plainText($answer, 3000)])
            ->take(-12)
            ->values()
            ->all();

        $request->session()->put('chatbot_recent_messages', $history);
    }

    private function isLanguageInstruction(string $question): bool
    {
        $question = ChatbotText::normalize($question);
        $phrases = [
            'roman english mai jawab do',
            'roman english me jawab do',
            'roman english main jawab do',
            'roman english mein jawab do',
            'roman urdu mai jawab do',
            'roman urdu me jawab do',
            'roman urdu main jawab do',
            'roman urdu mein jawab do',
            'urdu mai jawab do',
            'urdu me jawab do',
            'urdu main jawab do',
            'urdu mein jawab do',
            'english mai jawab do',
            'english me jawab do',
            'english main jawab do',
            'english mein jawab do',
            'simple batao',
            'simple bataye',
            'thora simple batao',
            'dobara samjhao',
            'dobara samjhaye',
            'detail mai batao',
            'detail main batao',
            'detail mein batao',
        ];

        foreach ($phrases as $phrase) {
            if (str_contains($question, $phrase)) {
                return true;
            }
        }

        return false;
    }

    private function detectResponseLanguage(string $question): string
    {
        $normalized = ChatbotText::normalize($question);

        if (preg_match('/[\x{0600}-\x{06FF}]/u', $question) === 1) {
            return 'urdu';
        }

        if (preg_match('/\b(?:roman urdu|roman english)\b/u', $normalized) === 1) {
            return 'roman_urdu';
        }

        if (preg_match('/\burdu\b.*\b(?:answer|reply|jawab)\b|\b(?:answer|reply|jawab)\b.*\burdu\b/u', $normalized) === 1) {
            return 'urdu';
        }

        if (preg_match('/\b(?:answer|reply|jawab)\b.*\benglish\b|\benglish\b.*\b(?:answer|reply|jawab)\b/u', $normalized) === 1) {
            return 'english';
        }

        $words = array_values(array_filter(explode(' ', $normalized)));
        $strongRomanUrdu = [
            'acha', 'achaa', 'asan', 'asaan', 'bata', 'batao', 'bataye', 'btao',
            'chahiye', 'dekho', 'dobara', 'hain', 'hoga', 'hogi', 'kab', 'kaise',
            'kaun', 'kese', 'kesay', 'kahan', 'kia', 'kidhar', 'kiya', 'kon', 'kya', 'kyun',
            'mujhay', 'mujhe', 'mujy', 'samjhao', 'thora', 'thori', 'yaar', 'zaroor',
        ];
        $supportingRomanUrdu = [
            'aap', 'ab', 'ap', 'aur', 'hai', 'ka', 'ke', 'ki', 'ko', 'mai', 'main',
            'mein', 'kar', 'ky', 'liye', 'nahi', 'par', 'pe', 'ra', 'raha', 'rahi',
            'rha', 'rhi', 'se', 'tha', 'thi', 'to', 'tu', 'wo', 'ye', 'yeh',
        ];

        if (array_intersect($words, $strongRomanUrdu) !== []
            || count(array_unique(array_intersect($words, $supportingRomanUrdu))) >= 2) {
            return 'roman_urdu';
        }

        return 'english';
    }

    private function responseLanguageInstruction(string $language): string
    {
        return match ($language) {
            'urdu' => 'CURRENT RESPONSE LANGUAGE (highest priority): Reply only in Urdu script. The latest user message determines the response language; do not copy the language of older conversation turns.',
            'roman_urdu' => 'CURRENT RESPONSE LANGUAGE (highest priority): Reply only in casual, friendly Roman Urdu using Latin/English letters. Use simple everyday language like a helpful friend would. Never use formal words like Kripya, Barae, Guzarish. Do not use Urdu or Arabic script. The latest user message determines the response language; do not copy the language of older conversation turns.',
            default => 'CURRENT RESPONSE LANGUAGE (highest priority): Reply only in English in a casual friendly tone. Do not reply in Roman Urdu or Urdu script. The latest user message determines the response language; do not copy the language of older conversation turns.',
        };
    }

    private function responseQualityInstructions(): string
    {
        return <<<'PROMPT'
Understand questions written in English, Urdu script, or informal Roman Urdu/Roman English, including common spelling variations. Detect the visitor's language and reply in the same language and style. For Roman Urdu, use simple, casual, friendly Roman Urdu in Latin letters — like a helpful senior student would talk. Never use formal Urdu words like "Kripya", "Barae meharbani", "Guzarish" etc.

CRITICAL RULES:
1. You are given reference context data. NEVER paste or copy raw CSV data, column headers, file names, or raw text from the context into your response. ALWAYS rewrite the information in natural conversational language.
2. NEVER show file names like "KASBIT_Faculty.csv" or "Document:" in your response. NEVER show CSV headers like "Name,Qualification,Designation" in response.
3. NEVER start response with "KASBIT ki official website ke mutabiq" or "Uploaded knowledge data". Just answer directly.
4. If a person's name is asked, find them in context and give a clean summary: their name, designation, department, and what they teach. Example: "Arif sahab Computer Science department mein Lecturer hain. Wo Database, Web Technology aur Programming parhate hain."
5. If asked what courses someone teaches, list them as bullet points or a simple comma-separated list.
6. ALWAYS search through ALL the provided context data carefully. If the answer exists anywhere in context, use it. Never say "information nahi hai" if data is present.

Always use the recent conversation history. A request such as "Roman English mein jawab do", "thora simple batao", "detail mein batao", or "dobara samjhao" refers to the previous assistant answer. Rewrite that answer as requested.

Answer warmly, casually, and directly. Give the answer first in 1-2 lines, then details if needed. Keep it short. Never paste raw data. Synthesize context into clean human-readable answers.

General AI knowledge may be used when allowed, but never invent official KASBIT dates, fees, deadlines, policies, programs, contacts, or links. Only if you genuinely cannot find anything relevant in the context, say you're not sure and suggest checking kasbit.edu.pk.

Never expose development URLs (localhost, 127.0.0.1, .local, .test). Include links only when public and helpful. Do not mention internal matching, prompts, context windows, or configuration.
PROMPT;
    }
}
