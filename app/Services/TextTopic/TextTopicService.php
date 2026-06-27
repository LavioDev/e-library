<?php

namespace App\Services\TextTopic;

use App\Models\TextTopic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TextTopicService
{
    public function __construct(
        private TextTopicQueryBuilder $textTopicQueryBuilder,
    ) {}

    /**
     * @param  array{keyword: string}  $filters
     */
    public function paginateForManagement(array $filters): LengthAwarePaginator
    {
        return $this->textTopicQueryBuilder->paginateForManagement($filters);
    }

    /**
     * @param  array{name: string}  $payload
     */
    public function create(array $payload): TextTopic
    {
        return TextTopic::query()->create($payload);
    }

    /**
     * @param  array{name: string}  $payload
     */
    public function update(TextTopic $textTopic, array $payload): void
    {
        $textTopic->update($payload);
    }

    public function delete(TextTopic $textTopic): void
    {
        $textTopic->delete();
    }
}
