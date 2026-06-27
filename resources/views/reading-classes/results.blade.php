@extends('layouts.library')

@section('title', 'Kết quả Nhiệm vụ đọc hiểu')

@push('styles')
    <style>
        /* Tăng width lên full cho trang kết quả */
        main > div.max-w-7xl {
            max-width: 100% !important;
        }
    </style>
@endpush

@section('breadcrumbs')
    <div class="mx-auto max-w-7xl">
        <nav class="mb-4 text-sm text-slate-600">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="hover:text-slate-900">Trang chủ</a></li>
                <li aria-hidden="true">></li>
                <li><a href="{{ route('admin.reading-classes.index') }}" class="hover:text-slate-900">Nhiệm vụ đọc hiểu</a></li>
                <li aria-hidden="true">></li>
                <li><a href="{{ route('admin.assignments.index', ['reading_class_id' => $readingClass->id]) }}" class="hover:text-slate-900">Bộ câu hỏi</a></li>
                <li aria-hidden="true">></li>
                <li class="font-medium text-slate-900">Kết quả</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <section class="space-y-5">
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">{{ $readingClass->name }}</h1>
                    <p class="mt-1 text-sm text-slate-600">{{ $readingClass->texts->pluck('name')->implode(', ') ?: 'Chưa gắn văn bản' }}</p>
                </div>

                <form method="GET" class="flex flex-nowrap items-center gap-3">
                    <label for="assignment_id" class="text-sm font-medium text-slate-700 whitespace-nowrap">Bộ câu hỏi</label>
                    <select id="assignment_id" name="assignment_id" onchange="this.form.submit()" class="select select-sm !h-10 min-h-10 w-80 rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none">
                        @forelse ($assignments as $assignment)
                            <option value="{{ $assignment->id }}" @selected((int) $selectedAssignment?->id === (int) $assignment->id)>
                                {{ $assignment->title }} ({{ $assignment->questions_count }} câu)
                            </option>
                        @empty
                            <option value="">Chưa có bộ câu hỏi</option>
                        @endforelse
                    </select>
                </form>
            </div>
        </div>

        @if ($selectedAssignment === null)
            <div class="rounded-sm border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
                Nhiệm vụ đọc hiểu này chưa có bộ câu hỏi để xem kết quả.
            </div>
        @else
            @php
                $assignmentMaxScore = (float) $selectedAssignment->questions->sum('max_score');
                $assignmentQuestionCount = (int) $selectedAssignment->questions_count;
            @endphp

            <div class="grid grid-cols-10 gap-5">
                <div class="col-span-3 h-fit overflow-hidden rounded-sm border border-slate-200 bg-white shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
                    <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                        <h2 class="text-sm font-semibold text-slate-900">Danh sách học sinh</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr class="text-slate-600">
                                    <th class="min-w-[160px]">Học sinh</th>
                                    <th class="w-fit whitespace-nowrap">Trạng thái</th>
                                    <th>Nộp lúc</th>
                                    <th class="text-right">Kết quả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    @php
                                        $submission = $submissionsByStudent->get($student->id);
                                        $status = $submission?->status ?? 'not_started';
                                        $scaledScore = $assignmentMaxScore > 0 && $submission?->total_score !== null
                                            ? round(((float) $submission->total_score / $assignmentMaxScore) * 10, 2)
                                            : null;

                                        $correctCount = null;
                                        if ($submission !== null) {
                                            $correctCount = $submission->answers->filter(function ($answer) {
                                                $question = $answer->question;
                                                if ($question === null) {
                                                    return false;
                                                }

                                                if ($question->type === 'multiple_choice') {
                                                    return $answer->selected_answer !== null
                                                        && (string) $answer->selected_answer === (string) $question->correct_answer;
                                                }

                                                return $answer->score !== null && (float) $answer->score > 0;
                                            })->count();
                                        }
                                    @endphp
                                    <tr class="{{ (int) $selectedSubmission?->student_id === (int) $student->id ? 'bg-slate-50' : '' }}">
                                        <td>
                                            <a
                                                href="{{ route('admin.reading-classes.results', ['readingClass' => $readingClass->id, 'assignment_id' => $selectedAssignment->id, 'student_id' => $student->id]) }}"
                                                class="block font-medium text-slate-800 hover:text-blue-700"
                                            >
                                                {{ \Illuminate\Support\Str::title((string) $student->name) }}
                                            </a>
                                            <p class="text-xs text-slate-500">{{ $student->email }}</p>
                                        </td>
                                        <td class="w-fit whitespace-nowrap">
                                            @if ($status === 'graded')
                                                <span class="badge badge-sm border-emerald-200 bg-emerald-100 text-emerald-700">Đã chấm</span>
                                            @elseif ($status === 'submitted')
                                                <span class="badge badge-sm border-blue-200 bg-blue-100 text-blue-700">Đã nộp</span>
                                            @elseif ($status === 'draft')
                                                <span class="badge badge-sm border-amber-200 bg-amber-100 text-amber-700">Nháp</span>
                                            @else
                                                <span class="badge badge-sm border-sky-200 bg-sky-100 text-sky-700">Chưa làm</span>
                                            @endif
                                        </td>
                                        <td class="text-xs text-slate-500 whitespace-nowrap">
                                            @if ($submission && $submission->submitted_at)
                                                {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right text-sm font-semibold text-slate-700">
                                            @if ($correctCount !== null)
                                                <span class="block">{{ $correctCount }}/{{ $assignmentQuestionCount }} câu</span>
                                                <span class="block text-xs font-medium text-blue-700">{{ $scaledScore !== null ? number_format($scaledScore, 2) . '/10' : '-' }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-500">Chưa có học sinh trong nhóm.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-span-7 min-w-0 rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
                    @if ($selectedSubmission === null)
                        <p class="text-sm text-slate-500">Chọn học sinh có bài nộp để xem và chấm điểm.</p>
                    @else
                        @php
                            $totalMaxScore = (float) $selectedSubmission->answers->sum(fn ($answer) => (float) ($answer->question?->max_score ?? 0));
                            $scaledScore = $totalMaxScore > 0 && $selectedSubmission->total_score !== null
                                ? round(((float) $selectedSubmission->total_score / $totalMaxScore) * 10, 2)
                                : null;
                            $canGrade = in_array($selectedSubmission->status, ['submitted', 'graded'], true);
                            $studentName = trim((string) ($selectedSubmission->student?->name ?? ''));
                            $totalQuestions = $selectedSubmission->answers->count();
                            $correctCount = $selectedSubmission->answers->filter(function ($answer) {
                                $question = $answer->question;
                                if ($question === null) {
                                    return false;
                                }

                                if ($question->type === 'multiple_choice') {
                                    return $answer->selected_answer !== null
                                        && (string) $answer->selected_answer === (string) $question->correct_answer;
                                }

                                return $answer->score !== null && (float) $answer->score > 0;
                            })->count();
                        @endphp

                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">
                                    Bài làm: {{ $studentName !== '' ? \Illuminate\Support\Str::title($studentName) : ($selectedSubmission->student?->email ?? 'N/A') }}
                                </h2>
                                <p class="mt-1 text-sm text-slate-600">
                                    Trạng thái:
                                    <span class="font-medium">{{ $selectedSubmission->status }}</span>
                                    • Lần làm {{ $selectedSubmission->attempt_no }}
                                </p>
                            </div>
                            <div class="rounded-sm border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                                <p>Đúng: {{ $correctCount }}/{{ $totalQuestions }} câu</p>
                                <p class="mt-1">Điểm thang 10: {{ $scaledScore !== null ? number_format($scaledScore, 2) . '/10' : 'Chưa có' }}</p>
                            </div>
                        </div>

                        @if (!$canGrade)
                            <div class="mt-4 rounded-sm border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                Bài này chưa nộp nên chưa thể hoàn thành chấm bài.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.reading-classes.results.grade', ['readingClass' => $readingClass, 'submission' => $selectedSubmission]) }}" class="mt-5 space-y-5">
                            @csrf
                            @foreach ($selectedSubmission->answers->sortBy(fn ($answer) => $answer->question?->position ?? 0)->values() as $index => $answer)
                                @php
                                    $question = $answer->question;
                                @endphp
                                <article class="rounded-sm border border-slate-200 bg-slate-50 p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h3 class="font-semibold text-slate-900">Câu {{ $question?->position }}: {{ $question?->prompt }}</h3>
                                        <p class="text-xs font-semibold text-slate-500">Tối đa {{ rtrim(rtrim(number_format((float) ($question?->max_score ?? 0), 2, '.', ''), '0'), '.') }} điểm</p>
                                    </div>

                                    <div class="mt-3 rounded-sm border border-slate-200 bg-white p-3 text-sm text-slate-700">
                                        @if ($question?->type === 'multiple_choice')
                                            <p><span class="font-medium">Đáp án chọn:</span> {{ $answer->selected_answer ?? 'Chưa trả lời' }}</p>
                                        @elseif ($question?->type === 'text_input')
                                            <p class="whitespace-pre-line"><span class="font-medium">Trả lời:</span> {{ $answer->text_answer ?: 'Chưa trả lời' }}</p>
                                        @elseif ($question?->type === 'file_input')
                                            <p class="font-medium">Tệp nộp:</p>
                                            <div class="mt-2 space-y-2">
                                                @forelse ($answer->files as $file)
                                                    <div class="flex items-center justify-between gap-2 rounded-sm border border-slate-200 px-3 py-2">
                                                        <span class="truncate text-sm">{{ $file->original_name }}</span>
                                                        <a href="{{ route('admin.reading-classes.results.files.download', ['readingClass' => $readingClass, 'file' => $file]) }}" class="text-xs font-medium text-blue-600 hover:text-blue-700">Tải xuống</a>
                                                    </div>
                                                @empty
                                                    <p class="text-sm text-slate-500">Không có tệp.</p>
                                                @endforelse
                                            </div>
                                        @endif
                                    </div>

                                    <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question?->id }}">
                                    <div class="mt-3 grid gap-3 md:grid-cols-3">
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-slate-700">Điểm</label>
                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                max="{{ (float) ($question?->max_score ?? 0) }}"
                                                name="answers[{{ $index }}][score]"
                                                value="{{ old("answers.$index.score", $answer->score) }}"
                                                class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                                            >
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="mb-1 block text-sm font-medium text-slate-700">Nhận xét</label>
                                            <input
                                                type="text"
                                                name="answers[{{ $index }}][comment]"
                                                value="{{ old("answers.$index.comment", $answer->comment) }}"
                                                class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                                            >
                                        </div>
                                    </div>
                                </article>
                            @endforeach

                            <div>
                                <label for="overall_comment" class="mb-1 block text-sm font-medium text-slate-700">Nhận xét tổng quan</label>
                                <textarea
                                    id="overall_comment"
                                    name="overall_comment"
                                    rows="4"
                                    class="textarea w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                                >{{ old('overall_comment', $selectedSubmission->overall_comment) }}</textarea>
                            </div>

                            <button
                                type="submit"
                                class="inline-flex rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-300"
                                @disabled(!$canGrade)
                            >
                                Hoàn thành chấm bài
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </section>
@endsection
