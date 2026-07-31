<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Services\Chatbot\Data\AiResponse;
use Throwable;

class OpenAiService extends AbstractAiService
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
            $input = $this->messages(
                $question,
                $history,
                $websiteContext
            );

            $payload = [
                'model' => $provider->model,
                'instructions' => $instructions,
                'input' => $input,
                'max_output_tokens' => (int) $provider->max_tokens,
            ];

            /*
             * Some OpenAI models do not support the temperature parameter.
             * Add temperature only for models that support it.
             */
            $modelsWithoutTemperature = [
                'gpt-5.6-sol',
            ];

            $model = strtolower(trim($provider->model));

            if (
                $provider->temperature !== null
                && ! in_array($model, $modelsWithoutTemperature, true)
            ) {
                $payload['temperature'] = (float) $provider->temperature;
            }

            $endpoint = $provider->endpoint
                ?: config('chatbot.providers.openai.endpoint');

            $response = $this->client()
                ->withToken($key)
                ->acceptJson()
                ->asJson()
                ->post($endpoint, $payload);

            if (! $response->successful()) {
                return AiResponse::failure(
                    data_get(
                        $response->json(),
                        'error.message',
                        'OpenAI returned an error.'
                    ),
                    $response->status(),
                );
            }

            /*
             * Responses API may return output_text directly.
             */
            $answer = $response->json('output_text');

            /*
             * Fallback: extract text from output content.
             */
            if (! is_string($answer) || trim($answer) === '') {
                $answer = collect($response->json('output', []))
                    ->flatMap(function (array $item) {
                        return $item['content'] ?? [];
                    })
                    ->map(function (array $content) {
                        return $content['text'] ?? null;
                    })
                    ->filter(function ($text) {
                        return is_string($text) && trim($text) !== '';
                    })
                    ->implode("\n");
            }

            $answer = $this->cleanAnswer($answer);

            if (! $answer) {
                return AiResponse::failure(
                    'OpenAI returned an empty answer.',
                    $response->status(),
                );
            }

            return AiResponse::success(
                $answer,
                [
                    'response_id' => $response->json('id'),
                ]
            );
        } catch (Throwable $exception) {
            return $this->failed($provider, $exception);
        }
    }
}
