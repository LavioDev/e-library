<?php

namespace App\Http\Requests\Web\Assignment;

use App\Models\Assignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssignmentQuestionRequest extends FormRequest
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
        /** @var Assignment $assignment */
        $assignment = $this->route('assignment');

        return [
            'type' => ['required', 'string', Rule::in(['multiple_choice', 'text_input', 'file_input'])],
            'prompt' => ['required', 'string'],
            'position' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('assignment_questions', 'position')
                    ->where('assignment_id', $assignment->id),
            ],
            'max_score' => ['required', 'numeric', 'min:0'],
            'options_raw' => ['nullable', 'string', 'required_if:type,multiple_choice'],
            'correct_answer' => ['nullable', 'string', 'required_if:type,multiple_choice'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Loại câu hỏi là bắt buộc.',
            'type.in' => 'Loại câu hỏi không hợp lệ.',
            'prompt.required' => 'Nội dung câu hỏi là bắt buộc.',
            'position.required' => 'Thứ tự câu hỏi là bắt buộc.',
            'position.unique' => 'Thứ tự câu hỏi đã tồn tại trong bài tập.',
            'max_score.required' => 'Điểm tối đa là bắt buộc.',
            'max_score.min' => 'Điểm tối đa phải lớn hơn hoặc bằng 0.',
            'options_raw.required_if' => 'Vui lòng nhập danh sách đáp án cho câu trắc nghiệm.',
            'correct_answer.required_if' => 'Vui lòng nhập đáp án đúng cho câu trắc nghiệm.',
        ];
    }
}

