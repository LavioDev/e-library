<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\User\DestroyUserRequest;
use App\Http\Requests\Web\User\IndexUserRequest;
use App\Http\Requests\Web\User\StoreUserRequest;
use App\Http\Requests\Web\User\UpdateUserRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {}

    public function index(IndexUserRequest $request): View
    {
        $filters = $request->filters();
        $users = $this->userService->paginateForManagement($filters);

        return view('users.index', compact('users', 'filters'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Đã tạo người dùng thành công.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userService->update($user, $request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Đã cập nhật người dùng thành công.');
    }

    public function destroy(DestroyUserRequest $request, User $user): RedirectResponse
    {
        $this->userService->delete($request->user(), $user);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Đã xóa người dùng thành công.');
    }
}
