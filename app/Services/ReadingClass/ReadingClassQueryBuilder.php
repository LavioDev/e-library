<?php

namespace App\Services\ReadingClass;

use App\Models\ReadingClass;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ReadingClassQueryBuilder
{
    /**
     * @param  array{keyword: string, text_id: string}  $filters
     */
    public function paginateForManagement(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return ReadingClass::query()
            ->with(['texts:id,name', 'users:id'])
            ->withCount(['users', 'assignments'])
            ->when($filters['keyword'] !== '', function (Builder $query) use ($filters): void {
                $query->where('name', 'like', '%'.$filters['keyword'].'%');
            })
            ->when($filters['text_id'] !== '', function (Builder $query) use ($filters): void {
                $query->whereHas('texts', function (Builder $textsQuery) use ($filters): void {
                    $textsQuery->where('texts.id', (int) $filters['text_id']);
                });
            })
            ->latest()
            ->paginate($perPage);
    }
}
