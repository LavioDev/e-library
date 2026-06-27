<?php

namespace App\Services\Assignment;

use App\Models\Assignment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class AssignmentManagementQueryBuilder
{
    /**
     * @param array{keyword: string, text_id: string, reading_class_id: string, is_published: string} $filters
     */
    public function paginateForManagement(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return Assignment::query()
            ->with(['readingClass:id,name'])
            ->withCount('questions')
            ->when($filters['keyword'] !== '', function (Builder $query) use ($filters): void {
                $query->where('title', 'like', '%'.$filters['keyword'].'%');
            })
            ->when($filters['text_id'] !== '', function (Builder $query) use ($filters): void {
                $query->whereHas('readingClass.texts', function (Builder $textsQuery) use ($filters): void {
                    $textsQuery->where('texts.id', (int) $filters['text_id']);
                });
            })
            ->when($filters['reading_class_id'] !== '', function (Builder $query) use ($filters): void {
                $query->where('reading_class_id', (int) $filters['reading_class_id']);
            })
            ->when($filters['is_published'] !== '', function (Builder $query) use ($filters): void {
                $query->where('is_published', $filters['is_published'] === '1');
            })
            ->latest()
            ->paginate($perPage);
    }
}
