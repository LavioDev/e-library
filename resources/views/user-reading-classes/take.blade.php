@extends('layouts.library')

@section('title', 'Làm bộ câu hỏi: ' . $assignment->title)

@section('breadcrumbs')
    <nav class="mb-4 text-sm text-slate-600">
        <ol class="flex flex-wrap items-center gap-2">
            <li><a href="{{ route('home') }}" class="hover:text-slate-900">Trang chủ</a></li>
            <li aria-hidden="true">&gt;</li>
            <li><a href="{{ route('user.reading-classes.index') }}" class="hover:text-slate-900">Nhiệm vụ đọc hiểu của tôi</a></li>
            <li aria-hidden="true">&gt;</li>
            <li><a href="{{ route('user.reading-classes.show', $class) }}" class="hover:text-slate-900">{{ $class->name }}</a></li>
            <li aria-hidden="true">&gt;</li>
            <li class="font-medium text-slate-900">{{ $assignment->title }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <section class="max-w-3xl mx-auto space-y-6">
        @php
            $isReadOnly = $submission->status !== 'draft';
        @endphp

        {{-- Card Thông Tin Bài Tập --}}
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-lg font-bold text-slate-900">{{ $assignment->title }}</h1>
                    <p class="text-xs text-slate-500">
                        Hạn nộp: <span class="font-medium text-slate-700">{{ $assignment->due_at?->format('H:i d/m/Y') ?? 'Không giới hạn' }}</span>
                        <span class="text-slate-300 mx-2">·</span>
                        Trạng thái bài làm: 
                        <span class="font-semibold text-blue-600">
                            @if ($submission->status === 'draft')
                                Đang làm nháp
                            @elseif ($submission->status === 'submitted')
                                Đã nộp bài
                            @else
                                Đã chấm điểm
                            @endif
                        </span>
                    </p>
                </div>
                <div>
                    <a
                        href="{{ route('user.reading-classes.show', $class) }}"
                        class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50 text-sm"
                    >
                        Quay lại nhóm
                    </a>
                </div>
            </div>
        </div>

        <form
            id="assignment-take-form"
            action="{{ route('user.reading-classes.assignments.save', ['readingClass' => $class->id, 'assignment' => $assignment->id]) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf

            <div class="space-y-4">
                @foreach ($submission->assignment->questions as $index => $question)
                    @php
                        $answer = $submission->answers->firstWhere('question_id', $question->id);
                    @endphp
                    <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)] space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <h3 class="text-sm font-semibold text-slate-800">
                                    Câu {{ $index + 1 }}: <span class="font-normal">{{ $question->prompt }}</span>
                                </h3>
                                <p class="text-xs text-slate-400">
                                    Điểm tối đa: {{ rtrim(rtrim((string) $question->max_score, '0'), '.') }} điểm
                                </p>
                            </div>
                        </div>

                        {{-- Kiểu câu hỏi --}}
                        <div class="pt-2">
                            @if ($question->type === 'multiple_choice')
                                <div class="grid gap-2">
                                    @foreach ($question->options_json ?? [] as $optIndex => $option)
                                        <label class="flex items-center gap-3 rounded-sm border border-slate-100 bg-slate-50/50 p-3 hover:bg-slate-50 transition-colors cursor-pointer text-sm">
                                            <input
                                                type="radio"
                                                name="answers[{{ $question->id }}][selected_answer]"
                                                value="{{ $option }}"
                                                class="radio radio-primary radio-sm border-slate-300"
                                                @checked($answer?->selected_answer === $option)
                                                @disabled($isReadOnly)
                                            />
                                            <span class="text-slate-700">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif ($question->type === 'text_input')
                                <textarea
                                    name="answers[{{ $question->id }}][text_answer]"
                                    rows="4"
                                    placeholder="Nhập câu trả lời của bạn tại đây..."
                                    class="textarea textarea-sm w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none focus:outline-none"
                                    @disabled($isReadOnly)
                                >{{ $answer?->text_answer }}</textarea>

                            @elseif ($question->type === 'file_input')
                                <div class="space-y-2">
                                    @if ($answer && $answer->files->isNotEmpty())
                                        <div class="rounded-sm border border-slate-200 bg-slate-50 px-4 py-3 flex items-center justify-between text-xs">
                                            <div class="flex items-center gap-2 text-slate-700 font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span>{{ $answer->files->first()->original_name }}</span>
                                            </div>
                                            <span class="text-slate-400">({{ number_format($answer->files->first()->size / 1024, 1) }} KB)</span>
                                        </div>
                                    @endif

                                    @if (!$isReadOnly)
                                        <input
                                            type="file"
                                            name="answers[{{ $question->id }}][file]"
                                            class="file-input file-input-bordered file-input-sm w-full rounded-sm text-xs bg-white border-slate-200"
                                        />
                                        <p class="text-[10px] text-slate-400">Định dạng chấp nhận: Tệp văn bản, hình ảnh hoặc PDF</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Hiển thị kết quả chấm điểm nếu đã chấm --}}
                        @if ($submission->status === 'graded' && $answer)
                            <div class="mt-4 border-t border-slate-100 pt-3 flex flex-wrap justify-between items-center gap-2 text-xs">
                                <span class="font-medium text-slate-700">
                                    Điểm đạt được: <span class="font-bold text-blue-600">{{ rtrim(rtrim((string) $answer->score, '0'), '.') }}</span> / {{ rtrim(rtrim((string) $question->max_score, '0'), '.') }}
                                </span>
                                @if ($answer->comment)
                                    <div class="w-full bg-blue-50/50 border border-blue-100 rounded-sm p-2.5 text-blue-800 mt-2">
                                        <span class="font-semibold block mb-0.5">Nhận xét của giáo viên:</span>
                                        {{ $answer->comment }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Nút Thao Tác Cuối Trang --}}
            @if (!$isReadOnly)
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button
                        type="submit"
                        name="action"
                        value="save"
                        class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-5 text-slate-700 shadow-none hover:bg-slate-50 text-sm"
                    >
                        Lưu nháp
                    </button>
                    <button
                        type="button"
                        id="submit-assignment-btn"
                        class="btn btn-sm !h-10 min-h-10 rounded-sm border-0 bg-blue-600 px-6 text-white shadow-none hover:bg-blue-700 text-sm"
                    >
                        Nộp bài
                    </button>
                </div>
            @else
                @if ($submission->status === 'graded' && $submission->overall_comment)
                    <div class="rounded-sm border border-blue-200 bg-blue-50 p-5 space-y-2">
                        <h4 class="text-sm font-semibold text-blue-900">Nhận xét tổng thể của giáo viên:</h4>
                        <p class="text-xs text-blue-800 leading-relaxed">{{ $submission->overall_comment }}</p>
                    </div>
                @endif
            @endif
        </form>

        {{-- Custom DaisyUI Modal for Submit Confirmation --}}
        <dialog id="confirm-submit-modal" class="modal">
            <div class="modal-box max-w-md rounded-sm bg-white p-0 shadow-2xl border border-slate-200">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Xác nhận nộp bài</h3>
                </div>
                <div class="px-5 py-5 text-sm text-slate-600 space-y-2">
                    <p>Bạn có chắc chắn muốn nộp bộ câu hỏi này?</p>
                    <p class="font-medium text-blue-600">Lưu ý: Sau khi nộp, bạn sẽ không thể chỉnh sửa câu trả lời của mình nữa.</p>
                </div>
                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button
                        type="button"
                        class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50 text-sm"
                        id="confirm-submit-cancel"
                    >
                        Hủy
                    </button>
                    <button
                        type="button"
                        class="btn btn-sm !h-10 min-h-10 rounded-sm border-0 bg-blue-600 px-4 text-white shadow-none hover:bg-blue-700 text-sm"
                        id="confirm-submit-ok"
                    >
                        Nộp bài
                    </button>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button aria-label="close" class="sr-only">close</button>
            </form>
        </dialog>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('assignment-take-form');
            const submitTriggerBtn = document.getElementById('submit-assignment-btn');
            const modal = document.getElementById('confirm-submit-modal');
            const cancelBtn = document.getElementById('confirm-submit-cancel');
            const okBtn = document.getElementById('confirm-submit-ok');

            if (submitTriggerBtn && modal && cancelBtn && okBtn && form) {
                submitTriggerBtn.addEventListener('click', () => {
                    modal.showModal();
                });

                cancelBtn.addEventListener('click', () => {
                    modal.close();
                });

                okBtn.addEventListener('click', () => {
                    // Create a hidden action input
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'action';
                    hiddenInput.value = 'submit';
                    form.appendChild(hiddenInput);

                    modal.close();
                    form.submit();
                });
            }
        });
    </script>
@endpush
