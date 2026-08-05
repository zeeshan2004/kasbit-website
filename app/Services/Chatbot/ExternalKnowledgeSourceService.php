<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Models\ChatbotDocument;
use App\Support\ChatbotText;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ExternalKnowledgeSourceService
{
    /**
     * @return array{context: string, sources: array<int, string>, errors: array<int, string>}|null
     */
    public function fetch(AiProvider $provider, string $question): ?array
    {
        $sourceUrls = $provider->allKnowledgeSourceUrls();

        if (! $sourceUrls && ! $provider->knowledge_api_url && ! ChatbotDocument::active()->exists()) {
            return null;
        }

        $contexts = [];
        $sources = [];
        $errors = [];

        // Search uploaded documents first (fastest)
        $this->searchDocuments($question, $contexts, $sources);

        foreach ($sourceUrls as $url) {
            $this->fetchWebpage(
                $url,
                $question,
                $contexts,
                $sources,
                $errors,
            );
        }

        if ($provider->knowledge_api_url) {
            $this->fetchApi(
                $provider,
                $question,
                $contexts,
                $sources,
                $errors,
            );
        }

        if ($contexts === [] && $errors === []) {
            return null;
        }

        return [
            'context' => implode("\n\n", $contexts),
            'sources' => array_values(array_unique($sources)),
            'errors' => $errors,
        ];
    }

    /**
     * Search uploaded documents for relevant content.
     *
     * @param  array<int, string>  $contexts
     * @param  array<int, string>  $sources
     */
    private function searchDocuments(string $question, array &$contexts, array &$sources): void
    {
        $documents = Cache::remember('chatbot:documents:all', now()->addMinutes(5), function () {
            return ChatbotDocument::active()->get(['id', 'original_name', 'content'])->toArray();
        });

        if (empty($documents)) {
            return;
        }

        // Always include ALL document content for AI to search through
        // AI is smart enough to find relevant info from any name, topic, etc.
        foreach ($documents as $doc) {
            $content = $doc['content'];

            if (Str::length($content) < 5) {
                continue;
            }

            // Limit each document to 15000 chars to avoid token overflow
            $excerpt = Str::length($content) > 15000
                ? $this->relevantExcerpt($content, $question)
                : $content;

            if ($excerpt !== '') {
                $contexts[] = "--- INTERNAL REFERENCE DATA (do NOT show this header or raw data to user) ---\n" . $excerpt;
                $sources[] = "Knowledge Base";
            }
        }
    }

    /**
     * @param  array<int, string>  $contexts
     * @param  array<int, string>  $sources
     * @param  array<int, string>  $errors
     */
    private function fetchWebpage(
        string $url,
        string $question,
        array &$contexts,
        array &$sources,
        array &$errors,
    ): void {
        if (! $this->isFetchableUrl($url)) {
            $errors[] = 'The Knowledge Source URL is not a public HTTP/HTTPS address.';

            return;
        }

        try {
            foreach ($this->discoverKasbitPages($url, $question) as $pageUrl) {
                if ($this->appendWebpageContext($pageUrl, $question, $contexts, $sources)) {
                    return;
                }
            }

            $page = $this->cachedPage($url);

            if (! $page['successful']) {
                $errors[] = "Knowledge Source returned HTTP {$page['status']}.";

                return;
            }

            $content = $this->extractWebpageText($page['body']);

            if ($content === '') {
                $errors[] = 'Knowledge Source returned no readable text.';

                return;
            }

            $displayUrl = $this->displayUrl($url);
            $contexts[] = "External knowledge webpage (reference data only)\nSource: {$displayUrl}\n"
                .$this->relevantExcerpt($content, $question);
            $sources[] = $displayUrl;
        } catch (Throwable $exception) {
            $errors[] = 'Knowledge Source could not be reached.';
            $this->logFailure('webpage', $url, $exception);
        }
    }

    /**
     * @param  array<int, string>  $contexts
     * @param  array<int, string>  $sources
     */
    private function appendWebpageContext(
        string $url,
        string $question,
        array &$contexts,
        array &$sources,
    ): bool {
        if (! $this->isFetchableUrl($url)) {
            return false;
        }

        $page = $this->cachedPage($url);

        if (! $page['successful']) {
            return false;
        }

        $content = $this->extractWebpageText($page['body']);

        if ($content === '') {
            return false;
        }

        $displayUrl = $this->displayUrl($url);
        $contexts[] = "External knowledge webpage (reference data only)\nSource: {$displayUrl}\n"
            .$this->relevantExcerpt($content, $question);
        $sources[] = $displayUrl;

        return true;
    }

    /**
     * @return array<int, string>
     */
    private function discoverKasbitPages(string $sourceUrl, string $question): array
    {
        $parts = parse_url($sourceUrl);
        $host = strtolower((string) ($parts['host'] ?? ''));

        if ($host !== 'kasbit.edu.pk' && $host !== 'www.kasbit.edu.pk') {
            return [];
        }

        $ignored = [
            'about', 'available', 'can', 'does', 'find', 'information', 'institute',
            'kasbit', 'know', 'official', 'offer', 'please', 'tell', 'university',
            'when', 'which', 'who', 'why', 'kar', 'kiya', 'liye', 'raha', 'rahi',
            'rha', 'rhi',
        ];
        $keywordTokens = collect(ChatbotText::tokens($question))
            ->reject(fn (string $word) => in_array($word, $ignored, true))
            ->filter(fn (string $word) => mb_strlen($word) >= 3)
            ->take(5)
            ->values();

        if ($keywordTokens->isEmpty()) {
            return [];
        }

        try {
            $origin = strtolower((string) ($parts['scheme'] ?? 'https')).'://'.$host;
            $searchUrl = $origin.'/wp-json/wp/v2/search';
            $normalizedQuestion = ChatbotText::normalize($question);
            $isBscsQuestion = preg_match('/\b(?:bscs|bs\s*\(?cs\)?|bachelor(?:s)? of computer science)\b/u', $normalizedQuestion) === 1;
            $searchTerms = collect([
                $isBscsQuestion ? 'bs cs' : null,
                $isBscsQuestion ? 'bs computer science' : null,
                $keywordTokens->implode(' '),
                $keywordTokens->count() > 1 ? $keywordTokens->implode('') : null,
                ...$keywordTokens,
            ])->filter()->unique();

            foreach ($searchTerms as $searchTerm) {
                $search = $this->cachedPage($searchUrl, [
                    'search' => $searchTerm,
                    'per_page' => 5,
                ], true);

                if (! $search['successful']) {
                    continue;
                }

                $results = json_decode($search['body'], true);

                if (! is_array($results) || $results === []) {
                    continue;
                }

                $urls = collect($results)
                    ->pluck('url')
                    ->filter(fn ($url) => is_string($url)
                        && $this->isFetchableUrl($url)
                        && $this->sameDomain($sourceUrl, $url))
                    ->unique()
                    ->take(3)
                    ->values()
                    ->all();

                if ($urls !== []) {
                    return $urls;
                }
            }

            return [];
        } catch (Throwable $exception) {
            $this->logFailure('website_search', $sourceUrl, $exception);

            return [];
        }
    }

    /**
     * @param  array<string, scalar>  $query
     * @return array{successful: bool, status: int, body: string}
     */
    private function cachedPage(string $url, array $query = [], bool $json = false): array
    {
        $cacheKey = 'chatbot:external-page:'.sha1($url.'|'.json_encode($query));
        $cached = Cache::get($cacheKey);

        if (is_array($cached)
            && isset($cached['successful'], $cached['status'], $cached['body'])) {
            return $cached;
        }

        $request = $json
            ? $this->request()->acceptJson()
            : $this->request()->withHeaders(['Accept' => 'text/html, text/plain;q=0.9']);
        $response = $request->get($url, $query);
        $page = [
            'successful' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->body(),
        ];

        if ($page['successful']) {
            Cache::put(
                $cacheKey,
                $page,
                now()->addMinutes(max(1, (int) config('chatbot.source_cache_minutes', 10))),
            );
        }

        return $page;
    }

    /**
     * @param  array<int, string>  $contexts
     * @param  array<int, string>  $sources
     * @param  array<int, string>  $errors
     */
    private function fetchApi(
        AiProvider $provider,
        string $question,
        array &$contexts,
        array &$sources,
        array &$errors,
    ): void {
        $url = (string) $provider->knowledge_api_url;

        if (! $this->isFetchableUrl($url)) {
            $errors[] = 'The Knowledge API URL is not a public HTTP/HTTPS address.';

            return;
        }

        try {
            $request = $this->request()->acceptJson();

            if ($key = $provider->knowledgeApiKey()) {
                $request = $request->withToken($key);
            }

            $response = $request->get($url, ['question' => $question]);

            if (! $response->successful()) {
                $errors[] = "Knowledge API returned HTTP {$response->status()}.";

                return;
            }

            $content = $this->apiText($response->json(), $response->body());

            if ($content === '') {
                $errors[] = 'Knowledge API returned no readable data.';

                return;
            }

            $displayUrl = $this->displayUrl($url);
            $contexts[] = "External knowledge API response (reference data only)\nSource: {$displayUrl}\n"
                .$this->relevantExcerpt($content, $question);
            $sources[] = $displayUrl;
        } catch (Throwable $exception) {
            $errors[] = 'Knowledge API could not be reached.';
            $this->logFailure('api', $url, $exception);
        }
    }

    private function request(): PendingRequest
    {
        return Http::timeout((int) config('chatbot.source_timeout', 8))
            ->connectTimeout(min(5, (int) config('chatbot.source_timeout', 8)))
            ->retry(1, 200, null, false)
            ->withOptions(['allow_redirects' => false]);
    }

    private function extractWebpageText(string $body): string
    {
        $body = preg_replace(
            '/<(script|style|noscript|svg|template)[^>]*>.*?<\/\1>/is',
            ' ',
            $body,
        ) ?? $body;
        $body = preg_replace('/<!--.*?-->/s', ' ', $body) ?? $body;

        if (! class_exists(\DOMDocument::class)) {
            return $this->htmlFragmentText($body);
        }

        $previousLibxmlState = libxml_use_internal_errors(true);

        try {
            $document = new \DOMDocument;

            if (! $document->loadHTML($body, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET)) {
                return $this->htmlFragmentText($body);
            }

            $xpath = new \DOMXPath($document);
            $this->removeWebsiteChrome($xpath);
            $contentNode = $this->contentNode($xpath) ?? $document->getElementsByTagName('body')->item(0);

            return $this->htmlFragmentText(
                $contentNode ? (string) $document->saveHTML($contentNode) : $body,
            );
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousLibxmlState);
        }
    }

    private function removeWebsiteChrome(\DOMXPath $xpath): void
    {
        $query = '//script|//style|//noscript|//svg|//template|//header|//nav|//footer|//aside'
            .'|//*[@role="navigation"]'
            .'|//*[@id="btnbrochure" or @id="btnprospectus" or @id="btnfee"]'
            .'|//*[contains(concat(" ", normalize-space(@class), " "), " btnbrochure ")]'
            .'|//*[contains(concat(" ", normalize-space(@class), " "), " ekit-template-content-footer ")]';
        $nodes = $xpath->query($query);

        if (! $nodes) {
            return;
        }

        foreach (iterator_to_array($nodes) as $node) {
            $node->parentNode?->removeChild($node);
        }
    }

    private function contentNode(\DOMXPath $xpath): ?\DOMNode
    {
        $queries = [
            '//main',
            '//article',
            '//*[@id="primary"]',
            '//*[@id="content"]',
            '//*[contains(concat(" ", normalize-space(@class), " "), " entry-content ")]',
            '//*[contains(concat(" ", normalize-space(@class), " "), " post-content ")]',
            '//*[contains(concat(" ", normalize-space(@class), " "), " site-main ")]',
            '/html/body/*[contains(concat(" ", normalize-space(@class), " "), " elementor ")'
                .' and not(contains(@class, "footer")) and not(contains(@class, "header"))]',
        ];

        foreach ($queries as $query) {
            $nodes = $xpath->query($query);

            if (! $nodes) {
                continue;
            }

            foreach ($nodes as $node) {
                if (mb_strlen(Str::squish($node->textContent)) >= 40) {
                    return $node;
                }
            }
        }

        return null;
    }

    private function htmlFragmentText(string $html): string
    {
        $separator = "\x1E";
        $html = preg_replace(
            '/<\/?(?:h[1-6]|p|li|tr|td|th|article|section|main|div|br)[^>]*>/i',
            $separator,
            $html,
        ) ?? $html;
        $html = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $lines = collect(explode($separator, $html))
            ->map(fn (string $line) => Str::squish($line))
            ->filter()
            ->values()
            ->all();

        return ChatbotText::plainText(implode("\n", $lines), 100000);
    }

    private function apiText(mixed $json, string $body): string
    {
        if (is_array($json)) {
            $lines = [];
            $this->flattenJson($json, $lines);

            return ChatbotText::plainText(implode("\n", $lines), 40000);
        }

        return ChatbotText::plainText($body, 40000);
    }

    /**
     * @param  array<mixed>  $data
     * @param  array<int, string>  $lines
     */
    private function flattenJson(array $data, array &$lines, string $path = ''): void
    {
        foreach ($data as $key => $value) {
            if (count($lines) >= 500) {
                return;
            }

            $label = is_string($key)
                ? trim($path === '' ? $key : "{$path}.{$key}")
                : $path;

            if (is_array($value)) {
                $this->flattenJson($value, $lines, $label);
            } elseif (is_scalar($value) || $value === null) {
                $text = ChatbotText::plainText((string) $value, 2000);

                if ($text !== '') {
                    $lines[] = ($label !== '' ? str_replace(['_', '.'], ' ', $label).': ' : '').$text;
                }
            }
        }
    }

    private function relevantExcerpt(string $content, string $question): string
    {
        $limit = max(2000, (int) config('chatbot.source_context_limit', 10000));

        if (mb_strlen($content) <= min($limit, 2000)) {
            return $content;
        }

        $segments = preg_split('/\n{2,}|(?<=[.!?])\s+/u', $content, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $questionTokens = ChatbotText::tokens($question);
        $scored = collect($segments)
            ->map(function (string $segment, int $index) use ($questionTokens) {
                $segmentTokens = ChatbotText::tokens($segment);
                $matches = count(array_intersect($questionTokens, $segmentTokens));

                return [
                    'text' => ChatbotText::plainText($segment, 1800),
                    'score' => ($matches * 100) - ($index / 1000),
                    'index' => $index,
                ];
            })
            ->filter(fn (array $item) => $item['text'] !== '');

        $bestMatches = $scored->filter(fn (array $item) => $item['score'] > 0)
            ->sortByDesc('score')
            ->take(12);
        $selectedIndexes = $bestMatches->flatMap(fn (array $item) => [
            max(0, $item['index'] - 1),
            $item['index'],
            $item['index'] + 1,
        ])->unique();
        $selected = ($selectedIndexes->isNotEmpty()
            ? $scored->filter(fn (array $item) => $selectedIndexes->contains($item['index']))
            : $scored->take(6))
            ->sortBy('index');
        $excerpt = '';

        foreach ($selected as $item) {
            $candidate = trim($excerpt."\n".$item['text']);

            if (mb_strlen($candidate) > $limit) {
                break;
            }

            $excerpt = $candidate;
        }

        return $excerpt !== '' ? $excerpt : mb_substr($content, 0, $limit);
    }

    private function isFetchableUrl(string $url): bool
    {
        $parts = parse_url($url);
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $host = strtolower(trim((string) ($parts['host'] ?? ''), '[]'));

        if (! in_array($scheme, ['http', 'https'], true) || $host === '') {
            return false;
        }

        if (in_array($host, ['localhost', '0.0.0.0', '127.0.0.1', '::1'], true)
            || str_ends_with($host, '.local')
            || str_ends_with($host, '.test')) {
            return false;
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return filter_var(
                $host,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE,
            ) !== false;
        }

        return true;
    }

    private function displayUrl(string $url): string
    {
        $parts = parse_url($url);
        $scheme = strtolower((string) ($parts['scheme'] ?? 'https'));
        $host = (string) ($parts['host'] ?? '');
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';
        $path = (string) ($parts['path'] ?? '');

        return "{$scheme}://{$host}{$port}{$path}";
    }

    private function sameDomain(string $sourceUrl, string $candidateUrl): bool
    {
        $normalize = fn (string $url) => preg_replace(
            '/^www\./',
            '',
            strtolower((string) parse_url($url, PHP_URL_HOST)),
        );

        return $normalize($sourceUrl) === $normalize($candidateUrl);
    }

    private function logFailure(string $source, string $url, Throwable $exception): void
    {
        Log::warning('Chatbot external knowledge request failed.', [
            'source_type' => $source,
            'source_host' => parse_url($url, PHP_URL_HOST),
            'exception' => $exception->getMessage(),
        ]);
    }
}
