<?php

namespace App\Services\Chatbot;

use App\Support\ChatbotText;

class IntentDetector
{
    private const INTENT_KEYWORDS = [

        /*
        |--------------------------------------------------------------------------
        | Fee Intent
        |--------------------------------------------------------------------------
        */
        'fee' => [
            'fee','fees','tuition','tuition fee','semester fee','admission fee',
            'registration fee','exam fee','library fee','hostel fee',
            'transport fee','course fee','fee structure','fee details',
            'fee information','fee challan','fee voucher',
            'cost','charges','price','expense','amount','payment',
            'pay','paid','installment','installments','monthly fee',
            'yearly fee','per semester','per credit hour',
            'rupees','rs','pkr','pakistani rupees',

            // Roman Urdu
            'fee batao','fees batao','kitni fee','kitna fee',
            'kitna paisa','kitna kharcha','fee kitni hai',
            'fees kitni hai','fee kya hai','charges kitne hain',
            'kharcha kitna hai','semester fee batao',
            'bscs fee','bscs fees','bba fee','mba fee',
            'computer science fee','software engineering fee',
            'ai fee','admission charges'
        ],

        /*
        |--------------------------------------------------------------------------
        | Admission Intent
        |--------------------------------------------------------------------------
        */
        'admission' => [
            'admission','apply','application','application form',
            'admission form','apply online','online application',
            'registration','register','enrollment','enroll',
            'join','join university','new admission',
            'admission process','how to apply',
            'eligibility','criteria','requirements',
            'required documents','documents',
            'deadline','last date','admission open',
            'admission close','entry test','interview',
            'merit','merit list','intake','session',

            // Roman Urdu
            'admission kaise kare',
            'admission kaise hoga',
            'kaisay apply karna',
            'kaise apply karna',
            'apply kaise karun',
            'dakhla',
            'dakhla kaise hoga',
            'admission lena hai',
            'admission chahiye',
            'form kahan milega',
            'apply'
        ],

        /*
        |--------------------------------------------------------------------------
        | Programs
        |--------------------------------------------------------------------------
        */
        'program' => [
            'program','programs','course','courses',
            'degree','degrees',
            'bs','bscs','bs cs','computer science',
            'software engineering','se',
            'artificial intelligence','ai',
            'data science','cyber security',
            'information technology','it',
            'bba','mba','mcs','ms','phd',
            'associate degree',
            'business administration',
            'commerce','accounting',
            'curriculum','subjects','subject',
            'semester','credit hours',
            'duration','years','4 years','2 years',

            // Roman Urdu
            'program batao',
            'course batao',
            'kis course',
            'kaunsa program',
            'degree batao',
            'computer wala program',
            'bscs mai',
            'bba mai',
            'mba mai'
        ],

        /*
        |--------------------------------------------------------------------------
        | Faculty
        |--------------------------------------------------------------------------
        */
        'faculty' => [
            'teacher','teachers',
            'faculty','staff',
            'professor','lecturer',
            'sir','madam',
            'hod','head',
            'chairman','dean',
            'director',
            'vice chancellor',
            'vc',
            'principal',
            'coordinator',
            'department head',
            'who is',
            'teach',
            'teaches',

            // Roman Urdu
            'kon hai',
            'kaun hai',
            'kon',
            'kaun',
            'parhata',
            'parhati',
            'kis ne parhaya',
            'dean kon hai',
            'hod kon hai',
            'teacher kon hai',
            'sir kon hain'
        ],

        /*
        |--------------------------------------------------------------------------
        | Scholarship
        |--------------------------------------------------------------------------
        */
        'scholarship' => [
            'scholarship',
            'financial aid',
            'aid',
            'grant',
            'loan',
            'education loan',
            'discount',
            'waiver',
            'fee concession',
            'merit scholarship',
            'need based',
            '100 scholarship',
            '50 scholarship',

            // Roman Urdu
            'scholarship hai',
            'scholarship milti hai',
            'discount milta hai',
            'fee kam hogi',
            'concession'
        ],

        /*
        |--------------------------------------------------------------------------
        | Campus
        |--------------------------------------------------------------------------
        */
        'campus' => [
            'campus',
            'location',
            'address',
            'map',
            'where',
            'timing',
            'office timing',
            'class timing',
            'library',
            'lab',
            'canteen',
            'hostel',
            'transport',
            'bus',
            'parking',
            'wifi',
            'building',
            'facility',
            'facilities',

            // Roman Urdu
            'kahan hai',
            'kidhar hai',
            'address batao',
            'timing batao',
            'office timing',
            'campus kahan hai'
        ],

        /*
        |--------------------------------------------------------------------------
        | Exams
        |--------------------------------------------------------------------------
        */
        'exam' => [
            'exam',
            'midterm',
            'mid term',
            'final',
            'quiz',
            'paper',
            'marks',
            'grade',
            'result',
            'gpa',
            'cgpa',
            'transcript',
            'certificate',
            'schedule',
            'datesheet',

            // Roman Urdu
            'result kab ayega',
            'exam kab hai',
            'paper kab hai',
            'marks',
            'result batao'
        ],

        /*
        |--------------------------------------------------------------------------
        | Greetings
        |--------------------------------------------------------------------------
        */
        'greeting' => [
            'hi',
            'hello',
            'hey',
            'aoa',
            'assalamualaikum',
            'assalam o alaikum',
            'salam',
            'good morning',
            'good afternoon',
            'good evening',
            'good night',
            'thanks',
            'thank you',
            'ok',
            'okay',
            'bye',
            'allah hafiz'
        ]
    ];

    /**
     * Detect user intent.
     */
    public function detect(string $question): ?string
    {
        $normalized = ChatbotText::normalize($question);

        $scores = [];

        $priorityIntents = [
            'fee',
            'admission',
            'program',
            'faculty',
            'scholarship',
            'campus',
            'exam'
        ];

        foreach (self::INTENT_KEYWORDS as $intent => $keywords) {

            $score = 0;

            foreach ($keywords as $keyword) {

                if (str_contains($normalized, strtolower($keyword))) {

                    $weight = in_array($intent, $priorityIntents) ? 3 : 1;

                    $score += strlen($keyword) * $weight;
                }
            }

            if ($score > 0) {
                $scores[$intent] = $score;
            }
        }

        if (empty($scores)) {
            return null;
        }

        arsort($scores);

        return array_key_first($scores);
    }
}