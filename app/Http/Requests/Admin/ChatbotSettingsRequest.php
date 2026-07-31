<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChatbotSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    protected function prepareForValidation(): void
    {
        foreach ([
            'is_enabled', 'save_history', 'suggestions_enabled',
            'ai_fallback_enabled', 'guest_chat_enabled',
        ] as $field) {
            $this->merge([$field => $this->boolean($field)]);
        }
    }

    public function rules(): array
    {
        return [
            'chatbot_name' => ['required', 'string', 'max:100'],
            'welcome_message' => ['required', 'string', 'max:2000'],
            'placeholder_text' => ['required', 'string', 'max:150'],
            'chatbot_icon' => ['required', 'string', 'max:100'],
            'header_title' => ['required', 'string', 'max:100'],
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_enabled' => ['boolean'],
            'save_history' => ['boolean'],
            'suggestions_enabled' => ['boolean'],
            'ai_fallback_enabled' => ['boolean'],
            'guest_chat_enabled' => ['boolean'],
            'max_questions_per_minute' => ['required', 'integer', 'between:1,120'],
            'max_message_length' => ['required', 'integer', 'between:50,2000'],
            'default_error_message' => ['required', 'string', 'max:2000'],
            'no_answer_message' => ['required', 'string', 'max:2000'],
            'privacy_message' => ['nullable', 'string', 'max:2000'],
            'system_prompt' => ['required', 'string', 'min:20', 'max:20000'],
        ];
    }
}
