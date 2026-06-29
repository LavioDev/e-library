<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ReadingClass;
use App\Models\Text;
use App\Models\TextTopic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModalSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $keyword = trim((string) $request->query('keyword', ''));
        if ($keyword === '') {
            return response()->json(['groups' => []]);
        }

        $user = $request->user();

        if ($user === null || $user->role === 'user') {
            $texts = Text::query()
                ->where('name', 'like', '%' . $keyword . '%')
                ->orderBy('name')
                ->limit(10)
                ->get(['id', 'name']);

            return response()->json([
                'groups' => [[
                    'type' => 'text',
                    'label' => 'Văn bản',
                    'items' => $texts->map(fn (Text $text): array => [
                        'id' => $text->id,
                        'name' => (string) $text->name,
                        'url' => route('texts.content.show', $text),
                    ])->values()->all(),
                ]],
            ]);
        }

        $topics = TextTopic::query()
            ->where('name', 'like', '%' . $keyword . '%')
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);

        $classes = ReadingClass::query()
            ->where('name', 'like', '%' . $keyword . '%')
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);

        $assignments = Assignment::query()
            ->where('title', 'like', '%' . $keyword . '%')
            ->orderBy('title')
            ->limit(10)
            ->get(['id', 'title']);

        return response()->json([
            'groups' => [
                [
                    'type' => 'text_topic',
                    'label' => 'Loại văn bản',
                    'items' => $topics->map(fn (TextTopic $topic): array => [
                        'id' => $topic->id,
                        'name' => (string) $topic->name,
                        'url' => route('admin.text-topics.index', ['keyword' => $topic->name]),
                    ])->values()->all(),
                ],
                [
                    'type' => 'reading_class',
                    'label' => 'Lớp học',
                    'items' => $classes->map(fn (ReadingClass $readingClass): array => [
                        'id' => $readingClass->id,
                        'name' => (string) $readingClass->name,
                        'url' => route('admin.reading-classes.index', ['keyword' => $readingClass->name]),
                    ])->values()->all(),
                ],
                [
                    'type' => 'assignment',
                    'label' => 'Bài tập',
                    'items' => $assignments->map(fn (Assignment $assignment): array => [
                        'id' => $assignment->id,
                        'name' => (string) $assignment->title,
                        'url' => route('admin.assignments.index', ['keyword' => $assignment->title]),
                    ])->values()->all(),
                ],
            ],
        ]);
    }
}
