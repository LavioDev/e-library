<?php

namespace App\Http\Requests\Api\AssignmentSubmission;

use Illuminate\Foundation\Http\FormRequest;

class GradeAssignmentSubmissionRequest extends FormRequest
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
            'answers.*.score' => ['nullable', 'numeric', 'min:0'],
            'answers.*.comment' => ['nullable', 'string'],
            'overall_comment' => ['nullable', 'string'],
        ];
    }
}

