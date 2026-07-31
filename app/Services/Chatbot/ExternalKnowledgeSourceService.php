<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Support\ChatbotText;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExternalKnowledgeSourceService
{
    /**
     * @return array{context: string, sources: array<int, string>, errors: array<int, string>}|null
     */
    public function fetch(AiProvider $provider, string $question): ?array
    {
        if (! $provider->knowledge_source_url && ! $provider->knowledge_api_url) {
            return null;
        }

        $contexts = [];
        $sources = [];
        $errors = [];

        if ($provider->knowledge_source_url) {
            $this->fetchWebpage(
                $provider->knowledge_source_url,
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
            'when', 'which', 'who', 'why',
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
            $searchTerms = collect([
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
        $body = preg_replace('/<\/?(?:h[1-6]|p|li|tr|td|th|article|section|main|div|br)[^>]*>/i', "\n", $body) ?? $body;
        $body = preg_replace('/<!--.*?-->/s', ' ', $body) ?? $body;
        $body = preg_replace('/<[^>]+>/s', ' ', $body) ?? $body;
        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return ChatbotText::plainText($body, 100000);
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

        $selected = $scored->take(3)
            ->merge($scored->sortByDesc('score')->take(12))
            ->unique('index')
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
