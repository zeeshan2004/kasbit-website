<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Services\Chatbot\Data\AiResponse;
use Throwable;

class ClaudeService extends AbstractAiService
{
    public function generate(
        AiProvider $provider,
        string $question,
        string $instructions,
        array $history = [],
        ?string $websiteContext = null,
    ): AiResponse {
        if (! $key = $provider->apiKey()) {
            return $this->missingKey($provider);
        }

        try {
            $response = $this->client()
                ->withHeaders([
                    'x-api-key' => $key,
                    'anthropic-version' => config('chatbot.providers.claude.version', '2023-06-01'),
                ])
                ->post($provider->endpoint ?: config('chatbot.providers.claude.endpoint'), [
                    'model' => $provider->model,
                    'system' => $instructions,
                    'messages' => $this->messages($question, $history, $websiteContext),
                    'temperature' => $provider->temperature,
                    'max_tokens' => $provider->max_tokens,
                ]);

            if (! $response->successful()) {
                return AiResponse::failure(
                    data_get($response->json(), 'error.message', 'Claude returned an error.'),
                    $response->status(),
                );
            }

            $answer = collect($response->json('content', []))
                ->where('type', 'text')
                ->pluck('text')
                ->implode("\n");

            return ($answer = $this->cleanAnswer($answer))
                ? AiResponse::success($answer, ['response_id' => $response->json('id')])
                : AiResponse::failure('Claude returned an empty answer.', $response->status());
        } catch (Throwable $exception) {
            return $this->failed($provider, $exception);
        }
    }
}
