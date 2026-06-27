<?php

namespace App\Services\Text;

use App\Models\Text;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TextService
{
    public function __construct(
        private TextQueryBuilder $textQueryBuilder,
    ) {}

    /**
     * @param  array{keyword: string, text_topic_id: string, difficulty: string}  $filters
     */
    public function paginateForManagement(array $filters): LengthAwarePaginator
    {
        return $this->textQueryBuilder->paginateForManagement($filters);
    }

    /**
     * @param  array{keyword: string, text_topic_id: string, difficulty: string}  $filters
     */
    public function getForExport(array $filters)
    {
        return $this->textQueryBuilder->getForExport($filters);
    }

    /**
     * @param  array{text_topic_id: int, topic?: string|null, name: string, author: string, difficulty: string, read_link?: string|null}  $payload
     */
    public function create(array $payload): Text
    {
        return Text::query()->create($payload);
    }

    /**
     * @param  array{text_topic_id: int, topic?: string|null, name: string, author: string, difficulty: string, read_link?: string|null}  $payload
     */
    public function update(Text $text, array $payload): void
    {
        $text->update($payload);
    }

    public function delete(Text $text): void
    {
        $text->delete();
    }
}
