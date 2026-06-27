<?php

namespace Database\Factories;

use App\Models\Text;
use App\Models\TextTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Text>
 */
class TextFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text_topic_id' => TextTopic::factory(),
            'topic' => fake()->optional()->sentence(3),
            'name' => fake()->sentence(4),
            'author' => fake()->name(),
            'difficulty' => fake()->randomElement(['easy', 'medium', 'hard']),
            'read_link' => fake()->optional()->url(),
        ];
    }
}
