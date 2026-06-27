<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Text;
use App\Models\TextDocument;
use App\Models\TextFile;
use App\Models\TextLink;
use App\Services\TextDocumentPreviewService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TextWriterController extends Controller
{
    public function __construct(
        private readonly TextDocumentPreviewService $previewService
    ) {
    }

    public function edit(Text $text): View
    {
        $text->load([
            'document',
            'textFiles',
            'textLinks',
            'comments' => fn ($query) => $query->with('user:id,name')->latest(),
        ]);

        $document = $this->ensureDocument($text);
        $preview = $this->previewService->buildPreview($document, $text->id);

        return view('texts.writer', [
            'text' => $text,
            'document' => $document,
            'previewHtml' => $preview['html'],
            'previewError' => $preview['error'],
            'comments' => $text->comments,
        ]);
    }

    public function storeLink(Request $request, Text $text)
    {
        $payload = $request->validate([
            'url' => ['required', 'url', 'max:2048'],
        ], [
            'url.required' => 'Vui lòng nhập đường dẫn.',
            'url.url' => 'Đường dẫn không đúng định dạng.',
            'url.max' => 'Đường dẫn quá dài.',
        ]);

        $link = $text->textLinks()->create([
            'url' => $payload['url'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'link' => array_merge($link->toArray(), [
                    'drive_type' => $link->getDriveType(),
                ]),
            ]);
        }

        return redirect()
            ->route('admin.texts.writer.edit', $text)
            ->with('status', 'Đã lưu đường dẫn thành công.');
    }

    public function destroyLink(Text $text, TextLink $link)
    {
        abort_unless((string) $link->text_id === (string) $text->id, 404);

        $link->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đường dẫn thành công.'
            ]);
        }

        return redirect()
            ->route('admin.texts.writer.edit', $text)
            ->with('status', 'Đã xóa đường dẫn thành công.');
    }

    public function update(Request $request, Text $text): RedirectResponse
    {
        $payload = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $document = $this->ensureDocument($text);
        $document->update(['title' => $payload['title']]);

        return redirect()
            ->route('admin.texts.writer.edit', $text)
            ->with('status', 'Đã cập nhật tiêu đề văn bản thành công.');
    }

    public function importDocx(Request $request, Text $text): RedirectResponse
    {
        $payload = $request->validate([
            'import_file' => ['required', 'file', 'mimes:docx', 'max:20480'],
        ], [
            'import_file.required' => 'Vui lòng chọn file DOCX để nhập.',
            'import_file.file' => 'Tệp tải lên không hợp lệ hoặc vượt quá dung lượng cho phép của máy chủ.',
            'import_file.mimes' => 'Chỉ hỗ trợ định dạng .docx.',
            'import_file.max' => 'Kích thước file DOCX không được vượt quá 20MB.',
        ]);

        $document = $this->ensureDocument($text);
        Storage::disk('local')->put($document->file_path, file_get_contents($payload['import_file']->getRealPath()));

        return redirect()
            ->route('admin.texts.writer.edit', $text)
            ->with('status', 'Đã nhập file DOCX thành công.');
    }

    public function exportDocx(Text $text): BinaryFileResponse
    {
        $document = $this->ensureDocument($text);
        $fullPath = Storage::disk('local')->path($document->file_path);
        $downloadName = str($document->title ?: $text->name)->slug('-')->toString();
        if ($downloadName === '') {
            $downloadName = 'text-document';
        }

        return response()->download($fullPath, $downloadName . '.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    public function previewImage(Text $text, string $filename): StreamedResponse
    {
        $relativePath = "text-previews/{$text->id}/{$filename}";
        abort_unless(Storage::disk('public')->exists($relativePath), 404);

        return Storage::disk('public')->response($relativePath);
    }

    public function storeFiles(Request $request, Text $text)
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file', 'max:51200'], // max 50MB per file
        ], [
            'files.required' => 'Vui lòng chọn ít nhất một file.',
            'files.*.file' => 'Tệp tải lên không hợp lệ.',
            'files.*.max' => 'Kích thước mỗi file không được vượt quá 50MB.',
        ]);

        $createdFiles = [];

        foreach ($request->file('files') as $file) {
            $mime = $file->getMimeType();
            $type = 'other';
            if (str_starts_with($mime, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $type = 'video';
            } elseif (str_starts_with($mime, 'audio/')) {
                $type = 'audio';
            }

            $fileName = $file->getClientOriginalName();
            $path = $file->store("text-files/{$text->id}", 'public');

            $createdFiles[] = $text->textFiles()->create([
                'file_name' => $fileName,
                'file_path' => $path,
                'file_type' => $type,
                'file_size' => $file->getSize(),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'files' => $createdFiles,
            ]);
        }

        return redirect()
            ->route('admin.texts.writer.edit', $text)
            ->with('status', 'Đã lưu các file thành công.');
    }

    public function destroyFile(Text $text, TextFile $file)
    {
        abort_unless((string) $file->text_id === (string) $text->id, 404);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa file thành công.'
            ]);
        }

        return redirect()
            ->route('admin.texts.writer.edit', $text)
            ->with('status', 'Đã xóa file thành công.');
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
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addTitle($document->title ?: $text->name, 1);

            $tmpFile = tempnam(sys_get_temp_dir(), 'docx_');
            IOFactory::createWriter($phpWord, 'Word2007')->save($tmpFile);
            Storage::disk('local')->put($document->file_path, file_get_contents($tmpFile));
            @unlink($tmpFile);
        }

        return $document->fresh();
    }
}
