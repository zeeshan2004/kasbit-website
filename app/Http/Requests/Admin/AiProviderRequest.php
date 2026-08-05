<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AiProviderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_default' => $this->boolean('is_default'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['openai', 'openrouter', 'claude', 'gemini', 'custom'])],
            'endpoint' => ['nullable', 'url:http,https', 'max:500'],
            'model' => ['required', 'string', 'max:150', 'regex:/^[A-Za-z0-9._:\\/-]+$/'],
            'api_key_env' => ['required', 'string', 'max:100', 'regex:/^[A-Z][A-Z0-9_]*$/'],
            'system_prompt' => ['nullable', 'string', 'max:10000'],
            'knowledge_source_url' => ['nullable', 'url:http,https', 'max:2048'],
            'knowledge_source_urls' => ['nullable', 'array', 'max:10'],
            'knowledge_source_urls.*' => ['nullable', 'url:http,https', 'max:2048'],
            'knowledge_api_url' => ['nullable', 'url:http,https', 'max:2048'],
            'knowledge_api_key_env' => ['nullable', 'string', 'max:100', 'regex:/^[A-Z][A-Z0-9_]*$/'],
            'temperature' => ['nullable', 'numeric', 'between:0,2'],
            'max_tokens' => ['required', 'integer', 'between:64,32000'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
        ];
    }
}
