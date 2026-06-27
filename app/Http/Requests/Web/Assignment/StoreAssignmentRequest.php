<?php

namespace App\Http\Requests\Web\Assignment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
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
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reading_class_id.required' => 'Lớp học là bắt buộc.',
            'reading_class_id.exists' => 'Lớp học không hợp lệ.',
            'title.required' => 'Tên bài tập là bắt buộc.',
            'due_at.after_or_equal' => 'Hạn nộp phải lớn hơn hoặc bằng thời gian mở.',
        ];
    }
}

