<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreFeedbackQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $student = Auth::guard('student')->user();

        return $student?->isStudent() && $student->is_active;
    }

    public function rules(): array
    {
        return [
            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where('is_active', true),
            ],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.exists' => 'Please select an active department.',
        ];
    }
}
