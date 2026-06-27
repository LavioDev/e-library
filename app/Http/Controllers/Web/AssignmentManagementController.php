<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Assignment\DestroyAssignmentRequest;
use App\Http\Requests\Web\Assignment\IndexAssignmentRequest;
use App\Http\Requests\Web\Assignment\StoreAssignmentRequest;
use App\Http\Requests\Web\Assignment\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\ReadingClass;
use App\Services\Assignment\AssignmentManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssignmentManagementController extends Controller
{
    public function __construct(
        private AssignmentManagementService $assignmentManagementService,
    ) {}

    public function index(IndexAssignmentRequest $request): View
    {
        $filters = $request->filters();
        $assignments = $this->assignmentManagementService->paginateForManagement($filters);
        $readingClasses = ReadingClass::query()->latest()->get(['id', 'name']);

        return view('assignments.index', compact('assignments', 'filters', 'readingClasses'));
    }

    public function store(StoreAssignmentRequest $request): RedirectResponse
    {
        $this->assignmentManagementService->create($request->validated());

        return redirect()
            ->route('admin.assignments.index')
            ->with('status', 'Đã tạo bài tập thành công.');
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        $this->assignmentManagementService->update($assignment, $request->validated());

        return redirect()
            ->route('admin.assignments.index')
            ->with('status', 'Đã cập nhật bài tập thành công.');
    }

    public function destroy(DestroyAssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        $this->assignmentManagementService->delete($assignment);

        return redirect()
            ->route('admin.assignments.index')
            ->with('status', 'Đã xóa bài tập thành công.');
    }
}

