<?php

namespace App\Http\Requests\Web\Assignment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexAssignmentRequest extends FormRequest
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
            'keyword' => ['nullable', 'string', 'max:255'],
            'text_id' => ['nullable', 'integer', 'exists:texts,id'],
            'reading_class_id' => ['nullable', 'integer', 'exists:reading_classes,id'],
            'is_published' => ['nullable', 'string', Rule::in(['0', '1'])],
        ];
    }

    /**
     * @return array{keyword: string, text_id: string, reading_class_id: string, is_published: string}
     */
    public function filters(): array
    {
        return [
            'keyword' => (string) ($this->validated()['keyword'] ?? ''),
            'text_id' => (string) ($this->validated()['text_id'] ?? ''),
            'reading_class_id' => (string) ($this->validated()['reading_class_id'] ?? ''),
            'is_published' => (string) ($this->validated()['is_published'] ?? ''),
        ];
    }
}
