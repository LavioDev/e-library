<?php

namespace App\Http\Requests\Web\TextTopic;

use Illuminate\Foundation\Http\FormRequest;

class IndexTextTopicRequest extends FormRequest
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
        ];
    }

    /**
     * @return array{keyword: string}
     */
    public function filters(): array
    {
        return [
            'keyword' => (string) ($this->validated()['keyword'] ?? ''),
        ];
    }
}
