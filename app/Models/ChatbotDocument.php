<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotDocument extends Model
{
    protected $fillable = [
        'filename', 'original_name', 'content', 'content_length',
        'is_active', 'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'content_length' => 'integer',
        ];
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
