<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotKnowledgeData extends Model
{
    protected $table = 'chatbot_knowledge_data';

    protected $fillable = [
        'intent', 'title', 'content', 'keywords', 'is_active', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const INTENTS = [
        'fee' => 'Fee Structure',
        'faculty' => 'Faculty / Staff',
        'program' => 'Programs / Courses',
        'admission' => 'Admissions',
        'campus' => 'Campus / Facilities',
        'scholarship' => 'Scholarships',
        'exam' => 'Exams / Results',
        'general' => 'General Info',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Search knowledge data by intent and keywords using FULLTEXT.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function search(string $question, ?string $intent = null, int $limit = 3)
    {
        $query = static::active();

        if ($intent) {
            $query->where('intent', $intent);
        }

        // Try FULLTEXT search first
        $results = $query->whereRaw(
            'MATCH(title, content, keywords) AGAINST(? IN NATURAL LANGUAGE MODE)',
            [$question]
        )->limit($limit)->get();

        // Fallback to LIKE search if FULLTEXT returns nothing
        if ($results->isEmpty()) {
            $words = array_filter(explode(' ', $question), fn($w) => mb_strlen($w) >= 3);
            $query = static::active();

            if ($intent) {
                $query->where('intent', $intent);
            }

            foreach (array_slice($words, 0, 5) as $word) {
                $query->where(function ($q) use ($word) {
                    $q->where('title', 'like', "%{$word}%")
                      ->orWhere('content', 'like', "%{$word}%")
                      ->orWhere('keywords', 'like', "%{$word}%");
                });
            }

            $results = $query->limit($limit)->get();

            // If AND search fails, try OR search
            if ($results->isEmpty() && count($words) > 1) {
                $query = static::active();
                if ($intent) {
                    $query->where('intent', $intent);
                }
                $query->where(function ($q) use ($words) {
                    foreach (array_slice($words, 0, 5) as $word) {
                        $q->orWhere('title', 'like', "%{$word}%")
                          ->orWhere('content', 'like', "%{$word}%")
                          ->orWhere('keywords', 'like', "%{$word}%");
                    }
                });
                $results = $query->limit($limit)->get();
            }
        }

        return $results;
    }
}
