<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\ReadingClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reading_class_id' => ReadingClass::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'open_at' => now()->subHour(),
            'due_at' => now()->addDay(),
            'is_published' => true,
        ];
    }
}

