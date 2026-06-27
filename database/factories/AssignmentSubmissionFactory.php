<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssignmentSubmission>
 */
class AssignmentSubmissionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assignment_id' => Assignment::factory(),
            'student_id' => User::factory(),
            'attempt_no' => 1,
            'status' => 'draft',
            'submitted_at' => null,
            'graded_at' => null,
            'graded_by' => null,
            'total_score' => null,
            'overall_comment' => null,
        ];
    }
}

