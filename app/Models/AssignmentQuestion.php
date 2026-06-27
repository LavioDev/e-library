<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssignmentQuestion extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'assignment_id',
        'type',
        'prompt',
        'options_json',
        'correct_answer',
        'max_score',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'options_json' => 'array',
            'max_score' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<Assignment, $this>
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * @return HasMany<AssignmentSubmissionAnswer, $this>
     */
    public function submissionAnswers(): HasMany
    {
        return $this->hasMany(AssignmentSubmissionAnswer::class, 'question_id');
    }
}

