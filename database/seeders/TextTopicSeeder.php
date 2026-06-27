<?php

namespace Database\Seeders;

use App\Models\TextTopic;
use Illuminate\Database\Seeder;

class TextTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TextTopic::factory()->count(5)->create();
    }
}
