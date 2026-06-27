<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TextDocument extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'text_id',
        'title',
        'content',
        'file_path',
    ];

    /**
     * @return BelongsTo<Text, $this>
     */
    public function text(): BelongsTo
    {
        return $this->belongsTo(Text::class);
    }
}
