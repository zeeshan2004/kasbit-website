<?php

namespace App\Support;

use Illuminate\Support\Str;

class ChatbotText
{
    public static function normalize(?string $value): string
    {
        $value = Str::lower(strip_tags((string) $value));
        $value = preg_replace('/[^\pL\pN\s]/u', ' ', $value) ?? $value;

        return Str::of($value)->squish()->limit(500, '')->toString();
    }

    public static function hash(?string $value): string
    {
        return hash('sha256', self::normalize($value));
    }

    /**
     * @return array<int, string>
     */
    public static function tokens(?string $value): array
    {
        $stopWords = [
            'a', 'an', 'and', 'are', 'for', 'from', 'how', 'is', 'in', 'ka', 'ke',
            'ki', 'ko', 'kya', 'mein', 'of', 'on', 'or', 'the', 'to', 'what',
            'where', 'with', 'hai', 'hain', 'mujhe', 'please', 'batao', 'bataye',
            'btao', 'karen', 'karna', 'krna', 'mujy', 'mujhay', 'kese', 'kesay',
            'kaise', 'kahan', 'kidhar', 'kon', 'konsa', 'konsay', 'kaunsa',
            'acha', 'achaa', 'bata', 'thora', 'thori', 'wo', 'woh', 'ye', 'yeh',
            'zara',
        ];

        $aliases = [
            'admissions' => 'admission',
            'dakhla' => 'admission',
            'dakhle' => 'admission',
            'apply' => 'application',
            'darkhwast' => 'application',
            'fees' => 'fee',
            'charges' => 'fee',
            'programmes' => 'program',
            'programs' => 'program',
            'courses' => 'course',
            'degrees' => 'degree',
            'requirements' => 'requirement',
            'documents' => 'document',
            'akhri' => 'deadline',
            'lastdate' => 'deadline',
            'tareekh' => 'date',
            'kab' => 'date',
            'uni' => 'university',
        ];

        return collect(explode(' ', self::normalize($value)))
            ->map(fn (string $word) => $aliases[$word] ?? $word)
            ->filter(fn (string $word) => mb_strlen($word) > 1 && ! in_array($word, $stopWords, true))
            ->unique()
            ->values()
            ->all();
    }

    public static function plainText(?string $value, int $limit = 8000): string
    {
        $value = preg_replace('/<(br|\/p|\/div|\/li)>/i', "\n", (string) $value) ?? (string) $value;
        $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = str_replace(["\r\n", "\r"], "\n", $value);
        $value = preg_replace('/[^\S\n]+/u', ' ', $value) ?? $value;
        $value = preg_replace('/\n{3,}/u', "\n\n", $value) ?? $value;

        return Str::of($value)->trim()->limit($limit)->toString();
    }
}
