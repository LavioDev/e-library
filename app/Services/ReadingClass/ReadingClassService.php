<?php

namespace App\Services\ReadingClass;

use App\Models\ReadingClass;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReadingClassService
{
    public function __construct(
        private ReadingClassQueryBuilder $readingClassQueryBuilder,
    ) {}

    /**
     * @param  array{keyword: string, text_id: string}  $filters
     */
    public function paginateForManagement(array $filters): LengthAwarePaginator
    {
        return $this->readingClassQueryBuilder->paginateForManagement($filters);
    }

    /**
     * @param  array{name: string, text_ids?: array<int, int>, user_ids?: array<int, int>}  $payload
     */
    public function create(array $payload): ReadingClass
    {
        $readingClass = ReadingClass::query()->create([
            'name' => $payload['name'],
        ]);

        $readingClass->texts()->sync($payload['text_ids'] ?? []);
        $readingClass->users()->sync($payload['user_ids'] ?? []);

        return $readingClass;
    }

    /**
     * @param  array{name: string, text_ids?: array<int, int>, user_ids?: array<int, int>}  $payload
     */
    public function update(ReadingClass $readingClass, array $payload): void
    {
        $readingClass->update([
            'name' => $payload['name'],
        ]);

        $readingClass->texts()->sync($payload['text_ids'] ?? []);
        $readingClass->users()->sync($payload['user_ids'] ?? []);
    }

    public function delete(ReadingClass $readingClass): void
    {
        $readingClass->users()->detach();
        $readingClass->delete();
    }
}

