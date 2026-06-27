<?php

namespace App\Http\Requests\Api\Text;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTextRequest extends FormRequest
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
            'text_topic_id' => ['required', 'integer', 'exists:text_topics,id'],
            'topic' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'difficulty' => ['required', 'string', Rule::in(['easy', 'medium', 'hard'])],
            'read_link' => ['nullable', 'url', 'max:255'],
            'document' => ['nullable', 'array'],
            'document.title' => ['required_with:document', 'string', 'max:255'],
            'document.content' => ['required_with:document', 'string'],
        ];
    }
}
