<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder
{
    /**
     * @param  array{keyword: string, role: string}  $filters
     */
    public function paginateForManagement(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->when($filters['keyword'] !== '', function (Builder $query) use ($filters): void {
                $query->where(function (Builder $innerQuery) use ($filters): void {
                    $innerQuery
                        ->where('name', 'like', '%'.$filters['keyword'].'%')
                        ->orWhere('email', 'like', '%'.$filters['keyword'].'%');
                });
            })
            ->when($filters['role'] !== '', function (Builder $query) use ($filters): void {
                $query->where('role', $filters['role']);
            })
            ->latest()
            ->paginate($perPage);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }

    public function findByIdOrFail(int $userId): User
    {
        return User::query()->findOrFail($userId);
    }
}
