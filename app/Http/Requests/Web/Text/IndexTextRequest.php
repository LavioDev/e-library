<?php

namespace App\Http\Requests\Web\Text;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexTextRequest extends FormRequest
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
            'text_topic_id' => ['nullable', 'integer', 'exists:text_topics,id'],
            'difficulty' => ['nullable', 'string', Rule::in(['easy', 'medium', 'hard'])],
        ];
    }

    /**
     * @return array{keyword: string, text_topic_id: string, difficulty: string}
     */
    public function filters(): array
    {
        return [
            'keyword' => (string) ($this->validated()['keyword'] ?? ''),
            'text_topic_id' => (string) ($this->validated()['text_topic_id'] ?? ''),
            'difficulty' => (string) ($this->validated()['difficulty'] ?? ''),
        ];
    }
}
