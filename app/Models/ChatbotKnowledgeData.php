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
        // Expand common abbreviations
        $expandedQuestion = self::expandAbbreviations($question);
        $searchTerms = array_unique(array_merge(
            array_filter(explode(' ', $question), fn($w) => mb_strlen($w) >= 2),
            array_filter(explode(' ', $expandedQuestion), fn($w) => mb_strlen($w) >= 2),
        ));

        $query = static::active();
        if ($intent) {
            $query->where('intent', $intent);
        }

        // Try FULLTEXT search first
        $results = $query->whereRaw(
            'MATCH(title, content, keywords) AGAINST(? IN NATURAL LANGUAGE MODE)',
            [$expandedQuestion]
        )->limit($limit)->get();

        if ($results->isNotEmpty()) return $results;

        // Fallback: OR search with all terms
        $query = static::active();
        if ($intent) {
            $query->where('intent', $intent);
        }

        $query->where(function ($q) use ($searchTerms) {
            foreach (array_slice($searchTerms, 0, 8) as $word) {
                $q->orWhere('title', 'like', "%{$word}%")
                  ->orWhere('content', 'like', "%{$word}%")
                  ->orWhere('keywords', 'like', "%{$word}%");
            }
        });

        return $query->limit($limit)->get();
    }

    /**
     * Expand common program abbreviations to full names for better search.
     */
    private static function expandAbbreviations(string $text): string
    {
        $map = [
            'bscs' => 'BS Computer Science',
            'bba' => 'BBA Bachelor Business Administration',
            'mba' => 'MBA Master Business Administration',
            'mcs' => 'MCS Master Computer Science',
            'bs cs' => 'BS Computer Science',
            'bs af' => 'BS Accounting Finance',
            'phd' => 'Ph.D Doctorate',
            'ms' => 'MS Master',
            'adp' => 'Associate Degree Program',
        ];

        $lower = strtolower($text);
        $expanded = $text;

        foreach ($map as $abbr => $full) {
            if (str_contains($lower, $abbr)) {
                $expanded .= ' ' . $full;
            }
        }

        return $expanded;
    }
}
