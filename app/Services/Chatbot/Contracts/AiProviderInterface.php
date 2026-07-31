<?php

namespace App\Services\Chatbot\Contracts;

use App\Models\AiProvider;
use App\Services\Chatbot\Data\AiResponse;

interface AiProviderInterface
{
    /**
     * @param  array<int, array{role: string, content: string}>  $history
     */
    public function generate(
        AiProvider $provider,
        string $question,
        string $instructions,
        array $history = [],
        ?string $websiteContext = null,
    ): AiResponse;
}
