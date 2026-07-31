<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveChatbotProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'student_id' => trim((string) $this->input('student_id')),
            'full_name' => trim((string) $this->input('full_name')),
        ]);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z0-9\-\/]+$/'],
            'full_name' => ['required', 'string', 'min:2', 'max:120', "regex:/^[\pL\pM .'-]+$/u"],
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')->where(fn ($query) => $query->where('is_active', true)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Please enter your student ID.',
            'student_id.regex' => 'Please enter a valid student ID.',
            'full_name.required' => 'Please enter your full name.',
            'full_name.regex' => 'Please enter a valid full name.',
            'department_id.required' => 'Please select your department.',
            'department_id.exists' => 'Please select an active department.',
        ];
    }
}
