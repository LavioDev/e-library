<?php

namespace App\Services\Text;

use App\Models\Text;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TextQueryBuilder
{
    /**
     * @param  array{keyword: string, text_topic_id: string, difficulty: string}  $filters
     */
    public function paginateForManagement(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQueryForManagement($filters)->paginate($perPage);
    }

    /**
     * @param  array{keyword: string, text_topic_id: string, difficulty: string}  $filters
     * @return \Illuminate\Database\Eloquent\Collection<int, Text>
     */
    public function getForExport(array $filters)
    {
        return $this->baseQueryForManagement($filters)->get();
    }

    /**
     * @param  array{keyword: string, text_topic_id: string, difficulty: string}  $filters
     */
    private function baseQueryForManagement(array $filters): Builder
    {
        return Text::query()
            ->with('textTopic')
            ->when($filters['keyword'] !== '', function (Builder $query) use ($filters): void {
                $query->where(function (Builder $innerQuery) use ($filters): void {
                    $innerQuery
                        ->where('name', 'like', '%'.$filters['keyword'].'%')
                        ->orWhere('author', 'like', '%'.$filters['keyword'].'%')
                        ->orWhere('topic', 'like', '%'.$filters['keyword'].'%');
                });
            })
            ->when($filters['text_topic_id'] !== '', function (Builder $query) use ($filters): void {
                $query->where('text_topic_id', (int) $filters['text_topic_id']);
            })
            ->when($filters['difficulty'] !== '', function (Builder $query) use ($filters): void {
                $query->where('difficulty', $filters['difficulty']);
            })
            ->latest();
    }
}
