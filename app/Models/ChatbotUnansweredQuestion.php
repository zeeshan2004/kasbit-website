<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatbotUnansweredQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'guest_session_id', 'student_name', 'student_id', 'department_id',
        'user_question', 'normalized_question',
        'question_hash', 'ai_provider_id', 'ai_response', 'status', 'asked_count',
        'first_asked_at', 'last_asked_at', 'admin_answer', 'notes', 'answered_by',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'asked_count' => 'integer',
            'first_asked_at' => 'datetime',
            'last_asked_at' => 'datetime',
            'answered_at' => 'datetime',
        ];
    }

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answeredBy()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
