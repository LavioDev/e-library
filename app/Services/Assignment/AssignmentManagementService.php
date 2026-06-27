<?php

namespace App\Services\Assignment;

use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssignmentManagementService
{
    public function __construct(
        private AssignmentManagementQueryBuilder $assignmentQueryBuilder,
    ) {}

    /**
     * @param array{keyword: string, reading_class_id: string, is_published: string} $filters
     */
    public function paginateForManagement(array $filters): LengthAwarePaginator
    {
        return $this->assignmentQueryBuilder->paginateForManagement($filters);
    }

    /**
     * @param array{
     *  reading_class_id: int,
     *  title: string,
     *  description?: string|null,
     *  open_at?: string|null,
     *  due_at?: string|null,
     *  is_published?: bool
     * } $payload
     */
    public function create(array $payload): Assignment
    {
        $this->validateSchedule($payload['open_at'] ?? null, $payload['due_at'] ?? null);

        return Assignment::query()->create([
            'reading_class_id' => $payload['reading_class_id'],
            'title' => $payload['title'],
            'description' => $payload['description'] ?? null,
            'open_at' => $payload['open_at'] ?? null,
            'due_at' => $payload['due_at'] ?? null,
            'is_published' => (bool) ($payload['is_published'] ?? false),
        ]);
    }

    /**
     * @param array{
     *  reading_class_id: int,
     *  title: string,
     *  description?: string|null,
     *  open_at?: string|null,
     *  due_at?: string|null,
     *  is_published?: bool
     * } $payload
     */
    public function update(Assignment $assignment, array $payload): void
    {
        $this->validateSchedule($payload['open_at'] ?? null, $payload['due_at'] ?? null);

        $assignment->update([
            'reading_class_id' => $payload['reading_class_id'],
            'title' => $payload['title'],
            'description' => $payload['description'] ?? null,
            'open_at' => $payload['open_at'] ?? null,
            'due_at' => $payload['due_at'] ?? null,
            'is_published' => (bool) ($payload['is_published'] ?? false),
        ]);
    }

    public function delete(Assignment $assignment): void
    {
        $assignment->delete();
    }

    /**
     * @param array{
     *  type: string,
     *  prompt: string,
     *  max_score: int|float|string,
     *  position: int,
     *  options_raw?: string|null,
     *  correct_answer?: string|null
     * } $payload
     */
    public function createQuestion(Assignment $assignment, array $payload): AssignmentQuestion
    {
        $normalized = $this->normalizeQuestionPayload($payload);

        return $assignment->questions()->create($normalized);
    }

    /**
     * @param array{
     *  type: string,
     *  prompt: string,
     *  max_score: int|float|string,
     *  position: int,
     *  options_raw?: string|null,
     *  correct_answer?: string|null
     * } $payload
     */
    public function updateQuestion(Assignment $assignment, AssignmentQuestion $question, array $payload): void
    {
        if ((int) $question->assignment_id !== (int) $assignment->id) {
            throw ValidationException::withMessages([
                'question' => 'Câu hỏi không thuộc bài tập hiện tại.',
            ]);
        }

        $normalized = $this->normalizeQuestionPayload($payload);
        $question->update($normalized);
    }

    public function deleteQuestion(Assignment $assignment, AssignmentQuestion $question): void
    {
        if ((int) $question->assignment_id !== (int) $assignment->id) {
            throw ValidationException::withMessages([
                'question' => 'Câu hỏi không thuộc bài tập hiện tại.',
            ]);
        }

        DB::transaction(function () use ($question): void {
            $question->delete();
        });
    }

    private function validateSchedule(?string $openAt, ?string $dueAt): void
    {
        if ($openAt === null || $dueAt === null) {
            return;
        }

        if (strtotime($openAt) === false || strtotime($dueAt) === false) {
            throw ValidationException::withMessages([
                'schedule' => 'Thời gian mở/đóng không hợp lệ.',
            ]);
        }

        if (strtotime($openAt) > strtotime($dueAt)) {
            throw ValidationException::withMessages([
                'due_at' => 'Hạn nộp phải lớn hơn hoặc bằng thời gian mở.',
            ]);
        }
    }

    /**
     * @param array{
     *  type: string,
     *  prompt: string,
     *  max_score: int|float|string,
     *  position: int,
     *  options_raw?: string|null,
     *  correct_answer?: string|null
     * } $payload
     * @return array{
     *  type: string,
     *  prompt: string,
     *  max_score: float,
     *  position: int,
     *  options_json?: array<int,string>|null,
     *  correct_answer?: string|null
     * }
     */
    private function normalizeQuestionPayload(array $payload): array
    {
        $type = (string) $payload['type'];
        $prompt = trim((string) $payload['prompt']);
        $position = (int) $payload['position'];
        $maxScore = (float) $payload['max_score'];

        if ($prompt === '') {
            throw ValidationException::withMessages([
                'prompt' => 'Nội dung câu hỏi là bắt buộc.',
            ]);
        }

        if ($maxScore < 0) {
            throw ValidationException::withMessages([
                'max_score' => 'Điểm tối đa phải lớn hơn hoặc bằng 0.',
            ]);
        }

        if ($type !== 'multiple_choice' && $type !== 'text_input' && $type !== 'file_input') {
            throw ValidationException::withMessages([
                'type' => 'Loại câu hỏi không hợp lệ.',
            ]);
        }

        $normalized = [
            'type' => $type,
            'prompt' => $prompt,
            'max_score' => $maxScore,
            'position' => $position,
            'options_json' => null,
            'correct_answer' => null,
        ];

        if ($type === 'multiple_choice') {
            $rawOptions = (string) ($payload['options_raw'] ?? '');
            $options = array_values(array_filter(
                array_map(static fn (string $line): string => trim($line), preg_split('/\r\n|\r|\n/', $rawOptions) ?: []),
                static fn (string $line): bool => $line !== ''
            ));

            if (count($options) < 2) {
                throw ValidationException::withMessages([
                    'options_raw' => 'Câu trắc nghiệm cần ít nhất 2 đáp án.',
                ]);
            }

            $correctAnswer = (string) ($payload['correct_answer'] ?? '');
            if ($correctAnswer === '' || !in_array($correctAnswer, $options, true)) {
                throw ValidationException::withMessages([
                    'correct_answer' => 'Đáp án đúng phải nằm trong danh sách đáp án.',
                ]);
            }

            $normalized['options_json'] = $options;
            $normalized['correct_answer'] = $correctAnswer;
        }

        return $normalized;
    }
}

