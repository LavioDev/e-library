<?php

namespace App\Http\Requests\Web\ReadingClass;

use Illuminate\Foundation\Http\FormRequest;

class IndexReadingClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'text_id' => ['nullable', 'integer', 'exists:texts,id'],
        ];
    }

    /**
     * @return array{keyword: string, text_id: string}
     */
    public function filters(): array
    {
        return [
            'keyword' => (string) ($this->validated()['keyword'] ?? ''),
            'text_id' => (string) ($this->validated()['text_id'] ?? ''),
        ];
    }
}

