<?php

namespace App\Services\Assignment;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AssignmentSubmissionAnswer;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssignmentSubmissionService
{
    public function createDraft(User $student, Assignment $assignment): AssignmentSubmission
    {
        $this->assertStudent($student);
        $this->assertStudentInClass($student, $assignment);
        $this->assertOpenWindow($assignment);

        $nextAttemptNo = (int) AssignmentSubmission::query()
            ->where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->max('attempt_no') + 1;

        return DB::transaction(function () use ($assignment, $student, $nextAttemptNo): AssignmentSubmission {
            $submission = AssignmentSubmission::query()->create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'attempt_no' => $nextAttemptNo,
                'status' => 'draft',
            ]);

            $questions = $assignment->questions()->orderBy('position')->get();
            foreach ($questions as $question) {
                $submission->answers()->create([
                    'question_id' => $question->id,
                    'auto_graded' => false,
                ]);
            }

            return $submission->fresh(['assignment.questions', 'answers.files']);
        });
    }

    /**
     * @param array<int, array{
     *   question_id: int,
     *   selected_answer?: string|null,
     *   text_answer?: string|null,
     *   files?: array<int, array{
     *     file_path: string,
     *     original_name: string,
     *     mime_type?: string|null,
     *     size?: int|null
     *   }>
     * }> $answers
     */
    public function saveDraftAnswers(User $student, AssignmentSubmission $submission, array $answers): AssignmentSubmission
    {
        $this->assertStudentOwnsSubmission($student, $submission);
        $this->assertDraft($submission);
        $this->assertOpenWindow($submission->assignment);

        return DB::transaction(function () use ($submission, $answers): AssignmentSubmission {
            foreach ($answers as $index => $answerInput) {
                $questionId = (int) $answerInput['question_id'];
                /** @var AssignmentSubmissionAnswer|null $answer */
                $answer = $submission->answers()->where('question_id', $questionId)->first();
                if (!$answer) {
                    throw ValidationException::withMessages([
                        "answers.$index.question_id" => 'Question does not belong to this submission.',
                    ]);
                }

                $question = $answer->question;
                if ($question === null) {
                    throw ValidationException::withMessages([
                        "answers.$index.question_id" => 'Question not found.',
                    ]);
                }

                if ($question->type === 'multiple_choice') {
                    $selected = $answerInput['selected_answer'] ?? null;
                    if ($selected !== null && !in_array($selected, $question->options_json ?? [], true)) {
                        throw ValidationException::withMessages([
                            "answers.$index.selected_answer" => 'Invalid multiple choice answer.',
                        ]);
                    }

                    $answer->update([
                        'selected_answer' => $selected,
                        'text_answer' => null,
                    ]);
                } elseif ($question->type === 'text_input') {
                    $answer->update([
                        'selected_answer' => null,
                        'text_answer' => (string) ($answerInput['text_answer'] ?? ''),
                    ]);
                } elseif ($question->type === 'file_input') {
                    $answer->update([
                        'selected_answer' => null,
                        'text_answer' => null,
                    ]);

                    $answer->files()->delete();
                    foreach (($answerInput['files'] ?? []) as $fileIndex => $file) {
                        $filePath = trim((string) ($file['file_path'] ?? ''));
                        $originalName = trim((string) ($file['original_name'] ?? ''));
                        if ($filePath === '' || $originalName === '') {
                            throw ValidationException::withMessages([
                                "answers.$index.files.$fileIndex" => 'file_path and original_name are required.',
                            ]);
                        }

                        $answer->files()->create([
                            'file_path' => $filePath,
                            'original_name' => $originalName,
                            'mime_type' => $file['mime_type'] ?? null,
                            'size' => $file['size'] ?? null,
                        ]);
                    }
                }
            }

            return $submission->fresh(['assignment.questions', 'answers.files']);
        });
    }

    public function submit(User $student, AssignmentSubmission $submission): AssignmentSubmission
    {
        $this->assertStudentOwnsSubmission($student, $submission);
        $this->assertDraft($submission);
        $this->assertOpenWindow($submission->assignment);

        return DB::transaction(function () use ($submission): AssignmentSubmission {
            $answers = $submission->answers()->with('question')->get();
            foreach ($answers as $answer) {
                $question = $answer->question;
                if ($question === null) {
                    continue;
                }

                if ($question->type !== 'multiple_choice') {
                    $answer->update([
                        'auto_graded' => false,
                    ]);
                    continue;
                }

                $isCorrect = $answer->selected_answer !== null
                    && $question->correct_answer !== null
                    && $answer->selected_answer === $question->correct_answer;

                $answer->update([
                    'score' => $isCorrect ? $question->max_score : 0,
                    'auto_graded' => true,
                ]);
            }

            $totalScore = (float) $submission->answers()->sum(DB::raw('COALESCE(score, 0)'));
            $submission->update([
                'status' => 'submitted',
                'submitted_at' => Carbon::now(),
                'total_score' => $totalScore,
            ]);

            return $submission->fresh(['assignment.questions', 'answers.files']);
        });
    }

    /**
     * @param array<int, array{
     *   question_id: int,
     *   score?: int|float|string|null,
     *   comment?: string|null
     * }> $gradedAnswers
     */
    public function grade(User $teacher, AssignmentSubmission $submission, array $gradedAnswers, ?string $overallComment = null): AssignmentSubmission
    {
        $this->assertTeacher($teacher);

        if (!in_array($submission->status, ['submitted', 'graded'], true)) {
            throw ValidationException::withMessages([
                'submission' => 'Only submitted or graded submissions can be graded.',
            ]);
        }

        return DB::transaction(function () use ($teacher, $submission, $gradedAnswers, $overallComment): AssignmentSubmission {
            foreach ($gradedAnswers as $index => $graded) {
                $questionId = (int) $graded['question_id'];
                /** @var AssignmentSubmissionAnswer|null $answer */
                $answer = $submission->answers()->where('question_id', $questionId)->first();
                if (!$answer) {
                    throw ValidationException::withMessages([
                        "answers.$index.question_id" => 'Question does not belong to this submission.',
                    ]);
                }

                $question = $answer->question;
                if ($question === null) {
                    throw ValidationException::withMessages([
                        "answers.$index.question_id" => 'Question not found.',
                    ]);
                }

                $scoreRaw = $graded['score'] ?? null;
                $score = $scoreRaw === null ? null : (float) $scoreRaw;
                if ($score !== null && ($score < 0 || $score > (float) $question->max_score)) {
                    throw ValidationException::withMessages([
                        "answers.$index.score" => 'Score must be between 0 and question max_score.',
                    ]);
                }

                $answer->update([
                    'score' => $score,
                    'comment' => $graded['comment'] ?? null,
                    'auto_graded' => false,
                ]);
            }

            $hasUngradedAnswer = $submission->answers()->whereNull('score')->exists();
            if ($hasUngradedAnswer) {
                throw ValidationException::withMessages([
                    'answers' => 'All answers must have score before marking as graded.',
                ]);
            }

            $totalScore = (float) $submission->answers()->sum('score');
            $submission->update([
                'status' => 'graded',
                'graded_at' => Carbon::now(),
                'graded_by' => $teacher->id,
                'total_score' => $totalScore,
                'overall_comment' => $overallComment,
            ]);

            return $submission->fresh(['assignment.questions', 'answers.files', 'grader']);
        });
    }

    private function assertStudent(User $user): void
    {
        if ($user->role !== 'user') {
            throw new AuthorizationException('Only student can perform this action.');
        }
    }

    private function assertTeacher(User $user): void
    {
        if ($user->role !== 'teacher') {
            throw new AuthorizationException('Only teacher can perform this action.');
        }
    }

    private function assertStudentInClass(User $student, Assignment $assignment): void
    {
        $exists = $assignment->readingClass
            ?->users()
            ->where('users.id', $student->id)
            ->exists();

        if (!$exists) {
            throw ValidationException::withMessages([
                'student_id' => 'Student is not part of assignment class.',
            ]);
        }
    }

    private function assertStudentOwnsSubmission(User $student, AssignmentSubmission $submission): void
    {
        $this->assertStudent($student);

        if ((int) $submission->student_id !== (int) $student->id) {
            throw new AuthorizationException('You are not allowed to access this submission.');
        }
    }

    private function assertDraft(AssignmentSubmission $submission): void
    {
        if ($submission->status !== 'draft') {
            throw ValidationException::withMessages([
                'status' => 'Only draft submission can be modified/submitted.',
            ]);
        }
    }

    private function assertOpenWindow(Assignment $assignment): void
    {
        if (!$assignment->is_published) {
            throw ValidationException::withMessages([
                'assignment' => 'Assignment is not published.',
            ]);
        }

        $now = Carbon::now();
        if ($assignment->open_at !== null && $now->lt($assignment->open_at)) {
            throw ValidationException::withMessages([
                'assignment' => 'Assignment is not open yet.',
            ]);
        }

        if ($assignment->due_at !== null && $now->gt($assignment->due_at)) {
            throw ValidationException::withMessages([
                'assignment' => 'Assignment submission is closed.',
            ]);
        }
    }
}

