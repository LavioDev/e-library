<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\CurrentUserRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\LogoutRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        return response()->json($authService->login($request->validated()));
    }

    public function logout(LogoutRequest $request, AuthService $authService): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $authService->logout($user);

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(CurrentUserRequest $request, UserService $userService): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'user' => $userService->getAuthenticatedUser($user),
        ]);
    }
}
