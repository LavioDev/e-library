<?php

namespace Database\Seeders;

use App\Models\ReadingClass;
use App\Models\User;
use Illuminate\Database\Seeder;

class RandomUserEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $readingClassIds = ReadingClass::query()->pluck('id');
        if ($readingClassIds->isEmpty()) {
            return;
        }

        $users = User::factory()
            ->count(50)
            ->create([
                'role' => 'user',
            ]);

        foreach ($users as $user) {
            $joinCount = random_int(1, min(2, $readingClassIds->count()));
            $classIds = $readingClassIds->random($joinCount)->all();
            $user->readingClasses()->syncWithoutDetaching($classIds);
        }
    }
}

