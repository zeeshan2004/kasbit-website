<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Services\Chatbot\Data\AiResponse;
use Throwable;

class OpenRouterService extends AbstractAiService
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
            $payload = [
                'model' => $provider->model,
                'messages' => [
                    ['role' => 'system', 'content' => $instructions],
                    ...$this->messages($question, $history, $websiteContext),
                ],
                'max_tokens' => (int) $provider->max_tokens,
            ];

            if ($provider->temperature !== null) {
                $payload['temperature'] = (float) $provider->temperature;
            }

            $endpoint = $provider->endpoint
                ?: config('chatbot.providers.openrouter.endpoint');

            $headers = array_filter([
                'HTTP-Referer' => config('chatbot.providers.openrouter.referer'),
                'X-OpenRouter-Title' => config('chatbot.providers.openrouter.title'),
            ], fn ($value) => is_string($value) && trim($value) !== '');

            $response = $this->client()
                ->withToken($key)
                ->withHeaders($headers)
                ->post($endpoint, $payload);

            if (! $response->successful()) {
                return AiResponse::failure(
                    data_get(
                        $response->json(),
                        'error.message',
                        'OpenRouter returned an error.'
                    ),
                    $response->status(),
                );
            }

            $answer = data_get($response->json(), 'choices.0.message.content');

            if (is_array($answer)) {
                $answer = collect($answer)
                    ->map(fn ($part) => is_array($part) ? ($part['text'] ?? null) : null)
                    ->filter(fn ($text) => is_string($text) && trim($text) !== '')
                    ->implode("\n");
            }

            $answer = is_string($answer) ? $this->cleanAnswer($answer) : null;

            if (! $answer) {
                return AiResponse::failure(
                    'OpenRouter returned an empty answer.',
                    $response->status(),
                );
            }

            return AiResponse::success($answer, [
                'response_id' => $response->json('id'),
                'model' => $response->json('model'),
            ]);
        } catch (Throwable $exception) {
            return $this->failed($provider, $exception);
        }
    }
}
