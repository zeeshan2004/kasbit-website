<?php

namespace App\Services\Chatbot;

use App\Support\ChatbotText;

class IntentDetector
{
    private const INTENT_KEYWORDS = [
        'fee' => [
            'fee', 'fees', 'cost', 'tuition', 'payment', 'installment', 'scholarship',
            'kitni', 'kitna', 'paisa', 'rupees', 'rs', 'amount', 'charge', 'charges',
            'fee structure', 'semester fee', 'admission fee',
        ],
        'faculty' => [
            'teacher', 'sir', 'madam', 'professor', 'lecturer', 'faculty', 'staff',
            'hod', 'dean', 'instructor', 'teaches', 'teach', 'parhata', 'parhati',
            'kon hai', 'kaun hai', 'who is', 'department head',
        ],
        'program' => [
            'program', 'course', 'degree', 'bba', 'mba', 'bscs', 'mcs', 'bs',
            'ms', 'associate', 'bachelor', 'master', 'semester', 'syllabus',
            'subject', 'curriculum', 'credit', 'duration',
        ],
        'admission' => [
            'admission', 'apply', 'application', 'enroll', 'enrollment', 'register',
            'registration', 'eligibility', 'criteria', 'requirement', 'deadline',
            'dakhla', 'form', 'intake', 'merit', 'entry test',
        ],
        'campus' => [
            'campus', 'location', 'address', 'building', 'library', 'lab',
            'canteen', 'parking', 'transport', 'facility', 'facilities',
            'kahan', 'where', 'timing', 'time',
        ],
        'scholarship' => [
            'scholarship', 'financial aid', 'discount', 'waiver', 'merit based',
            'need based', 'concession',
        ],
        'exam' => [
            'exam', 'result', 'gpa', 'cgpa', 'grade', 'marks', 'paper',
            'midterm', 'final', 'datesheet', 'schedule',
        ],
    ];

    /**
     * Detect the intent of a user question.
     */
    public function detect(string $question): ?string
    {
        $normalized = ChatbotText::normalize($question);
        $scores = [];

        foreach (self::INTENT_KEYWORDS as $intent => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (str_contains($normalized, $keyword)) {
                    $score += strlen($keyword); // longer keyword = more specific = higher score
                }
            }
            if ($score > 0) {
                $scores[$intent] = $score;
            }
        }

        if (empty($scores)) {
            return null; // general/unknown intent
        }

        arsort($scores);
        return array_key_first($scores);
    }
}
