@extends('layouts.library')

@section('title', 'Kết quả Nhiệm vụ đọc hiểu')

@section('content')
    <section class="space-y-6">
        {{-- ─── FILTERS & HEADER INFO ─── --}}
        <div class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold uppercase tracking-wider" style="color: oklch(18% 0.020 58);">{{ $readingClass->name }}</h1>
                    <p class="mt-1 text-sm font-serif italic" style="color: oklch(46% 0.018 58);">
                        Văn bản: <span class="font-sans font-semibold not-italic" style="color: oklch(34% 0.025 64);">{{ $readingClass->texts->pluck('name')->implode(', ') ?: 'Chưa gắn văn bản' }}</span>
                    </p>
                </div>

                <form method="GET" class="flex flex-nowrap items-center gap-3">
                    <label for="assignment_id" class="text-sm font-medium whitespace-nowrap" style="color: oklch(34% 0.025 64);">Bộ câu hỏi</label>
                    <select id="assignment_id" name="assignment_id" onchange="this.form.submit()" class="select select-sm !h-10 min-h-10 w-80 rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);">
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
            <div class="rounded-2xl border p-6 text-sm shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72); color: oklch(46% 0.018 58);">
                Nhiệm vụ đọc hiểu này chưa có bộ câu hỏi để xem kết quả.
            </div>
        @else
            @php
                $assignmentMaxScore = (float) $selectedAssignment->questions->sum('max_score');
                $assignmentQuestionCount = (int) $selectedAssignment->questions_count;
            @endphp

            <div class="flex gap-6">
                {{-- ─── STUDENT LIST (LEFT COLUMN) ─── --}}
                <div class="h-fit overflow-hidden rounded-2xl border shadow-sm" style="border-color: oklch(89% 0.018 72); width: 40%; flex-shrink: 0; background: white;">
                    <div class="px-4 py-3 border-b" style="background: oklch(97% 0.010 76); border-color: oklch(89% 0.018 72);">
                        <h2 class="text-sm font-bold uppercase tracking-wider" style="color: oklch(18% 0.020 58);">Danh sách học sinh</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead style="background: oklch(97% 0.010 76); color: oklch(30% 0.022 60); border-bottom: 1px solid oklch(89% 0.018 72);">
                                <tr>
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
                                        $isActive = (int) $selectedSubmission?->student_id === (int) $student->id;
                                    @endphp
                                    <tr style="border-bottom: 1px solid oklch(92% 0.016 74); background: {{ $isActive ? 'oklch(96.5% 0.012 70) !important' : 'white !important' }};">
                                        <td>
                                            <a
                                                href="{{ route('admin.reading-classes.results', ['readingClass' => $readingClass->id, 'assignment_id' => $selectedAssignment->id, 'student_id' => $student->id]) }}"
                                                class="block font-semibold hover:opacity-80 transition"
                                                style="color: oklch(18% 0.020 58);"
                                            >
                                                {{ \Illuminate\Support\Str::title((string) $student->name) }}
                                            </a>
                                            <p class="text-xs" style="color: oklch(34% 0.025 64);">{{ $student->email }}</p>
                                        </td>
                                        <td class="w-fit whitespace-nowrap">
                                            @if ($status === 'graded')
                                                <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block"
                                                      style="background: oklch(52% 0.090 155 / 0.15); color: oklch(30% 0.070 155);">Đã chấm</span>
                                            @elseif ($status === 'submitted')
                                                <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block"
                                                      style="background: oklch(62% 0.090 240 / 0.15); color: oklch(35% 0.080 240);">Đã nộp</span>
                                            @elseif ($status === 'draft')
                                                <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block"
                                                      style="background: oklch(72% 0.090 42 / 0.15); color: oklch(38% 0.080 42);">Nháp</span>
                                            @else
                                                <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block"
                                                      style="background: oklch(80% 0.010 70 / 0.2); color: oklch(46% 0.018 58);">Chưa làm</span>
                                            @endif
                                        </td>
                                        <td class="text-xs whitespace-nowrap" style="color: oklch(34% 0.025 64);">
                                            @if ($submission && $submission->submitted_at)
                                                {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right text-sm font-bold" style="color: oklch(18% 0.020 58);">
                                            @if ($correctCount !== null)
                                                <span class="block text-xs font-medium">{{ $correctCount }}/{{ $assignmentQuestionCount }} câu</span>
                                                <span class="block text-xs font-bold" style="color: oklch(40% 0.068 54);">{{ $scaledScore !== null ? number_format($scaledScore, 2) . '/10' : '-' }}</span>
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

                {{-- ─── SUBMISSION DETAIL & GRADING (RIGHT COLUMN) ─── --}}
                <div class="min-w-0 rounded-2xl border p-5 shadow-sm" style="flex: 1; background: white; border-color: oklch(89% 0.018 72);">
                    @if ($selectedSubmission === null)
                        <p class="text-sm text-slate-500 font-serif italic">Chọn học sinh có bài nộp để xem và chấm điểm.</p>
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

                        <div class="flex flex-wrap items-start justify-between gap-4 pb-4 border-b" style="border-color: oklch(90% 0.018 74);">
                            <div>
                                <h2 class="text-xl font-bold" style="color: oklch(18% 0.020 58);">
                                    Bài làm: {{ $studentName !== '' ? \Illuminate\Support\Str::title($studentName) : ($selectedSubmission->student?->email ?? 'N/A') }}
                                </h2>
                                <p class="mt-1 text-xs" style="color: oklch(34% 0.025 64);">
                                    Trạng thái:
                                    <span class="font-bold" style="color: oklch(18% 0.020 58);">{{ $selectedSubmission->status === 'graded' ? 'Đã chấm' : ($selectedSubmission->status === 'submitted' ? 'Đã nộp' : 'Bản nháp') }}</span>
                                    • Lần làm {{ $selectedSubmission->attempt_no }}
                                </p>
                            </div>
                            <div class="rounded-xl border px-4 py-2.5 text-sm" 
                                 style="border-color: oklch(62% 0.090 240 / 0.3); background: oklch(95% 0.020 240 / 0.5); color: oklch(35% 0.080 240);">
                                <p class="font-semibold">Đúng: {{ $correctCount }}/{{ $totalQuestions }} câu</p>
                                <p class="mt-0.5 font-bold">Điểm số: {{ $scaledScore !== null ? number_format($scaledScore, 2) . '/10' : 'Chưa chấm' }}</p>
                            </div>
                        </div>

                        @if (!$canGrade)
                            <div class="mt-4 rounded-xl border px-4 py-3 text-sm"
                                 style="background: oklch(72% 0.090 42 / 0.1); border-color: oklch(72% 0.090 42 / 0.25); color: oklch(38% 0.080 42);">
                                Bài này chưa nộp chính thức nên chưa thể chấm điểm.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.reading-classes.results.grade', ['readingClass' => $readingClass, 'submission' => $selectedSubmission]) }}" class="mt-6 space-y-6">
                            @csrf
                            @foreach ($selectedSubmission->answers->sortBy(fn ($answer) => $answer->question?->position ?? 0)->values() as $index => $answer)
                                @php
                                    $question = $answer->question;
                                @endphp
                                <article class="rounded-xl border p-4 transition hover:shadow-sm" style="background: oklch(99% 0.005 78); border-color: oklch(89% 0.018 72);">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h3 class="font-bold text-sm" style="color: oklch(18% 0.020 58);">Câu {{ $question?->position }}: {{ $question?->prompt }}</h3>
                                        <p class="text-xs font-semibold" style="color: oklch(34% 0.025 64);">Tối đa {{ rtrim(rtrim(number_format((float) ($question?->max_score ?? 0), 2, '.', ''), '0'), '.') }} điểm</p>
                                    </div>

                                    <div class="mt-3 rounded-xl border p-3 text-sm" style="background: oklch(97% 0.010 76); border-color: oklch(90% 0.018 74); color: oklch(20% 0.022 60);">
                                        @if ($question?->type === 'multiple_choice')
                                            <p><span class="font-bold">Đáp án chọn:</span> {{ $answer->selected_answer ?? 'Chưa trả lời' }}</p>
                                        @elseif ($question?->type === 'text_input')
                                            <p class="whitespace-pre-line"><span class="font-bold">Trả lời:</span> {{ $answer->text_answer ?: 'Chưa trả lời' }}</p>
                                        @elseif ($question?->type === 'file_input')
                                            <p class="font-bold">Tệp nộp:</p>
                                            <div class="mt-2 space-y-2">
                                                @forelse ($answer->files as $file)
                                                    <div class="flex items-center justify-between gap-2 rounded-xl border bg-white px-3 py-2" style="border-color: oklch(90% 0.018 74);">
                                                        <span class="truncate text-sm font-medium" style="color: oklch(20% 0.022 60);">{{ $file->original_name }}</span>
                                                        <a href="{{ route('admin.reading-classes.results.files.download', ['readingClass' => $readingClass, 'file' => $file]) }}" 
                                                           class="text-xs font-bold hover:opacity-80 transition" style="color: oklch(40% 0.068 54);">Tải xuống</a>
                                                    </div>
                                                @empty
                                                    <p class="text-sm font-serif italic" style="color: oklch(46% 0.018 58);">Không có tệp.</p>
                                                @endforelse
                                            </div>
                                        @endif
                                    </div>

                                    <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question?->id }}">
                                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                                        <div>
                                            <label class="mb-1 block text-xs font-bold" style="color: oklch(34% 0.025 64);">Điểm</label>
                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                max="{{ (float) ($question?->max_score ?? 0) }}"
                                                name="answers[{{ $index }}][score]"
                                                value="{{ old("answers.$index.score", $answer->score) }}"
                                                class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                                                style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                                            >
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="mb-1 block text-xs font-bold" style="color: oklch(34% 0.025 64);">Nhận xét</label>
                                            <input
                                                type="text"
                                                name="answers[{{ $index }}][comment]"
                                                value="{{ old("answers.$index.comment", $answer->comment) }}"
                                                class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                                                style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                                            >
                                        </div>
                                    </div>
                                </article>
                            @endforeach

                            <div class="pt-2">
                                <label for="overall_comment" class="mb-1 block text-xs font-bold" style="color: oklch(34% 0.025 64);">Nhận xét tổng quan</label>
                                <textarea
                                    id="overall_comment"
                                    name="overall_comment"
                                    rows="4"
                                    class="textarea w-full rounded-xl border text-sm shadow-none focus:outline-none"
                                    style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                                >{{ old('overall_comment', $selectedSubmission->overall_comment) }}</textarea>
                            </div>

                            <button
                                type="submit"
                                class="btn btn-sm !h-10 min-h-10 rounded-xl border px-5 text-white shadow-none transition"
                                style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                                onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
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
