<?php

namespace Database\Factories;

use App\Models\AssignmentQuestion;
use App\Models\AssignmentSubmission;
use App\Models\AssignmentSubmissionAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssignmentSubmissionAnswer>
 */
class AssignmentSubmissionAnswerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'submission_id' => AssignmentSubmission::factory(),
            'question_id' => AssignmentQuestion::factory(),
            'selected_answer' => null,
            'text_answer' => null,
            'score' => null,
            'comment' => null,
            'auto_graded' => false,
        ];
    }
}

