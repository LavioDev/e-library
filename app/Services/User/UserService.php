<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(
        private UserQueryBuilder $userQueryBuilder,
    ) {}

    public function getAuthenticatedUser(User $user): User
    {
        return $this->userQueryBuilder->findByIdOrFail((int) $user->getKey());
    }

    /**
     * @param  array{keyword: string, role: string}  $filters
     */
    public function paginateForManagement(array $filters): LengthAwarePaginator
    {
        return $this->userQueryBuilder->paginateForManagement($filters);
    }

    /**
     * @param  array{name: string, email: string, role: string, password: string}  $payload
     */
    public function create(array $payload): User
    {
        return User::query()->create($payload);
    }

    /**
     * @param  array{name: string, email: string, role: string, password?: string|null}  $payload
     */
    public function update(User $user, array $payload): void
    {
        if (empty($payload['password'])) {
            unset($payload['password']);
        }

        $user->update($payload);
    }

    public function delete(User $actor, User $target): void
    {
        if ((int) $actor->getKey() === (int) $target->getKey()) {
            throw ValidationException::withMessages([
                'delete' => 'Không thể xóa tài khoản đang đăng nhập.',
            ]);
        }

        $target->delete();
    }
}
