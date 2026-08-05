<?php

namespace App\Http\Requests;

use App\Models\HeaderMenu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StudentRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->name),
            'email' => strtolower(trim((string) $this->email)),
            'student_id' => strtoupper(trim((string) $this->student_id)),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'student_id' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9][A-Z0-9\-\/]*$/',
                Rule::unique('users', 'student_id'),
            ],
            'program_id' => [
                'required',
                Rule::in(HeaderMenu::registrationProgramIds()),
            ],
            'semester' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.regex' => 'Student ID may only contain letters, numbers, dashes and slashes.',
            'program_id.in' => 'Please select an active KASBIT program.',
        ];
    }
}
