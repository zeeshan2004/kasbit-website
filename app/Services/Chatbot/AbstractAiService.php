<?php

namespace App\Services\Chatbot;

use App\Models\AiProvider;
use App\Services\Chatbot\Contracts\AiProviderInterface;
use App\Services\Chatbot\Data\AiResponse;
use App\Support\ChatbotText;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class AbstractAiService implements AiProviderInterface
{
    protected function client(): PendingRequest
    {
        return Http::acceptJson()
            ->asJson()
            ->timeout((int) config('chatbot.http_timeout', 25))
            ->retry((int) config('chatbot.http_retries', 2), 250, null, false);
    }

    protected function missingKey(AiProvider $provider): AiResponse
    {
        return AiResponse::failure("The {$provider->api_key_env} environment variable is not configured.");
    }

    protected function cleanAnswer(?string $answer): ?string
    {
        $clean = ChatbotText::plainText($answer);

        return $clean !== '' ? $clean : null;
    }

    protected function failed(AiProvider $provider, Throwable $exception): AiResponse
    {
        Log::warning('AI chatbot provider request failed.', [
            'provider_id' => $provider->id,
            'provider_type' => $provider->type,
            'exception' => $exception->getMessage(),
        ]);

        return AiResponse::failure('The AI provider could not be reached.');
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $history
     * @return array<int, array{role: string, content: string}>
     */
    protected function messages(string $question, array $history, ?string $websiteContext = null): array
    {
        $messages = collect($history)
            ->take(-8)
            ->map(fn (array $message) => [
                'role' => $message['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => ChatbotText::plainText($message['content'], 3000),
            ])
            ->filter(fn (array $message) => $message['content'] !== '')
            ->values()
            ->all();

        $content = $websiteContext
            ? "Approved website context:\n{$websiteContext}\n\nUser question:\n{$question}"
            : $question;

        $messages[] = ['role' => 'user', 'content' => $content];

        return $messages;
    }
}
