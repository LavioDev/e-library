<?php

namespace App\Http\Requests\Web\ReadingClass;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReadingClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('text_id') && !$this->has('text_ids')) {
            $this->merge([
                'text_ids' => [$this->input('text_id')],
            ]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('reading_classes', 'name')],
            'text_ids' => ['required', 'array'],
            'text_ids.*' => ['integer', 'distinct', 'exists:texts,id'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'distinct', 'exists:users,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên lớp học là bắt buộc.',
            'name.unique' => 'Tên lớp học đã tồn tại.',
            'text_ids.required' => 'Văn bản là bắt buộc.',
            'text_ids.array' => 'Danh sách văn bản không hợp lệ.',
            'text_ids.*.exists' => 'Có văn bản không tồn tại.',
            'user_ids.array' => 'Danh sách người dùng không hợp lệ.',
            'user_ids.*.exists' => 'Có người dùng không tồn tại.',
        ];
    }
}
