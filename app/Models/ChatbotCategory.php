<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatbotCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    public function knowledge()
    {
        return $this->hasMany(ChatbotKnowledgeBase::class, 'category_id');
    }

    public function suggestions()
    {
        return $this->hasMany(ChatbotSuggestedQuestion::class, 'category_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatbotMessage::class, 'category_id');
    }
}
