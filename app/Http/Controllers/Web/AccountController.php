<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\User\UpdateOwnAccountRequest;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {}

    public function show(): View
    {
        $user = $this->userService->getAuthenticatedUser(auth()->user());

        return view('users.account', compact('user'));
    }

    public function update(UpdateOwnAccountRequest $request): RedirectResponse
    {
        $this->userService->update($request->user(), $request->validated());

        return redirect()
            ->route('account.show')
            ->with('status', 'Cập nhật tài khoản thành công.');
    }
}