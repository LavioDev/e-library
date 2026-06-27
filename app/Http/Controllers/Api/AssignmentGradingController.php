<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignmentSubmission\GradeAssignmentSubmissionRequest;
use App\Models\AssignmentSubmission;
use App\Models\User;
use App\Services\Assignment\AssignmentSubmissionService;
use Illuminate\Http\JsonResponse;

class AssignmentGradingController extends Controller
{
    public function grade(
        AssignmentSubmission $submission,
        GradeAssignmentSubmissionRequest $request,
        AssignmentSubmissionService $service
    ): JsonResponse {
        /** @var User $teacher */
        $teacher = $request->user();

        $graded = $service->grade(
            $teacher,
            $submission,
            $request->validated()['answers'],
            $request->validated()['overall_comment'] ?? null
        );

        return response()->json([
            'message' => 'Submission graded successfully.',
            'data' => $graded->load(['answers.files', 'assignment.questions', 'grader:id,name']),
        ]);
    }
}

