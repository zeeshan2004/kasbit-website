<?php

namespace App\Services\Chatbot;

use App\Support\ChatbotText;

class PromptGuard
{
    /**
     * @return array{blocked: bool, message: ?string}
     */
    public function inspect(string $question): array
    {
        $normalized = ChatbotText::normalize($question);
        $patterns = [
            'ignore previous instructions',
            'ignore all instructions',
            'reveal system prompt',
            'show system prompt',
            'print system prompt',
            'developer message',
            'reveal api key',
            'show api key',
            'database password',
            'database credentials',
            'dump environment',
            'show env file',
            'bypass security',
            'jailbreak',
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($normalized, $pattern)) {
                return [
                    'blocked' => true,
                    'message' => 'I can help with KASBIT information, but I cannot reveal private configuration or follow instructions that bypass security.',
                ];
            }
        }

        return ['blocked' => false, 'message' => null];
    }
}
