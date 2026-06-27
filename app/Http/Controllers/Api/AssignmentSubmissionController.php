<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignmentSubmission\SaveAssignmentSubmissionAnswersRequest;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;
use App\Services\Assignment\AssignmentSubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentSubmissionController extends Controller
{
    public function createDraft(Assignment $assignment, Request $request, AssignmentSubmissionService $service): JsonResponse
    {
        /** @var User $student */
        $student = $request->user();
        $submission = $service->createDraft($student, $assignment);

        return response()->json([
            'message' => 'Draft submission created successfully.',
            'data' => $submission->load(['answers.files', 'assignment.questions']),
        ], 201);
    }

    public function saveAnswers(
        AssignmentSubmission $submission,
        SaveAssignmentSubmissionAnswersRequest $request,
        AssignmentSubmissionService $service
    ): JsonResponse {
        /** @var User $student */
        $student = $request->user();

        $updatedSubmission = $service->saveDraftAnswers($student, $submission, $request->validated()['answers']);

        return response()->json([
            'message' => 'Draft answers saved successfully.',
            'data' => $updatedSubmission->load(['answers.files', 'assignment.questions']),
        ]);
    }

    public function submit(AssignmentSubmission $submission, Request $request, AssignmentSubmissionService $service): JsonResponse
    {
        /** @var User $student */
        $student = $request->user();
        $submitted = $service->submit($student, $submission);

        return response()->json([
            'message' => 'Submission submitted successfully.',
            'data' => $submitted->load(['answers.files', 'assignment.questions']),
        ]);
    }
}

