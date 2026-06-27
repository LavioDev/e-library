<?php

namespace App\Services\Text;

use App\Models\Text;
use App\Models\TextDocument;

class TextWriterService
{
    /**
     * @param  array{title: string, content: string}  $payload
     */
    public function save(Text $text, array $payload): TextDocument
    {
        return TextDocument::query()->updateOrCreate(
            ['text_id' => $text->id],
            [
                'title' => $payload['title'],
                'content' => $payload['content'],
            ],
        );
    }
}
