<?php

namespace Database\Factories;

use App\Models\AssignmentAnswerFile;
use App\Models\AssignmentSubmissionAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssignmentAnswerFile>
 */
class AssignmentAnswerFileFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'submission_answer_id' => AssignmentSubmissionAnswer::factory(),
            'file_path' => 'assignment-files/'.fake()->uuid().'.pdf',
            'original_name' => fake()->word().'.pdf',
            'mime_type' => 'application/pdf',
            'size' => fake()->numberBetween(1000, 500000),
        ];
    }
}

