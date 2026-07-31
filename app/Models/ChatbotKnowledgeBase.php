<?php

namespace App\Models;

use App\Support\ChatbotText;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatbotKnowledgeBase extends Model
{
    use SoftDeletes;

    protected $table = 'chatbot_knowledge_base';

    protected $fillable = [
        'category_id', 'question', 'answer', 'keywords', 'status', 'priority',
        'answer_origin', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return ['priority' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saving(function (self $knowledge): void {
            $knowledge->normalized_question = ChatbotText::normalize($knowledge->question);
            $knowledge->question_hash = ChatbotText::hash($knowledge->question);
        });
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function category()
    {
        return $this->belongsTo(ChatbotCategory::class);
    }

    public function alternatives()
    {
        return $this->hasMany(ChatbotAlternativeQuestion::class, 'knowledge_base_id');
    }

    public function relatedQuestions()
    {
        return $this->hasMany(ChatbotRelatedQuestion::class, 'knowledge_base_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatbotMessage::class, 'knowledge_base_id');
    }
}
