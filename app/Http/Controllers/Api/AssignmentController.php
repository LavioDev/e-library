<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Assignment\StoreAssignmentRequest;
use App\Http\Requests\Api\Assignment\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Services\Assignment\AssignmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, (int) $request->query('per_page', 10));
        $assignments = Assignment::query()
            ->with(['readingClass:id,name', 'questions:id,assignment_id,type,position,max_score'])
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'data' => $assignments->items(),
            'meta' => [
                'current_page' => $assignments->currentPage(),
                'last_page' => $assignments->lastPage(),
                'per_page' => $assignments->perPage(),
                'total' => $assignments->total(),
            ],
        ]);
    }

    public function show(Assignment $assignment): JsonResponse
    {
        $assignment->load([
            'readingClass:id,name',
            'questions' => fn ($query) => $query->orderBy('position'),
        ]);

        return response()->json([
            'data' => $assignment,
        ]);
    }

    public function store(StoreAssignmentRequest $request, AssignmentService $service): JsonResponse
    {
        $assignment = $service->create($request->validated());

        return response()->json([
            'message' => 'Assignment created successfully.',
            'data' => $assignment->load(['readingClass:id,name', 'questions']),
        ], 201);
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment, AssignmentService $service): JsonResponse
    {
        $updatedAssignment = $service->update($assignment, $request->validated());

        return response()->json([
            'message' => 'Assignment updated successfully.',
            'data' => $updatedAssignment->load(['readingClass:id,name', 'questions']),
        ]);
    }

    public function destroy(Assignment $assignment, AssignmentService $service): JsonResponse
    {
        $service->delete($assignment);

        return response()->json([
            'message' => 'Assignment deleted successfully.',
        ]);
    }
}

