<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ReadingClass\DestroyReadingClassRequest;
use App\Http\Requests\Web\ReadingClass\IndexReadingClassRequest;
use App\Http\Requests\Web\ReadingClass\StoreReadingClassRequest;
use App\Http\Requests\Web\ReadingClass\UpdateReadingClassRequest;
use App\Models\ReadingClass;
use App\Models\Text;
use App\Models\User;
use App\Services\ReadingClass\ReadingClassService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReadingClassManagementController extends Controller
{
    public function __construct(
        private ReadingClassService $readingClassService,
    ) {}

    public function index(IndexReadingClassRequest $request): View
    {
        $filters = $request->filters();
        $readingClasses = $this->readingClassService->paginateForManagement($filters);
        $texts = Text::query()->latest()->get(['id', 'name']);
        $users = User::query()->latest()->get(['id', 'name', 'email', 'role']);

        return view('reading-classes.index', compact('readingClasses', 'filters', 'texts', 'users'));
    }

    public function store(StoreReadingClassRequest $request): RedirectResponse
    {
        $this->readingClassService->create($request->validated());

        return redirect()
            ->route('admin.reading-classes.index')
            ->with('status', 'Đã tạo lớp học thành công.');
    }

    public function update(UpdateReadingClassRequest $request, ReadingClass $readingClass): RedirectResponse
    {
        $this->readingClassService->update($readingClass, $request->validated());

        return redirect()
            ->route('admin.reading-classes.index')
            ->with('status', 'Đã cập nhật lớp học thành công.');
    }

    public function destroy(DestroyReadingClassRequest $request, ReadingClass $readingClass): RedirectResponse
    {
        $this->readingClassService->delete($readingClass);

        return redirect()
            ->route('admin.reading-classes.index')
            ->with('status', 'Đã xóa lớp học thành công.');
    }
}
