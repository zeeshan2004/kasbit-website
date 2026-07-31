<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatbotRelatedQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'knowledge_base_id', 'related_knowledge_base_id', 'question', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'is_active' => 'boolean'];
    }

    public function knowledge()
    {
        return $this->belongsTo(ChatbotKnowledgeBase::class, 'knowledge_base_id');
    }

    public function relatedKnowledge()
    {
        return $this->belongsTo(ChatbotKnowledgeBase::class, 'related_knowledge_base_id');
    }
}
