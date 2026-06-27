<?php

namespace Database\Seeders;

use App\Models\Text;
use App\Models\TextTopic;
use Illuminate\Database\Seeder;

class LibraryTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            'Nghị luận xã hội',
            'Nghị luận văn học',
            'Đọc hiểu văn bản',
            'Thơ Việt Nam',
            'Truyện ngắn hiện đại',
        ];

        foreach ($topics as $topicName) {
            $topic = TextTopic::query()->updateOrCreate(
                ['name' => $topicName],
                ['name' => $topicName],
            );

            Text::query()->where('text_topic_id', $topic->id)->delete();

            for ($index = 1; $index <= 10; $index++) {
                Text::query()->create([
                    'text_topic_id' => $topic->id,
                    'topic' => $topicName,
                    'name' => $topicName.' - Văn bản '.$index,
                    'author' => 'User '.fake()->name(),
                    'difficulty' => fake()->randomElement(['easy', 'medium', 'hard']),
                    'read_link' => fake()->optional(0.7)->url(),
                ]);
            }
        }
    }
}
