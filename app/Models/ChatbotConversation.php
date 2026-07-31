<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ChatbotConversation extends Model
{
    use SoftDeletes;

    protected $fillable = ['uuid', 'user_id', 'guest_session_id', 'status', 'last_message_at', 'metadata'];

    protected function casts(): array
    {
        return ['last_message_at' => 'datetime', 'metadata' => 'array'];
    }

    protected static function booted(): void
    {
        static::creating(function (self $conversation): void {
            $conversation->uuid ??= (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatbotMessage::class, 'conversation_id');
    }
}
