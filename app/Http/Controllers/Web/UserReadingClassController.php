<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ReadingClass;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;
use App\Services\Assignment\AssignmentSubmissionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserReadingClassController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $readingClasses = ReadingClass::query()
            ->whereHas('users', fn ($query) => $query->where('users.id', $user->id))
            ->with([
                'texts:id,name,author',
                'assignments' => fn ($query) => $query->where('is_published', true),
            ])
            ->withCount([
                'users',
                'texts',
                'assignments' => fn ($query) => $query->where('is_published', true),
            ])
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('user-reading-classes.index', [
            'readingClasses' => $readingClasses,
        ]);
    }

    public function show(Request $request, ReadingClass $readingClass): View
    {
        /** @var User $user */
        $user = $request->user();

        // Ensure the student belongs to the reading class
        if (!$readingClass->users()->where('users.id', $user->id)->exists()) {
            abort(403, 'Bạn không thuộc nhóm học sinh này.');
        }

        $readingClass->load([
            'texts.textTopic',
            'assignments' => fn ($query) => $query->where('is_published', true)->with([
                'questions:id,assignment_id,max_score',
                'submissions' => fn ($subQuery) => $subQuery->where('student_id', $user->id)->latest('attempt_no'),
            ]),
            'users' => fn ($query) => $query->orderByRaw("role = 'teacher' DESC")->orderBy('name'),
        ]);

        return view('user-reading-classes.show', [
            'class' => $readingClass,
            'now' => \Illuminate\Support\Carbon::now(),
        ]);
    }

    public function takeAssignment(Request $request, ReadingClass $readingClass, Assignment $assignment): View
    {
        /** @var User $user */
        $user = $request->user();

        // Ensure the student belongs to the reading class
        if (!$readingClass->users()->where('users.id', $user->id)->exists()) {
            abort(403, 'Bạn không thuộc nhóm học sinh này.');
        }

        // Ensure the assignment belongs to the class
        if ((int) $assignment->reading_class_id !== (int) $readingClass->id) {
            abort(404, 'Bài tập không thuộc nhóm học sinh này.');
        }

        $submission = AssignmentSubmission::query()
            ->where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->latest('attempt_no')
            ->first();

        if (!$submission) {
            $service = app(AssignmentSubmissionService::class);
            $submission = $service->createDraft($user, $assignment);
        }

        $submission->load([
            'assignment.questions' => fn ($q) => $q->orderBy('position'),
            'answers.question',
            'answers.files',
        ]);

        return view('user-reading-classes.take', [
            'class' => $readingClass,
            'assignment' => $assignment,
            'submission' => $submission,
            'now' => \Illuminate\Support\Carbon::now(),
        ]);
    }

    public function saveAssignmentAnswers(
        Request $request,
        ReadingClass $readingClass,
        Assignment $assignment
    ): RedirectResponse {
        /** @var User $user */
        $user = $request->user();

        // Ensure the student belongs to the reading class
        if (!$readingClass->users()->where('users.id', $user->id)->exists()) {
            abort(403, 'Bạn không thuộc nhóm học sinh này.');
        }

        $submission = AssignmentSubmission::query()
            ->where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->latest('attempt_no')
            ->firstOrFail();

        $answersInput = [];
        $answersData = $request->input('answers', []);

        foreach ($submission->assignment->questions as $question) {
            $questionId = $question->id;
            $data = $answersData[$questionId] ?? [];

            if ($question->type === 'file_input') {
                if ($request->hasFile("answers.$questionId.file")) {
                    $uploadedFile = $request->file("answers.$questionId.file");
                    $path = $uploadedFile->store('answers', 'local');
                    $answersInput[] = [
                        'question_id' => $questionId,
                        'files' => [
                            [
                                'file_path' => $path,
                                'original_name' => $uploadedFile->getClientOriginalName(),
                                'mime_type' => $uploadedFile->getMimeType(),
                                'size' => $uploadedFile->getSize(),
                            ]
                        ]
                    ];
                } else {
                    // Keep existing files if not re-uploading
                    $existingAnswer = $submission->answers()->where('question_id', $questionId)->first();
                    if ($existingAnswer && $existingAnswer->files()->exists()) {
                        $files = [];
                        foreach ($existingAnswer->files as $file) {
                            $files[] = [
                                'file_path' => $file->file_path,
                                'original_name' => $file->original_name,
                                'mime_type' => $file->mime_type,
                                'size' => $file->size,
                            ];
                        }
                        $answersInput[] = [
                            'question_id' => $questionId,
                            'files' => $files,
                        ];
                    }
                }
            } else {
                $answersInput[] = [
                    'question_id' => $questionId,
                    'selected_answer' => $data['selected_answer'] ?? null,
                    'text_answer' => $data['text_answer'] ?? null,
                ];
            }
        }

        $service = app(AssignmentSubmissionService::class);
        $service->saveDraftAnswers($user, $submission, $answersInput);

        if ($request->input('action') === 'submit') {
            $service->submit($user, $submission);
            return redirect()
                ->route('user.reading-classes.show', $readingClass)
                ->with('status', 'Đã nộp bài tập thành công.');
        }

        return redirect()
            ->back()
            ->with('status', 'Đã lưu nháp câu trả lời thành công.');
    }
}
