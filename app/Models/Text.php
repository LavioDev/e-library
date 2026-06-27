<?php

namespace App\Models;

use Database\Factories\TextFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Text extends Model
{
    /** @use HasFactory<TextFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'text_topic_id',
        'topic',
        'name',
        'author',
        'difficulty',
        'read_link',
    ];

    /**
     * @return list<string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * @return BelongsTo<TextTopic, $this>
     */
    public function textTopic(): BelongsTo
    {
        return $this->belongsTo(TextTopic::class);
    }

    /**
     * @return HasOne<TextDocument, $this>
     */
    public function document(): HasOne
    {
        return $this->hasOne(TextDocument::class);
    }

    /**
     * @return HasMany<ReadingClass, $this>
     */
    public function readingClasses(): HasMany
    {
        return $this->hasMany(ReadingClass::class);
    }

    /**
     * @return HasMany<TextComment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TextComment::class)->latest();
    }

    /**
     * @return HasMany<TextFile, $this>
     */
    public function textFiles(): HasMany
    {
        return $this->hasMany(TextFile::class);
    }

    /**
     * @return HasMany<TextLink, $this>
     */
    public function textLinks(): HasMany
    {
        return $this->hasMany(TextLink::class);
    }
}
