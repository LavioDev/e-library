<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentAnswerFile extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'submission_answer_id',
        'file_path',
        'original_name',
        'mime_type',
        'size',
    ];

    /**
     * @return BelongsTo<AssignmentSubmissionAnswer, $this>
     */
    public function submissionAnswer(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmissionAnswer::class, 'submission_answer_id');
    }
}

