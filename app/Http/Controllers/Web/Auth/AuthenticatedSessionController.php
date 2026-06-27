<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\LoginWebRequest;
use App\Http\Requests\Web\Auth\LogoutWebRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function store(LoginWebRequest $request, AuthService $authService): RedirectResponse
    {
        $authService->loginWeb($request->validated());

        $request->session()->regenerate();

        return redirect()
            ->intended(route('home'))
            ->with('status', 'Đăng nhập thành công.');
    }

    public function destroy(LogoutWebRequest $request, AuthService $authService): RedirectResponse
    {
        $authService->logoutWeb($request);

        return redirect()
            ->route('home')
            ->with('status', 'Đăng xuất thành công.');
    }
}
