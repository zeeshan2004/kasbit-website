<?php

namespace App\Http\Requests;

use App\Models\Query;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeedbackQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Query::STATUSES)],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
