<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Assignment\DestroyAssignmentQuestionRequest;
use App\Http\Requests\Web\Assignment\StoreAssignmentQuestionRequest;
use App\Http\Requests\Web\Assignment\UpdateAssignmentQuestionRequest;
use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use App\Services\Assignment\AssignmentManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentQuestionManagementController extends Controller
{
    public function __construct(
        private AssignmentManagementService $assignmentManagementService,
    ) {}

    public function index(Request $request, Assignment $assignment): View
    {
        $filters = [
            'keyword' => trim((string) $request->query('keyword', '')),
        ];

        $assignment->load(['readingClass.texts']);
        $questions = $assignment->questions()
            ->when($filters['keyword'] !== '', function ($query) use ($filters): void {
                $query->where(function ($subQuery) use ($filters): void {
                    $subQuery
                        ->where('prompt', 'like', '%'.$filters['keyword'].'%')
                        ->orWhere('correct_answer', 'like', '%'.$filters['keyword'].'%');
                });
            })
            ->orderBy('position')
            ->get();

        $nextPosition = ((int) $assignment->questions()->max('position')) + 1;

        return view('assignments.questions', compact('assignment', 'questions', 'filters', 'nextPosition'));
    }

    public function store(StoreAssignmentQuestionRequest $request, Assignment $assignment): RedirectResponse
    {
        $this->assignmentManagementService->createQuestion($assignment, $request->validated());

        return redirect()
            ->route('admin.assignments.questions.index', $assignment)
            ->with('status', 'Đã thêm câu hỏi thành công.');
    }

    public function update(UpdateAssignmentQuestionRequest $request, Assignment $assignment, AssignmentQuestion $question): RedirectResponse
    {
        $this->assignmentManagementService->updateQuestion($assignment, $question, $request->validated());

        return redirect()
            ->route('admin.assignments.questions.index', $assignment)
            ->with('status', 'Đã cập nhật câu hỏi thành công.');
    }

    public function destroy(DestroyAssignmentQuestionRequest $request, Assignment $assignment, AssignmentQuestion $question): RedirectResponse
    {
        $this->assignmentManagementService->deleteQuestion($assignment, $question);

        return redirect()
            ->route('admin.assignments.questions.index', $assignment)
            ->with('status', 'Đã xóa câu hỏi thành công.');
    }
}
