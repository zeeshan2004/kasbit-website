<?php

return [
    'http_timeout' => (int) env('CHATBOT_AI_TIMEOUT', 15),
    'http_retries' => (int) env('CHATBOT_AI_RETRIES', 1),
    'similarity_threshold' => (float) env('CHATBOT_SIMILARITY_THRESHOLD', 72),
    'related_limit' => (int) env('CHATBOT_RELATED_LIMIT', 5),
    'history_limit' => (int) env('CHATBOT_HISTORY_LIMIT', 30),
    'source_timeout' => (int) env('CHATBOT_SOURCE_TIMEOUT', 5),
    'source_context_limit' => (int) env('CHATBOT_SOURCE_CONTEXT_LIMIT', 10000),
    'source_cache_minutes' => (int) env('CHATBOT_SOURCE_CACHE_MINUTES', 60),

    'api_keys' => [
        'OPENAI_API_KEY' => env('OPENAI_API_KEY'),
        'OPENROUTER_API_KEY' => env('OPENROUTER_API_KEY'),
        'ANTHROPIC_API_KEY' => env('ANTHROPIC_API_KEY'),
        'GEMINI_API_KEY' => env('GEMINI_API_KEY'),
        'CUSTOM_AI_API_KEY' => env('CUSTOM_AI_API_KEY'),
        'KNOWLEDGE_API_KEY' => env('KNOWLEDGE_API_KEY'),
    ],

    'providers' => [
        'openai' => [
            'endpoint' => env('OPENAI_API_ENDPOINT', 'https://api.openai.com/v1/responses'),
            'model' => env('OPENAI_MODEL', 'gpt-5.6-sol'),
        ],
        'openrouter' => [
            'endpoint' => env('OPENROUTER_API_ENDPOINT', 'https://openrouter.ai/api/v1/chat/completions'),
            'model' => env('OPENROUTER_MODEL', 'openai/gpt-4o'),
            'referer' => env('OPENROUTER_SITE_URL', env('APP_URL', 'http://127.0.0.1:8000')),
            'title' => env('OPENROUTER_SITE_NAME', 'KASBIT Assistant'),
        ],
        'claude' => [
            'endpoint' => env('ANTHROPIC_API_ENDPOINT', 'https://api.anthropic.com/v1/messages'),
            'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-5'),
            'version' => env('ANTHROPIC_API_VERSION', '2023-06-01'),
        ],
        'gemini' => [
            'endpoint' => env('GEMINI_API_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models'),
            'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
        ],
    ],
];
