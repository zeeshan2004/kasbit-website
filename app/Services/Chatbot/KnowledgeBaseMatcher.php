<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotKnowledgeBase;
use App\Services\Chatbot\Data\KnowledgeMatch;
use App\Support\ChatbotText;

class KnowledgeBaseMatcher
{
    public function find(string $question): ?KnowledgeMatch
    {
        $normalized = ChatbotText::normalize($question);
        $hash = ChatbotText::hash($question);

        $exact = ChatbotKnowledgeBase::query()
            ->approved()
            ->with(['category', 'alternatives', 'relatedQuestions.relatedKnowledge'])
            ->where(function ($query) use ($hash) {
                $query->where('question_hash', $hash)
                    ->orWhereHas('alternatives', fn ($alternative) => $alternative->where('question_hash', $hash));
            })
            ->orderByDesc('priority')
            ->first();

        if ($exact) {
            return new KnowledgeMatch($exact, 100);
        }

        $best = null;
        $bestScore = 0.0;

        ChatbotKnowledgeBase::query()
            ->approved()
            ->with(['category', 'alternatives', 'relatedQuestions.relatedKnowledge'])
            ->orderByDesc('priority')
            ->chunkById(200, function ($items) use ($normalized, &$best, &$bestScore): void {
                foreach ($items as $knowledge) {
                    $candidates = collect([$knowledge->normalized_question])
                        ->merge($knowledge->alternatives->pluck('normalized_question'));

                    foreach ($candidates as $candidate) {
                        $score = $this->score($normalized, (string) $candidate, $knowledge->keywords);

                        if ($score > $bestScore) {
                            $best = $knowledge;
                            $bestScore = $score;
                        }
                    }
                }
            });

        return $best && $bestScore >= (float) config('chatbot.similarity_threshold', 72)
            ? new KnowledgeMatch($best, round($bestScore, 2))
            : null;
    }

    private function score(string $question, string $candidate, ?string $keywords): float
    {
        if ($question === '' || $candidate === '') {
            return 0;
        }

        similar_text($question, $candidate, $similarity);
        $questionTokens = ChatbotText::tokens($question);
        $candidateTokens = ChatbotText::tokens($candidate);
        $union = array_unique([...$questionTokens, ...$candidateTokens]);
        $intersection = array_intersect($questionTokens, $candidateTokens);
        $jaccard = count($union) ? (count($intersection) / count($union)) * 100 : 0;

        $substring = str_contains($question, $candidate) || str_contains($candidate, $question) ? 88 : 0;
        $keywordTokens = ChatbotText::tokens($keywords);
        $keywordScore = count($keywordTokens)
            ? (count(array_intersect($questionTokens, $keywordTokens)) / count($keywordTokens)) * 100
            : 0;

        return max($substring, ($similarity * 0.55) + ($jaccard * 0.35) + ($keywordScore * 0.10));
    }
}
