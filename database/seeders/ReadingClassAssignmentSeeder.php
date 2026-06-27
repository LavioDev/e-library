<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use App\Models\ReadingClass;
use App\Models\Text;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReadingClassAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReadingClass::query()->delete();

        $users = User::query()->where('role', 'user')->get(['id']);
        $texts = Text::query()->get(['id', 'name']);

        foreach ($texts as $text) {
            $classCount = random_int(1, 2);

            for ($classIndex = 1; $classIndex <= $classCount; $classIndex++) {
                $readingClass = ReadingClass::query()->create([
                    'name' => 'Lớp học - '.$text->name.' - '.$classIndex.' - '.$text->id,
                    'text_id' => $text->id,
                ]);

                if ($users->isNotEmpty()) {
                    $readingClass->users()->attach(
                        $users->random(min($users->count(), random_int(1, min(3, $users->count()))))->pluck('id')->all(),
                    );
                }

                $assignment = Assignment::query()->create([
                    'reading_class_id' => $readingClass->id,
                    'title' => 'Bài tập - '.$readingClass->name,
                    'description' => 'Bài tập tự động cho lớp học '.$readingClass->name,
                    'open_at' => now()->subDay(),
                    'due_at' => now()->addDays(7),
                    'is_published' => true,
                ]);

                $questionCount = random_int(1, 5);
                $typePool = ['multiple_choice', 'text_input', 'file_input'];

                for ($position = 1; $position <= $questionCount; $position++) {
                    $type = $position <= count($typePool)
                        ? $typePool[$position - 1]
                        : $typePool[array_rand($typePool)];

                    AssignmentQuestion::query()->create([
                        'assignment_id' => $assignment->id,
                        'type' => $type,
                        'prompt' => 'Câu '.$position.': '.fake()->sentence(8),
                        'options_json' => $type === 'multiple_choice'
                            ? ['A', 'B', 'C', 'D']
                            : null,
                        'correct_answer' => $type === 'multiple_choice'
                            ? fake()->randomElement(['A', 'B', 'C', 'D'])
                            : ($type === 'text_input' ? fake()->sentence(4) : null),
                        'max_score' => fake()->randomElement([1, 2, 3, 5]),
                        'position' => $position,
                    ]);
                }
            }
        }
    }
}
