<?php

namespace App\Services\Chatbot;

use App\Models\HeaderMenuPage;
use App\Support\ChatbotText;
use Illuminate\Support\Str;

class WebsiteContentSearchService
{
    /**
     * @return array{answer: string, context: string, url: string, score: float}|null
     */
    public function search(string $question): ?array
    {
        $questionTokens = ChatbotText::tokens($question);

        if ($questionTokens === []) {
            return null;
        }

        $best = null;
        $bestScore = 0.0;

        HeaderMenuPage::query()
            ->whereHas('menu', fn ($query) => $query->where('is_active', true))
            ->with(['menu', 'slides' => fn ($query) => $query->where('is_active', true)])
            ->chunkById(100, function ($pages) use ($questionTokens, &$best, &$bestScore): void {
                foreach ($pages as $page) {
                    $content = collect([
                        $page->menu?->name,
                        $page->eyebrow,
                        $page->title,
                        $page->subtitle,
                        $page->content,
                        ...$page->slides->flatMap(fn ($slide) => [$slide->title, $slide->description]),
                    ])->filter()->implode(' ');

                    $tokens = ChatbotText::tokens($content);
                    $matches = array_intersect($questionTokens, $tokens);
                    $score = count($questionTokens) ? (count($matches) / count($questionTokens)) * 100 : 0;

                    if ($score > $bestScore) {
                        $best = [$page, $content];
                        $bestScore = $score;
                    }
                }
            });

        if (! $best || $bestScore < 45) {
            return null;
        }

        [$page, $content] = $best;
        $plain = ChatbotText::plainText($content, 3500);
        $title = $page->title ?: $page->menu?->name ?: 'KASBIT information';
        $url = route('pages.show', $page);
        $publicUrl = $this->isPublicUrl($url) ? $url : null;
        $answer = trim($title.': '.Str::limit($plain, 900, '...'));
        $context = "{$title}\n{$plain}";

        if ($publicUrl) {
            $answer .= "\nMore information: {$publicUrl}";
            $context .= "\nPublic page: {$publicUrl}";
        }

        return [
            'answer' => $answer,
            'context' => $context,
            'url' => $url,
            'score' => round($bestScore, 2),
        ];
    }

    private function isPublicUrl(string $url): bool
    {
        $host = strtolower(trim((string) parse_url($url, PHP_URL_HOST), '[]'));

        if ($host === ''
            || in_array($host, ['localhost', '0.0.0.0', '127.0.0.1', '::1'], true)
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
}
