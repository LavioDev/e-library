<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssignmentQuestion>
 */
class AssignmentQuestionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assignment_id' => Assignment::factory(),
            'type' => 'multiple_choice',
            'prompt' => fake()->sentence(8),
            'options_json' => ['A', 'B', 'C', 'D'],
            'correct_answer' => 'A',
            'max_score' => 1,
            'position' => 1,
        ];
    }
}

