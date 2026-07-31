<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Services\Chatbot\Data\AiResponse;
use Throwable;

class CustomAiService extends AbstractAiService
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

        if (! $provider->endpoint) {
            return AiResponse::failure('The custom provider endpoint is missing.');
        }

        try {
            $response = $this->client()->withToken($key)->post($provider->endpoint, [
                'model' => $provider->model,
                'system' => $instructions,
                'messages' => $this->messages($question, $history, $websiteContext),
                'temperature' => $provider->temperature,
                'max_tokens' => $provider->max_tokens,
            ]);

            if (! $response->successful()) {
                return AiResponse::failure(
                    data_get($response->json(), 'error.message', 'The custom provider returned an error.'),
                    $response->status(),
                );
            }

            $answer = data_get($response->json(), 'answer')
                ?? data_get($response->json(), 'response')
                ?? data_get($response->json(), 'output_text')
                ?? data_get($response->json(), 'choices.0.message.content')
                ?? data_get($response->json(), 'data.answer');

            return is_string($answer) && ($answer = $this->cleanAnswer($answer))
                ? AiResponse::success($answer)
                : AiResponse::failure('The custom provider returned an empty answer.', $response->status());
        } catch (Throwable $exception) {
            return $this->failed($provider, $exception);
        }
    }
}
