<?php

namespace App\Services\Assignment;

use App\Models\Assignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssignmentService
{
    /**
     * @param  array{
     *   reading_class_id: int,
     *   title: string,
     *   description?: string|null,
     *   open_at?: string|null,
     *   due_at?: string|null,
     *   is_published?: bool,
     *   questions: array<int, array{
     *     type: string,
     *     prompt: string,
     *     max_score: int|float|string,
     *     position?: int|null,
     *     options_json?: array<int, string>|null,
     *     correct_answer?: string|null
     *   }>
     * } $payload
     */
    public function create(array $payload): Assignment
    {
        $this->validateSchedule($payload['open_at'] ?? null, $payload['due_at'] ?? null);
        $normalizedQuestions = $this->normalizeAndValidateQuestions($payload['questions']);

        return DB::transaction(function () use ($payload, $normalizedQuestions): Assignment {
            $assignment = Assignment::query()->create([
                'reading_class_id' => $payload['reading_class_id'],
                'title' => $payload['title'],
                'description' => $payload['description'] ?? null,
                'open_at' => $payload['open_at'] ?? null,
                'due_at' => $payload['due_at'] ?? null,
                'is_published' => (bool) ($payload['is_published'] ?? false),
            ]);

            $assignment->questions()->createMany($normalizedQuestions);

            return $assignment->fresh(['readingClass', 'questions']);
        });
    }

    /**
     * @param  array{
     *   reading_class_id: int,
     *   title: string,
     *   description?: string|null,
     *   open_at?: string|null,
     *   due_at?: string|null,
     *   is_published?: bool,
     *   questions: array<int, array{
     *     type: string,
     *     prompt: string,
     *     max_score: int|float|string,
     *     position?: int|null,
     *     options_json?: array<int, string>|null,
     *     correct_answer?: string|null
     *   }>
     * } $payload
     */
    public function update(Assignment $assignment, array $payload): Assignment
    {
        $this->validateSchedule($payload['open_at'] ?? null, $payload['due_at'] ?? null);
        $normalizedQuestions = $this->normalizeAndValidateQuestions($payload['questions']);

        return DB::transaction(function () use ($assignment, $payload, $normalizedQuestions): Assignment {
            $assignment->update([
                'reading_class_id' => $payload['reading_class_id'],
                'title' => $payload['title'],
                'description' => $payload['description'] ?? null,
                'open_at' => $payload['open_at'] ?? null,
                'due_at' => $payload['due_at'] ?? null,
                'is_published' => (bool) ($payload['is_published'] ?? false),
            ]);

            $assignment->questions()->delete();
            $assignment->questions()->createMany($normalizedQuestions);

            return $assignment->fresh(['readingClass', 'questions']);
        });
    }

    public function delete(Assignment $assignment): void
    {
        $assignment->delete();
    }

    private function validateSchedule(?string $openAt, ?string $dueAt): void
    {
        if ($openAt === null || $dueAt === null) {
            return;
        }

        if (strtotime($openAt) === false || strtotime($dueAt) === false) {
            throw ValidationException::withMessages([
                'schedule' => 'Invalid assignment schedule.',
            ]);
        }

        if (strtotime($openAt) > strtotime($dueAt)) {
            throw ValidationException::withMessages([
                'due_at' => 'The due date must be after or equal to open date.',
            ]);
        }
    }

    /**
     * @param  array<int, array{
     *   type: string,
     *   prompt: string,
     *   max_score: int|float|string,
     *   position?: int|null,
     *   options_json?: array<int, string>|null,
     *   correct_answer?: string|null
     * }> $questions
     * @return array<int, array{
     *   type: string,
     *   prompt: string,
     *   max_score: int|float|string,
     *   position: int,
     *   options_json: array<int, string>|null,
     *   correct_answer: string|null
     * }>
     */
    private function normalizeAndValidateQuestions(array $questions): array
    {
        if ($questions === []) {
            throw ValidationException::withMessages([
                'questions' => 'At least one question is required.',
            ]);
        }

        $normalized = [];
        $usedPositions = [];

        foreach (array_values($questions) as $index => $question) {
            $position = (int) ($question['position'] ?? ($index + 1));
            if ($position <= 0) {
                throw ValidationException::withMessages([
                    "questions.$index.position" => 'Question position must be greater than 0.',
                ]);
            }
            if (in_array($position, $usedPositions, true)) {
                throw ValidationException::withMessages([
                    'questions' => 'Question positions must be unique.',
                ]);
            }
            $usedPositions[] = $position;

            $type = (string) $question['type'];
            $prompt = trim((string) $question['prompt']);
            if ($prompt === '') {
                throw ValidationException::withMessages([
                    "questions.$index.prompt" => 'Question prompt is required.',
                ]);
            }

            $maxScore = (float) $question['max_score'];
            if ($maxScore < 0) {
                throw ValidationException::withMessages([
                    "questions.$index.max_score" => 'max_score must be greater than or equal to 0.',
                ]);
            }

            $options = $question['options_json'] ?? null;
            $correctAnswer = $question['correct_answer'] ?? null;

            if ($type === 'multiple_choice') {
                if (!is_array($options) || count($options) < 2) {
                    throw ValidationException::withMessages([
                        "questions.$index.options_json" => 'Multiple choice question needs at least 2 options.',
                    ]);
                }

                $options = array_values(array_map(static fn ($option) => (string) $option, $options));
                foreach ($options as $option) {
                    if (trim($option) === '') {
                        throw ValidationException::withMessages([
                            "questions.$index.options_json" => 'Multiple choice options cannot be empty.',
                        ]);
                    }
                }

                if ($correctAnswer === null || !in_array($correctAnswer, $options, true)) {
                    throw ValidationException::withMessages([
                        "questions.$index.correct_answer" => 'correct_answer must match one of options_json values.',
                    ]);
                }
            } elseif ($type === 'text_input' || $type === 'file_input') {
                $options = null;
                $correctAnswer = null;
            } else {
                throw ValidationException::withMessages([
                    "questions.$index.type" => 'Unsupported question type.',
                ]);
            }

            $normalized[] = [
                'type' => $type,
                'prompt' => $prompt,
                'max_score' => $maxScore,
                'position' => $position,
                'options_json' => $options,
                'correct_answer' => $correctAnswer,
            ];
        }

        usort($normalized, static fn (array $a, array $b): int => $a['position'] <=> $b['position']);

        return $normalized;
    }
}

