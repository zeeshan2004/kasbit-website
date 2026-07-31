<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatbotMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'conversation_id', 'parent_message_id', 'role', 'content', 'answer_source',
        'ai_provider_id', 'knowledge_base_id', 'category_id', 'response_time_ms',
        'ip_address', 'user_agent', 'status', 'metadata',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'response_time_ms' => 'integer'];
    }

    public function conversation()
    {
        return $this->belongsTo(ChatbotConversation::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_message_id');
    }

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function knowledge()
    {
        return $this->belongsTo(ChatbotKnowledgeBase::class, 'knowledge_base_id');
    }

    public function category()
    {
        return $this->belongsTo(ChatbotCategory::class);
    }
}
