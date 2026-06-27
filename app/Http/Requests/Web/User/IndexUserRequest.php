<?php

namespace App\Http\Requests\Web\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexUserRequest extends FormRequest
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
            'role' => ['nullable', 'string', Rule::in(['teacher', 'user'])],
        ];
    }

    /**
     * @return array{keyword: string, role: string}
     */
    public function filters(): array
    {
        return [
            'keyword' => (string) ($this->validated()['keyword'] ?? ''),
            'role' => (string) ($this->validated()['role'] ?? ''),
        ];
    }
}
