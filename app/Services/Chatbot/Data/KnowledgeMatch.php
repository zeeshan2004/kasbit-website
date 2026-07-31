<?php

namespace App\Services\Chatbot\Data;

use App\Models\ChatbotKnowledgeBase;

final readonly class KnowledgeMatch
{
    public function __construct(
        public ChatbotKnowledgeBase $knowledge,
        public float $score,
    ) {
    }
}
