<?php

namespace App\Services\Chatbot\Data;

final readonly class AiResponse
{
    public function __construct(
        public bool $successful,
        public ?string $answer = null,
        public ?string $error = null,
        public ?int $statusCode = null,
        public array $metadata = [],
    ) {
    }

    public static function success(string $answer, array $metadata = []): self
    {
        return new self(true, $answer, metadata: $metadata);
    }

    public static function failure(string $error, ?int $statusCode = null): self
    {
        return new self(false, error: $error, statusCode: $statusCode);
    }
}
