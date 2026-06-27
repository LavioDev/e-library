<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TextFile extends Model
{
    /** @use HasFactory<\Database\Factories\TextFileFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'text_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    /**
     * @return BelongsTo<Text, $this>
     */
    public function text(): BelongsTo
    {
        return $this->belongsTo(Text::class);
    }
}
