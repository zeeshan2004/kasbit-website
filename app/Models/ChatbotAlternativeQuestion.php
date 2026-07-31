<?php

namespace App\Models;

use App\Support\ChatbotText;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatbotAlternativeQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = ['knowledge_base_id', 'question'];

    protected static function booted(): void
    {
        static::saving(function (self $alternative): void {
            $alternative->normalized_question = ChatbotText::normalize($alternative->question);
            $alternative->question_hash = ChatbotText::hash($alternative->question);
        });
    }

    public function knowledge()
    {
        return $this->belongsTo(ChatbotKnowledgeBase::class, 'knowledge_base_id');
    }
}
