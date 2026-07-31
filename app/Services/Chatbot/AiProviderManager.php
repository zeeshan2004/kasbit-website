<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Services\Chatbot\Contracts\AiProviderInterface;
use App\Services\Chatbot\Data\AiResponse;

class AiProviderManager
{
    public function activeProvider(): ?AiProvider
    {
        return AiProvider::query()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->first();
    }

    public function service(AiProvider $provider): AiProviderInterface
    {
        return match ($provider->type) {
            'openai' => app(OpenAiService::class),
            'openrouter' => app(OpenRouterService::class),
            'claude' => app(ClaudeService::class),
            'gemini' => app(GeminiService::class),
            default => app(CustomAiService::class),
        };
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $history
     */
    public function generate(
        AiProvider $provider,
        string $question,
        string $instructions,
        array $history = [],
        ?string $websiteContext = null,
    ): AiResponse {
        return $this->service($provider)->generate(
            $provider,
            $question,
            $instructions,
            $history,
            $websiteContext,
        );
    }
}
