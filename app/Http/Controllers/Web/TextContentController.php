<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Text;
use App\Models\TextDocument;
use App\Models\User;
use App\Services\TextDocumentPreviewService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TextContentController extends Controller
{
    public function __construct(
        private readonly TextDocumentPreviewService $previewService
    ) {}

    public function show(Text $text, Request $request): View
    {
        $text->load([
            'document',
            'textTopic',
            'textFiles',
            'textLinks',
            'comments' => fn ($query) => $query->with('user:id,name')->latest(),
        ]);
        $document = $this->ensureDocument($text);
        $preview = $this->previewService->buildPreview($document, $text->id, 'texts.content.preview-image');
        $relatedAssignments = collect();

        return view('texts.content', [
            'text' => $text,
            'document' => $document,
            'previewHtml' => $preview['html'],
            'previewError' => $preview['error'],
            'relatedAssignments' => $relatedAssignments,
            'comments' => $text->comments,
            'now' => Carbon::now(),
        ]);
    }

    public function previewImage(Text $text, string $filename): StreamedResponse
    {
        $relativePath = "text-previews/{$text->id}/{$filename}";
        abort_unless(Storage::disk('public')->exists($relativePath), 404);

        return Storage::disk('public')->response($relativePath);
    }

    public function serveFile(Text $text, string $filename): StreamedResponse
    {
        $relativePath = "text-files/{$text->id}/{$filename}";
        abort_unless(Storage::disk('public')->exists($relativePath), 404);

        return Storage::disk('public')->response($relativePath);
    }

    public function download(Text $text, Request $request): StreamedResponse
    {
        /** @var User|null $user */
        $user = $request->user();
        abort_unless($user !== null && $user->role === 'user', 403);

        $document = $this->ensureDocument($text);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        $baseName = str($text->name)->slug('_')->value();
        $downloadName = ($baseName !== '' ? $baseName : 'van_ban_' . $text->id) . '.docx';

        return Storage::disk('local')->download($document->file_path, $downloadName);
    }

    private function ensureDocument(Text $text): TextDocument
    {
        $document = TextDocument::query()->firstOrCreate(
            ['text_id' => $text->id],
            [
                'title' => $text->name,
                'content' => '',
            ]
        );

        if (!$document->file_path) {
            $document->file_path = 'text-documents/' . $text->id . '.docx';
            $document->save();
        }

        if (!Storage::disk('local')->exists($document->file_path)) {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            $section->addTitle($document->title ?: $text->name, 1);

            $tmpFile = tempnam(sys_get_temp_dir(), 'docx_');
            \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007')->save($tmpFile);
            Storage::disk('local')->put($document->file_path, file_get_contents($tmpFile));
            @unlink($tmpFile);
        }

        return $document->fresh();
    }
}
