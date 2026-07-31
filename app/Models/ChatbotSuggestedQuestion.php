<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatbotSuggestedQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'question', 'answer', 'display_order', 'is_active', 'show_on_welcome',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_active' => 'boolean',
            'show_on_welcome' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ChatbotCategory::class);
    }
}
