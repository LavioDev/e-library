<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\User\UserQueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserQueryBuilder $userQueryBuilder,
    ) {}

    /**
     * @param  array{email: string, password: string}  $credentials
     * @return array{token: string, token_type: string, user: User}
     */
    public function login(array $credentials): array
    {
        $user = $this->userQueryBuilder->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            Log::warning('Invalid login attempt.', [
                'email' => $credentials['email'],
            ]);

            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        $token = $user->createToken('library-web')->plainTextToken;

        $user->update(['last_login_at' => now()]);

        Log::info('User logged in.', [
            'user_id' => $user->id,
        ]);

        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];
    }

    public function logout(User $user): void
    {
        $currentToken = $user->currentAccessToken();

        $currentToken?->delete();

        Log::info('User logged out.', [
            'user_id' => $user->id,
            'token_id' => $currentToken?->id,
        ]);
    }

    /**
     * @param  array{email: string, password: string}  $credentials
     */
    public function loginWeb(array $credentials): void
    {
        if (! Auth::attempt($credentials, false)) {
            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không đúng.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();
        $user->update(['last_login_at' => now()]);
    }

    public function logoutWeb(Request $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
