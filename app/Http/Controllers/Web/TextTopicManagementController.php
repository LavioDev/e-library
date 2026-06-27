<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\TextTopic\DestroyTextTopicRequest;
use App\Http\Requests\Web\TextTopic\IndexTextTopicRequest;
use App\Http\Requests\Web\TextTopic\StoreTextTopicRequest;
use App\Http\Requests\Web\TextTopic\UpdateTextTopicRequest;
use App\Models\TextTopic;
use App\Services\TextTopic\TextTopicService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TextTopicManagementController extends Controller
{
    public function __construct(
        private TextTopicService $textTopicService,
    ) {}

    public function index(IndexTextTopicRequest $request): View
    {
        $filters = $request->filters();
        $textTopics = $this->textTopicService->paginateForManagement($filters);

        return view('text-topics.index', compact('textTopics', 'filters'));
    }

    public function store(StoreTextTopicRequest $request): RedirectResponse
    {
        $this->textTopicService->create($request->validated());

        return redirect()
            ->route('admin.text-topics.index')
            ->with('status', 'Đã tạo loại văn bản thành công.');
    }

    public function update(UpdateTextTopicRequest $request, TextTopic $textTopic): RedirectResponse
    {
        $this->textTopicService->update($textTopic, $request->validated());

        return redirect()
            ->route('admin.text-topics.index')
            ->with('status', 'Đã cập nhật loại văn bản thành công.');
    }

    public function destroy(DestroyTextTopicRequest $request, TextTopic $textTopic): RedirectResponse
    {
        $this->textTopicService->delete($textTopic);

        return redirect()
            ->route('admin.text-topics.index')
            ->with('status', 'Đã xóa loại văn bản thành công.');
    }

    public function export(): StreamedResponse
    {
        $textTopics = TextTopic::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Tên loại văn bản');
        $sheet->setCellValue('B1', 'Ngày tạo');

        // Make header bold
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);

        $rowNumber = 2;
        foreach ($textTopics as $textTopic) {
            $sheet->setCellValue('A' . $rowNumber, $textTopic->name);
            $sheet->setCellValue('B' . $rowNumber, optional($textTopic->created_at)->format('d/m/Y H:i') ?? '');
            $rowNumber++;
        }

        // Auto-size columns
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'loai-van-ban-' . date('Ymd-His') . '.xlsx';

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

        // Set header
        $sheet->setCellValue('A1', 'Tên loại văn bản');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        // Set sample data
        $sheet->setCellValue('A2', 'Ví dụ: Truyện ngắn');
        $sheet->setCellValue('A3', 'Ví dụ: Thơ lục bát');

        $sheet->getColumnDimension('A')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'mau-nhap-loai-van-ban.xlsx';

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
            'import_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ], [
            'import_file.required' => 'Vui lòng chọn file Excel hoặc CSV để nhập.',
            'import_file.file' => 'Tệp tải lên không hợp lệ.',
            'import_file.mimes' => 'Chỉ hỗ trợ các định dạng .xlsx, .xls, .csv.',
            'import_file.max' => 'Kích thước file không được vượt quá 5MB.',
        ]);

        $file = $request->file('import_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $importedCount = 0;
        $skippedCount = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue; // Skip header row
            }

            $name = trim($row[0] ?? '');
            if ($name === '') {
                continue;
            }

            // Check if text topic name already exists
            $exists = TextTopic::query()->where('name', $name)->exists();
            if ($exists) {
                $skippedCount++;
                continue;
            }

            $this->textTopicService->create(['name' => $name]);
            $importedCount++;
        }

        $message = "Đã nhập thành công {$importedCount} loại văn bản.";
        if ($skippedCount > 0) {
            $message .= " Bỏ qua {$skippedCount} loại văn bản đã tồn tại.";
        }

        return redirect()
            ->route('admin.text-topics.index')
            ->with('status', $message);
    }
}
