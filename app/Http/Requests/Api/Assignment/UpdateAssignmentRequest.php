<?php

namespace App\Http\Requests\Api\Assignment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'reading_class_id' => ['required', 'integer', 'exists:reading_classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'open_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:open_at'],
            'is_published' => ['nullable', 'boolean'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.type' => ['required', 'string', Rule::in(['multiple_choice', 'text_input', 'file_input'])],
            'questions.*.prompt' => ['required', 'string'],
            'questions.*.max_score' => ['required', 'numeric', 'min:0'],
            'questions.*.position' => ['nullable', 'integer', 'min:1'],
            'questions.*.options_json' => ['nullable', 'array'],
            'questions.*.correct_answer' => ['nullable', 'string'],
        ];
    }
}

