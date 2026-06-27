<?php

namespace App\Http\Requests\Api\AssignmentSubmission;

use Illuminate\Foundation\Http\FormRequest;

class SaveAssignmentSubmissionAnswersRequest extends FormRequest
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
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'integer', 'exists:assignment_questions,id'],
            'answers.*.selected_answer' => ['nullable', 'string'],
            'answers.*.text_answer' => ['nullable', 'string'],
            'answers.*.files' => ['nullable', 'array'],
            'answers.*.files.*.file_path' => ['required_with:answers.*.files', 'string', 'max:2048'],
            'answers.*.files.*.original_name' => ['required_with:answers.*.files', 'string', 'max:255'],
            'answers.*.files.*.mime_type' => ['nullable', 'string', 'max:255'],
            'answers.*.files.*.size' => ['nullable', 'integer', 'min:0'],
        ];
    }
}

