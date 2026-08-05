<?php

namespace App\Console\Commands;

use App\Models\AiProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WarmChatbotSourceCache extends Command
{
    protected $signature = 'chatbot:warm-cache';

    protected $description = 'Pre-fetch and cache all knowledge source URLs so chatbot responses are instant';

    public function handle(): int
    {
        $providers = AiProvider::where('is_active', true)->get();
        $urls = [];

        foreach ($providers as $provider) {
            foreach ($provider->allKnowledgeSourceUrls() as $url) {
                $urls[] = $url;
            }
        }

        $urls = array_unique($urls);

        if (empty($urls)) {
            $this->info('No knowledge source URLs configured.');
            return self::SUCCESS;
        }

        $this->info('Warming cache for ' . count($urls) . ' URL(s)...');
        $cached = 0;
        $failed = 0;

        foreach ($urls as $url) {
            $cacheKey = 'chatbot:external-page:' . sha1($url . '|[]');

            // Skip if already cached
            if (Cache::has($cacheKey)) {
                $this->line("  [cached] {$url}");
                $cached++;
                continue;
            }

            try {
                $response = Http::timeout(10)
                    ->connectTimeout(5)
                    ->withHeaders(['Accept' => 'text/html, text/plain;q=0.9'])
                    ->get($url);

                $page = [
                    'successful' => $response->successful(),
                    'status' => $response->status(),
                    'body' => $response->body(),
                ];

                if ($page['successful']) {
                    Cache::put(
                        $cacheKey,
                        $page,
                        now()->addMinutes(max(1, (int) config('chatbot.source_cache_minutes', 60))),
                    );
                    $this->line("  [fetched] {$url}");
                    $cached++;
                } else {
                    $this->warn("  [HTTP {$page['status']}] {$url}");
                    $failed++;
                }
            } catch (\Throwable $e) {
                $this->error("  [error] {$url} — {$e->getMessage()}");
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done. Cached: {$cached}, Failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
