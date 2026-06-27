<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\RegisterWebRequest;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {}

    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    public function store(RegisterWebRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        
        // Mặc định vai trò của tài khoản tự đăng ký là 'user'
        $validatedData['role'] = 'user';

        $user = $this->userService->create($validatedData);

        // Đăng nhập tự động ngay sau khi đăng ký thành công
        Auth::guard('web')->login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('status', 'Đăng ký tài khoản thành công và đã tự động đăng nhập!');
    }
}
