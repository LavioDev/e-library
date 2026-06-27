<?php

namespace App\Services\TextTopic;

use App\Models\TextTopic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TextTopicQueryBuilder
{
    /**
     * @param  array{keyword: string}  $filters
     */
    public function paginateForManagement(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return TextTopic::query()
            ->withCount('texts')
            ->when($filters['keyword'] !== '', function (Builder $query) use ($filters): void {
                $query->where('name', 'like', '%'.$filters['keyword'].'%');
            })
            ->latest()
            ->paginate($perPage);
    }
}
