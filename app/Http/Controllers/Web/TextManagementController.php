<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Text\DestroyTextRequest;
use App\Http\Requests\Web\Text\IndexTextRequest;
use App\Http\Requests\Web\Text\StoreTextRequest;
use App\Http\Requests\Web\Text\UpdateTextRequest;
use App\Models\Text;
use App\Models\TextTopic;
use App\Services\Text\TextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TextManagementController extends Controller
{
    public function __construct(
        private TextService $textService,
    ) {}

    public function index(IndexTextRequest $request): View
    {
        $filters = $request->filters();
        $texts = $this->textService->paginateForManagement($filters);
        $textTopics = TextTopic::query()->latest()->get(['id', 'name']);

        return view('texts.index', compact('texts', 'filters', 'textTopics'));
    }

    public function store(StoreTextRequest $request): RedirectResponse
    {
        $this->textService->create($request->validated());

        return redirect()
            ->route('admin.texts.index')
            ->with('status', 'Đã tạo văn bản thành công.');
    }

    public function update(UpdateTextRequest $request, Text $text): RedirectResponse
    {
        $this->textService->update($text, $request->validated());

        return redirect()
            ->route('admin.texts.index')
            ->with('status', 'Đã cập nhật văn bản thành công.');
    }

    public function destroy(DestroyTextRequest $request, Text $text): RedirectResponse
    {
        $this->textService->delete($text);

        return redirect()
            ->route('admin.texts.index')
            ->with('status', 'Đã xóa văn bản thành công.');
    }

    public function export(IndexTextRequest $request): StreamedResponse
    {
        $filters = $request->filters();
        $texts = $this->textService->getForExport($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tên văn bản');
        $sheet->setCellValue('B1', 'Chủ đề');
        $sheet->setCellValue('C1', 'Tác giả');
        $sheet->setCellValue('D1', 'Loại văn bản');
        $sheet->setCellValue('E1', 'Mức độ');
        $sheet->setCellValue('F1', 'Link đọc');
        $sheet->setCellValue('G1', 'Ngày tạo');

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        $rowNumber = 2;
        foreach ($texts as $text) {
            $difficultyLabel = match ($text->difficulty) {
                'easy' => 'Dễ',
                'medium' => 'Trung bình',
                'hard' => 'Khó',
                default => $text->difficulty,
            };

            $sheet->setCellValue('A'.$rowNumber, $text->name);
            $sheet->setCellValue('B'.$rowNumber, $text->topic ?? '');
            $sheet->setCellValue('C'.$rowNumber, $text->author);
            $sheet->setCellValue('D'.$rowNumber, $text->textTopic?->name ?? '');
            $sheet->setCellValue('E'.$rowNumber, $difficultyLabel);
            $sheet->setCellValue('F'.$rowNumber, $text->read_link ?? '');
            $sheet->setCellValue('G'.$rowNumber, optional($text->created_at)->format('d/m/Y H:i') ?? '');
            $rowNumber++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'danh-sach-van-ban-'.date('Ymd-His').'.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tên văn bản');
        $sheet->setCellValue('B1', 'Chủ đề (Không bắt buộc)');
        $sheet->setCellValue('C1', 'Tác giả');
        $sheet->setCellValue('D1', 'Mức độ (Dễ/Trung bình/Khó)');
        $sheet->setCellValue('E1', 'Link đọc (Không bắt buộc)');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $sheet->setCellValue('A2', 'Lão Hạc');
        $sheet->setCellValue('B2', 'Nông thôn');
        $sheet->setCellValue('C2', 'Nam Cao');
        $sheet->setCellValue('D2', 'Trung bình');
        $sheet->setCellValue('E2', 'https://example.com/laohac');

        $sheet->setCellValue('A3', 'Tắt đèn');
        $sheet->setCellValue('B3', 'Hiện thực');
        $sheet->setCellValue('C3', 'Ngô Tất Tố');
        $sheet->setCellValue('D3', 'Khó');
        $sheet->setCellValue('E3', '');

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'mau-nhap-van-ban.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'text_topic_id' => ['required', 'integer', 'exists:text_topics,id'],
            'import_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ], [
            'text_topic_id.required' => 'Vui lòng chọn loại văn bản.',
            'text_topic_id.exists' => 'Loại văn bản không tồn tại.',
            'import_file.required' => 'Vui lòng chọn file Excel hoặc CSV để nhập.',
            'import_file.file' => 'Tệp tải lên không hợp lệ.',
            'import_file.mimes' => 'Chỉ hỗ trợ các định dạng .xlsx, .xls, .csv.',
            'import_file.max' => 'Kích thước file không được vượt quá 5MB.',
        ]);

        $textTopicId = $request->input('text_topic_id');
        $file = $request->file('import_file');

        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $importedCount = 0;
        $skippedCount = 0;

        $difficultyMap = [
            'dễ' => 'easy',
            'de' => 'easy',
            'easy' => 'easy',
            'trung bình' => 'medium',
            'trung binh' => 'medium',
            'medium' => 'medium',
            'khó' => 'hard',
            'kho' => 'hard',
            'hard' => 'hard',
        ];

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }

            $name = trim($row[0] ?? '');
            $topic = trim($row[1] ?? '');
            $author = trim($row[2] ?? '');

            if ($name === '' || $author === '') {
                $skippedCount++;
                continue;
            }

            $exists = Text::query()
                ->where('text_topic_id', $textTopicId)
                ->where('name', $name)
                ->where('author', $author)
                ->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            $rawDifficulty = mb_strtolower(trim($row[3] ?? ''));
            $difficulty = $difficultyMap[$rawDifficulty] ?? 'easy';
            $readLink = trim($row[4] ?? '');

            if ($readLink !== '' && ! filter_var($readLink, FILTER_VALIDATE_URL)) {
                $readLink = null;
            }

            $this->textService->create([
                'text_topic_id' => (int) $textTopicId,
                'topic' => $topic !== '' ? $topic : null,
                'name' => $name,
                'author' => $author,
                'difficulty' => $difficulty,
                'read_link' => $readLink !== '' ? $readLink : null,
            ]);

            $importedCount++;
        }

        $message = "Đã nhập thành công {$importedCount} văn bản.";
        if ($skippedCount > 0) {
            $message .= " Bỏ qua {$skippedCount} hàng dữ liệu không hợp lệ hoặc đã tồn tại.";
        }

        return redirect()
            ->route('admin.texts.index')
            ->with('status', $message);
    }
}
