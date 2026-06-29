@extends('layouts.library')

@section('title', 'Quản lý bộ câu hỏi')

@section('content')
    <section class="space-y-6">
        {{-- ─── FILTERS & CONTROLS ─── --}}
        <div class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.assignments.index') }}" class="flex min-w-max flex-nowrap items-center gap-3 [&_label]:sr-only">
                    <input type="hidden" name="text_id" value="{{ $filters['text_id'] }}">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium">Tên bộ câu hỏi</label>
                        <input
                            id="keyword"
                            type="text"
                            name="keyword"
                            value="{{ $filters['keyword'] }}"
                            placeholder="Tìm theo tên bộ câu hỏi"
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        />
                    </div>

                    <div class="w-72 shrink-0">
                        <label for="reading_class_id" class="block text-sm font-medium">Nhiệm vụ đọc hiểu</label>
                        <select id="reading_class_id" name="reading_class_id" class="select select-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                                style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);">
                            <option value="">Tất cả Nhiệm vụ đọc hiểu</option>
                            @foreach ($readingClasses as $readingClass)
                                <option value="{{ $readingClass->id }}" @selected($filters['reading_class_id'] === (string) $readingClass->id)>{{ $readingClass->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-44 shrink-0">
                        <label for="is_published" class="block text-sm font-medium">Trạng thái</label>
                        <select id="is_published" name="is_published" class="select select-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                                style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" @selected($filters['is_published'] === '1')>Đã xuất bản</option>
                            <option value="0" @selected($filters['is_published'] === '0')>Bản nháp</option>
                        </select>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                                style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                            Lọc
                        </button>
                        <a href="{{ route('admin.assignments.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                           style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                           onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                            Xóa lọc
                        </a>
                        <button
                            type="button"
                            class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                            data-open-modal="create-assignment-modal"
                        >
                            Thêm bộ câu hỏi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── TABLE SECTION ─── --}}
        <section class="overflow-hidden rounded-2xl border shadow-sm" style="border-color: oklch(89% 0.018 72);">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead style="background: oklch(97% 0.010 76); color: oklch(30% 0.022 60); border-bottom: 1px solid oklch(89% 0.018 72);">
                        <tr>
                            <th>Tên bộ câu hỏi</th>
                            <th>Nhiệm vụ đọc hiểu</th>
                            <th class="whitespace-nowrap">Trạng thái</th>
                            <th class="whitespace-nowrap">Thời gian mở</th>
                            <th class="whitespace-nowrap">Hạn nộp</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $assignment)
                            @php
                                $assignmentPayload = [
                                    'id' => $assignment->id,
                                    'reading_class_id' => $assignment->reading_class_id,
                                    'title' => $assignment->title,
                                    'description' => $assignment->description,
                                    'open_at_for_input' => optional($assignment->open_at)->format('Y-m-d\TH:i'),
                                    'due_at_for_input' => optional($assignment->due_at)->format('Y-m-d\TH:i'),
                                    'open_at' => optional($assignment->open_at)->format('d/m/Y H:i'),
                                    'due_at' => optional($assignment->due_at)->format('d/m/Y H:i'),
                                    'is_published' => (bool) $assignment->is_published,
                                    'questions_count' => $assignment->questions_count,
                                    'created_at' => optional($assignment->created_at)->format('d/m/Y H:i'),
                                    'edit_url' => route('admin.assignments.update', $assignment),
                                ];
                            @endphp
                            <tr style="border-bottom: 1px solid oklch(92% 0.016 74);">
                                <td class="font-semibold" style="color: oklch(18% 0.020 58);">{{ $assignment->title }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ $assignment->readingClass?->name }}</td>
                                <td class="whitespace-nowrap">
                                    <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block"
                                          style="{{ $assignment->is_published ? 'background: oklch(52% 0.090 155 / 0.15); color: oklch(30% 0.070 155);' : 'background: oklch(72% 0.090 42 / 0.15); color: oklch(38% 0.080 42);' }}">
                                        {{ $assignment->is_published ? 'Đã xuất bản' : 'Bản nháp' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap" style="color: oklch(34% 0.025 64);">{{ optional($assignment->open_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="whitespace-nowrap" style="color: oklch(34% 0.025 64);">{{ optional($assignment->due_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.assignments.questions.index', $assignment) }}" 
                                           class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                           style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                           onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                                            Câu hỏi
                                        </a>
                                        <a
                                            href="{{ route('admin.reading-classes.results', ['readingClass' => $assignment->reading_class_id, 'assignment_id' => $assignment->id]) }}"
                                            class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                        >
                                            Kết quả
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                            data-open-modal="edit-assignment-modal"
                                            data-assignment='@json($assignmentPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}" data-confirm-delete="true" data-confirm-message="Xóa bộ câu hỏi này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm rounded-xl border-0 px-3 text-white shadow-none" style="background: oklch(58% 0.140 24);">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-500">Chưa có bộ câu hỏi nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ─── PAGINATION ─── --}}
        @if ($assignments->lastPage() > 1)
            <div
                class="lib-pagi-wrap mt-6"
                data-pagination
                data-current-page="{{ $assignments->currentPage() }}"
                data-last-page="{{ $assignments->lastPage() }}"
                data-base-url="{{ url()->current() }}"
                data-param="page"
                data-window="2"
                aria-label="Phân trang"
            ></div>
        @endif
    </section>

    {{-- ─── CREATE MODAL ─── --}}
    <dialog id="create-assignment-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Thêm bộ câu hỏi</h2>
            </div>
            <form method="POST" action="{{ route('admin.assignments.store') }}">
                @csrf
                <div class="px-5 py-5 max-h-[60vh] overflow-y-auto">
                    @include('assignments._form_fields', [
                        'prefix' => 'create',
                        'readingClasses' => $readingClasses,
                        'readingClassId' => old('reading_class_id', ''),
                        'title' => old('title', ''),
                        'description' => old('description', ''),
                        'openAt' => old('open_at', ''),
                        'dueAt' => old('due_at', ''),
                        'isPublished' => old('is_published', false),
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="create-assignment-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Tạo bộ câu hỏi
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    {{-- ─── EDIT MODAL ─── --}}
    <dialog id="edit-assignment-modal" class="modal">
        <div class="modal-box w-2xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Sửa bộ câu hỏi</h2>
            </div>
            <form method="POST" action="" id="edit-assignment-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5 max-h-[60vh] overflow-y-auto">
                    @include('assignments._form_fields', [
                        'prefix' => 'edit',
                        'readingClasses' => $readingClasses,
                        'readingClassId' => '',
                        'title' => '',
                        'description' => '',
                        'openAt' => '',
                        'dueAt' => '',
                        'isPublished' => false,
                        'questionsCount' => '',
                        'createdAt' => '',
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="edit-assignment-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>
@endsection

@push('scripts')
    <script>
        const modalOpeners = document.querySelectorAll('[data-open-modal]');
        const modalClosers = document.querySelectorAll('[data-close-modal]');
        const editAssignmentForm = document.getElementById('edit-assignment-form');

        modalOpeners.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.openModal);

                if (!modal) {
                    return;
                }

                const assignmentPayload = button.dataset.assignment ? JSON.parse(button.dataset.assignment) : null;

                if (modal.id === 'edit-assignment-modal' && assignmentPayload) {
                    editAssignmentForm.action = assignmentPayload.edit_url;
                    document.getElementById('edit_reading_class_id').value = assignmentPayload.reading_class_id;
                    document.getElementById('edit_title').value = assignmentPayload.title;
                    document.getElementById('edit_description').value = assignmentPayload.description ?? '';
                    document.getElementById('edit_open_at').value = assignmentPayload.open_at_for_input ?? '';
                    document.getElementById('edit_due_at').value = assignmentPayload.due_at_for_input ?? '';
                    document.getElementById('edit_is_published').checked = Boolean(assignmentPayload.is_published);
                    document.getElementById('edit_questions_count').value = assignmentPayload.questions_count ?? 0;
                    document.getElementById('edit_created_at').value = assignmentPayload.created_at ?? '';
                }

                modal.showModal();
            });
        });

        modalClosers.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.closeModal);

                if (modal) {
                    modal.close();
                }
            });
        });
    </script>
@endpush

@push('scripts')
<script src="{{ asset('js/library/pagination.js') }}"></script>
@endpush
