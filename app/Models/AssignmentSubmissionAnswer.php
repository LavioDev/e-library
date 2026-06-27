<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssignmentSubmissionAnswer extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'submission_id',
        'question_id',
        'selected_answer',
        'text_answer',
        'score',
        'comment',
        'auto_graded',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'auto_graded' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<AssignmentSubmission, $this>
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    /**
     * @return BelongsTo<AssignmentQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(AssignmentQuestion::class, 'question_id');
    }

    /**
     * @return HasMany<AssignmentAnswerFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(AssignmentAnswerFile::class, 'submission_answer_id');
    }
}

