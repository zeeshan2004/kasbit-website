<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendChatbotMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['message' => trim((string) $this->input('message'))]);
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'min:2', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Please type a question.',
            'message.max' => 'Your question is too long.',
        ];
    }
}
