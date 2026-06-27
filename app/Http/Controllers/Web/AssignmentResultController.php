<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Assignment\GradeAssignmentSubmissionWebRequest;
use App\Models\Assignment;
use App\Models\AssignmentAnswerFile;
use App\Models\AssignmentSubmission;
use App\Models\ReadingClass;
use App\Models\User;
use App\Services\Assignment\AssignmentSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssignmentResultController extends Controller
{
    public function __construct(
        private readonly AssignmentSubmissionService $submissionService,
    ) {}

    public function index(ReadingClass $readingClass, Request $request): View
    {
        $readingClass->load('texts:id,name');

        $assignments = $readingClass->assignments()
            ->withCount('questions')
            ->latest()
            ->get(['id', 'reading_class_id', 'title', 'due_at', 'is_published', 'created_at']);

        /** @var Assignment|null $selectedAssignment */
        $selectedAssignment = null;
        if ($assignments->isNotEmpty()) {
            $selectedAssignment = $assignments->firstWhere('id', (int) $request->query('assignment_id')) ?? $assignments->first();
            $selectedAssignment->load(['questions:id,assignment_id,max_score']);
        }

        $students = $readingClass->users()
            ->where('role', 'user')
            ->orderBy('name')
            ->get(['users.id', 'users.name', 'users.email']);

        $submissionsByStudent = collect();
        $selectedSubmission = null;

        if ($selectedAssignment !== null && $students->isNotEmpty()) {
            $studentIds = $students->pluck('id')->all();
            $submissions = AssignmentSubmission::query()
                ->where('assignment_id', $selectedAssignment->id)
                ->whereIn('student_id', $studentIds)
                ->with([
                    'student:id,name,email',
                    'grader:id,name',
                    'answers.question:id,type,correct_answer,max_score',
                ])
                ->orderByDesc('attempt_no')
                ->get();

            $submissionsByStudent = $submissions->groupBy('student_id')->map(
                fn ($items) => $items->first()
            );

            $selectedStudentId = (int) $request->query('student_id');
            if ($selectedStudentId > 0 && $submissionsByStudent->has($selectedStudentId)) {
                $selectedSubmission = $submissionsByStudent->get($selectedStudentId);
            } elseif ($request->filled('submission_id')) {
                $submissionId = (int) $request->query('submission_id');
                /** @var AssignmentSubmission|null $selectedById */
                $selectedById = $submissions->firstWhere('id', $submissionId);
                $selectedSubmission = $selectedById;
            } else {
                $selectedSubmission = $submissionsByStudent->first();
            }

            if ($selectedSubmission !== null) {
                $selectedSubmission->load([
                    'assignment:id,reading_class_id,title',
                    'answers.question',
                    'answers.files',
                    'student:id,name,email',
                    'grader:id,name',
                ]);
            }
        }

        return view('reading-classes.results', [
            'readingClass' => $readingClass,
            'assignments' => $assignments,
            'selectedAssignment' => $selectedAssignment,
            'students' => $students,
            'submissionsByStudent' => $submissionsByStudent,
            'selectedSubmission' => $selectedSubmission,
        ]);
    }

    public function grade(
        ReadingClass $readingClass,
        AssignmentSubmission $submission,
        GradeAssignmentSubmissionWebRequest $request
    ): RedirectResponse {
        $submission->loadMissing('assignment');
        abort_unless((int) $submission->assignment?->reading_class_id === (int) $readingClass->id, 404);

        /** @var User $teacher */
        $teacher = $request->user();
        $this->submissionService->grade(
            $teacher,
            $submission,
            $request->validated()['answers'],
            $request->validated()['overall_comment'] ?? null
        );

        return redirect()
            ->route('admin.reading-classes.results', [
                'readingClass' => $readingClass->id,
                'assignment_id' => $submission->assignment_id,
                'student_id' => $submission->student_id,
                'submission_id' => $submission->id,
            ])
            ->with('status', 'Đã hoàn thành chấm bài.');
    }

    public function downloadFile(ReadingClass $readingClass, AssignmentAnswerFile $file): StreamedResponse
    {
        $file->loadMissing('submissionAnswer.submission.assignment');
        $submission = $file->submissionAnswer?->submission;
        $submissionReadingClassId = $submission?->assignment?->reading_class_id;

        abort_unless((int) $submissionReadingClassId === (int) $readingClass->id, 404);
        abort_unless(Storage::disk('local')->exists($file->file_path), 404);

        return Storage::disk('local')->download($file->file_path, $file->original_name);
    }
}
