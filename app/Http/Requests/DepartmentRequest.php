<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        $department = $this->route('department');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')->ignore($department?->id),
            ],
            'sort_order' => ['required', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
