<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Text\IndexTextRequest;
use App\Http\Requests\Api\Text\StoreTextRequest;
use App\Http\Requests\Api\Text\UpdateTextRequest;
use App\Models\Text;
use App\Models\TextDocument;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class TextController extends Controller
{
    public function index(IndexTextRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 10);

        $query = Text::query()
            ->with(['textTopic:id,name', 'document:text_id,title,content'])
            ->when(($filters['keyword'] ?? '') !== '', function (Builder $builder) use ($filters): void {
                $keyword = (string) $filters['keyword'];
                $builder->where(function (Builder $q) use ($keyword): void {
                    $q->where('name', 'like', '%'.$keyword.'%')
                        ->orWhere('author', 'like', '%'.$keyword.'%')
                        ->orWhere('topic', 'like', '%'.$keyword.'%');
                });
            })
            ->when(($filters['text_topic_id'] ?? null) !== null, function (Builder $builder) use ($filters): void {
                $builder->where('text_topic_id', (int) $filters['text_topic_id']);
            })
            ->when(($filters['difficulty'] ?? '') !== '', function (Builder $builder) use ($filters): void {
                $builder->where('difficulty', (string) $filters['difficulty']);
            })
            ->latest();

        $texts = $query->paginate($perPage);

        return response()->json([
            'data' => $texts->items(),
            'meta' => [
                'current_page' => $texts->currentPage(),
                'last_page' => $texts->lastPage(),
                'per_page' => $texts->perPage(),
                'total' => $texts->total(),
            ],
        ]);
    }

    public function show(Text $text): JsonResponse
    {
        $text->load(['textTopic:id,name', 'document:text_id,title,content']);

        return response()->json([
            'data' => $text,
        ]);
    }

    public function store(StoreTextRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $text = Text::query()->create([
            'text_topic_id' => $payload['text_topic_id'],
            'topic' => $payload['topic'] ?? null,
            'name' => $payload['name'],
            'author' => $payload['author'],
            'difficulty' => $payload['difficulty'],
            'read_link' => $payload['read_link'] ?? null,
        ]);

        if (isset($payload['document'])) {
            TextDocument::query()->create([
                'text_id' => $text->id,
                'title' => $payload['document']['title'],
                'content' => $payload['document']['content'],
            ]);
        }

        $text->load(['textTopic:id,name', 'document:text_id,title,content']);

        return response()->json([
            'message' => 'Text created successfully.',
            'data' => $text,
        ], 201);
    }

    public function update(UpdateTextRequest $request, Text $text): JsonResponse
    {
        $payload = $request->validated();

        $text->update([
            'text_topic_id' => $payload['text_topic_id'],
            'topic' => $payload['topic'] ?? null,
            'name' => $payload['name'],
            'author' => $payload['author'],
            'difficulty' => $payload['difficulty'],
            'read_link' => $payload['read_link'] ?? null,
        ]);

        if (isset($payload['document'])) {
            TextDocument::query()->updateOrCreate(
                ['text_id' => $text->id],
                [
                    'title' => $payload['document']['title'],
                    'content' => $payload['document']['content'],
                ],
            );
        }

        $text->load(['textTopic:id,name', 'document:text_id,title,content']);

        return response()->json([
            'message' => 'Text updated successfully.',
            'data' => $text,
        ]);
    }

    public function destroy(Text $text): JsonResponse
    {
        $text->delete();

        return response()->json([
            'message' => 'Text deleted successfully.',
        ]);
    }
}
