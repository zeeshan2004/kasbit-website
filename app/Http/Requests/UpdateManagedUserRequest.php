<?php

namespace App\Http\Requests;

use App\Models\HeaderMenu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateManagedUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->name),
            'email' => strtolower(trim((string) $this->email)),
            'student_id' => $this->student_id
                ? strtoupper(trim((string) $this->student_id))
                : null,
        ]);
    }

    public function rules(): array
    {
        $managedUser = $this->route('managedUser');
        $studentRules = $managedUser?->isStudent()
            ? ['required', 'regex:/^[^@\s]+@kasbit\.edu\.pk$/i']
            : ['required'];

        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                ...$studentRules,
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($managedUser?->id),
            ],
            'student_id' => [
                Rule::requiredIf($managedUser?->isStudent()),
                'nullable',
                'string',
                'max:50',
                'regex:/^[A-Z0-9][A-Z0-9\-\/]*$/',
                Rule::unique('users', 'student_id')->ignore($managedUser?->id),
            ],
            'program_id' => [
                Rule::requiredIf($managedUser?->isStudent()),
                'nullable',
                Rule::in(HeaderMenu::registrationProgramIds(true)),
            ],
            'semester' => [
                Rule::requiredIf($managedUser?->isStudent()),
                'nullable',
                'string',
                'max:50',
            ],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.regex' => 'Student accounts must use an @kasbit.edu.pk email address.',
            'program_id.in' => 'Please select a KASBIT program.',
        ];
    }
}
