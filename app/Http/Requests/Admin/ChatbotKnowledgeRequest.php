<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChatbotKnowledgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'integer', 'exists:chatbot_categories,id'],
            'question' => ['required', 'string', 'min:3', 'max:2000'],
            'answer' => ['required', 'string', 'min:2', 'max:30000'],
            'alternatives' => ['nullable', 'string', 'max:10000'],
            'related_questions' => ['nullable', 'string', 'max:10000'],
            'keywords' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['draft', 'approved', 'disabled'])],
            'priority' => ['required', 'integer', 'between:0,1000'],
        ];
    }
}
