<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Env;

class AiProvider extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'type', 'endpoint', 'model', 'api_key_env', 'system_prompt',
        'knowledge_source_url', 'knowledge_source_urls', 'knowledge_api_url', 'knowledge_api_key_env',
        'temperature', 'max_tokens', 'is_active', 'is_default',
        'last_tested_at', 'last_test_status', 'last_test_message',
    ];

    protected function casts(): array
    {
        return [
            'temperature' => 'float',
            'max_tokens' => 'integer',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'last_tested_at' => 'datetime',
            'knowledge_source_urls' => 'array',
        ];
    }

    public function apiKey(): ?string
    {
        return $this->environmentSecret($this->api_key_env);
    }

    public function knowledgeApiKey(): ?string
    {
        return $this->environmentSecret($this->knowledge_api_key_env);
    }

    /**
     * Get all knowledge source URLs (combines legacy single URL + multiple URLs array).
     *
     * @return array<int, string>
     */
    public function allKnowledgeSourceUrls(): array
    {
        $urls = [];

        if ($this->knowledge_source_url) {
            $urls[] = $this->knowledge_source_url;
        }

        if (is_array($this->knowledge_source_urls)) {
            foreach ($this->knowledge_source_urls as $url) {
                if (is_string($url) && trim($url) !== '') {
                    $urls[] = trim($url);
                }
            }
        }

        return array_values(array_unique($urls));
    }

    private function environmentSecret(?string $name): ?string
    {
        if (! $name || ! preg_match('/^[A-Z][A-Z0-9_]*$/', $name)) {
            return null;
        }

        $value = config("chatbot.api_keys.{$name}")
            ?: Env::get($name)
            ?: getenv($name);

        return is_string($value) && trim($value) !== '' ? trim($value) : null;
    }

    public function messages()
    {
        return $this->hasMany(ChatbotMessage::class);
    }

    public function unansweredQuestions()
    {
        return $this->hasMany(ChatbotUnansweredQuestion::class);
    }
}
