<?php

namespace Database\Factories;

use App\Models\ReadingClass;
use App\Models\Text;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReadingClass>
 */
class ReadingClassFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Lop '.fake()->unique()->numberBetween(1, 9999),
            'text_id' => Text::factory(),
        ];
    }
}

