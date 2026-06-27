<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'reading_class_id',
        'title',
        'description',
        'open_at',
        'due_at',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'open_at' => 'datetime',
            'due_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<ReadingClass, $this>
     */
    public function readingClass(): BelongsTo
    {
        return $this->belongsTo(ReadingClass::class);
    }

    /**
     * @return HasMany<AssignmentQuestion, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(AssignmentQuestion::class);
    }

    /**
     * @return HasMany<AssignmentSubmission, $this>
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}

