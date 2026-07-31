<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Services\Chatbot\Data\AiResponse;
use Throwable;

class GeminiService extends AbstractAiService
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
            $baseUrl = rtrim($provider->endpoint ?: config('chatbot.providers.gemini.endpoint'), '/');
            $messages = $this->messages($question, $history, $websiteContext);
            $contents = collect($messages)->map(fn (array $message) => [
                'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $message['content']]],
            ])->all();

            $response = $this->client()
                ->withHeaders(['x-goog-api-key' => $key])
                ->post("{$baseUrl}/{$provider->model}:generateContent", [
                    'system_instruction' => ['parts' => [['text' => $instructions]]],
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => $provider->temperature,
                        'maxOutputTokens' => $provider->max_tokens,
                    ],
                ]);

            if (! $response->successful()) {
                return AiResponse::failure(
                    data_get($response->json(), 'error.message', 'Gemini returned an error.'),
                    $response->status(),
                );
            }

            $answer = collect(data_get($response->json(), 'candidates.0.content.parts', []))
                ->pluck('text')
                ->implode("\n");

            return ($answer = $this->cleanAnswer($answer))
                ? AiResponse::success($answer)
                : AiResponse::failure('Gemini returned an empty answer.', $response->status());
        } catch (Throwable $exception) {
            return $this->failed($provider, $exception);
        }
    }
}
