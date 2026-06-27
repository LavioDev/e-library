@extends('layouts.library')

@section('title', 'Quản lý bộ câu hỏi')

@section('content')
    <section class="space-y-5">
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.assignments.index') }}" class="flex min-w-max flex-nowrap items-center gap-2 [&_label]:sr-only">
                    <input type="hidden" name="text_id" value="{{ $filters['text_id'] }}">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium text-slate-700">Tên bộ câu hỏi</label>
                        <input
                            id="keyword"
                            type="text"
                            name="keyword"
                            value="{{ $filters['keyword'] }}"
                            placeholder="Tìm theo tên bộ câu hỏi"
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                        />
                    </div>

                    <div class="w-72 shrink-0">
                        <label for="reading_class_id" class="block text-sm font-medium text-slate-700">Nhiệm vụ đọc hiểu</label>
                        <select id="reading_class_id" name="reading_class_id" class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none">
                            <option value="">Tất cả Nhiệm vụ đọc hiểu</option>
                            @foreach ($readingClasses as $readingClass)
                                <option value="{{ $readingClass->id }}" @selected($filters['reading_class_id'] === (string) $readingClass->id)>{{ $readingClass->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-44 shrink-0">
                        <label for="is_published" class="block text-sm font-medium text-slate-700">Trạng thái</label>
                        <select id="is_published" name="is_published" class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" @selected($filters['is_published'] === '1')>Đã xuất bản</option>
                            <option value="0" @selected($filters['is_published'] === '0')>Bản nháp</option>
                        </select>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                            Lọc
                        </button>
                        <a href="{{ route('admin.assignments.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                            Xóa lọc
                        </a>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none"
                            data-open-modal="create-assignment-modal"
                        >
                            Thêm bộ câu hỏi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <section class="overflow-hidden rounded-sm border border-slate-200 bg-white shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-slate-50 text-slate-600">
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
                            <tr>
                                <td>{{ $assignment->title }}</td>
                                <td>{{ $assignment->readingClass?->name }}</td>
                                <td class="whitespace-nowrap">
                                    <span class="rounded-sm px-2 py-1 text-xs font-semibold {{ $assignment->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $assignment->is_published ? 'Đã xuất bản' : 'Bản nháp' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap">{{ optional($assignment->open_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="whitespace-nowrap">{{ optional($assignment->due_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.assignments.questions.index', $assignment) }}" class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50">
                                            Câu hỏi
                                        </a>
                                        <a
                                            href="{{ route('admin.reading-classes.results', ['readingClass' => $assignment->reading_class_id, 'assignment_id' => $assignment->id]) }}"
                                            class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50"
                                        >
                                            Kết quả
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50"
                                            data-open-modal="edit-assignment-modal"
                                            data-assignment='@json($assignmentPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}" data-confirm-delete="true" data-confirm-message="Xóa bộ câu hỏi này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm rounded-sm border-0 bg-rose-600 text-white shadow-none hover:bg-rose-700">
                                                Xóa
                                            </button>
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

        <div>
            {{ $assignments->withQueryString()->links() }}
        </div>
    </section>

    <dialog id="create-assignment-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Thêm bộ câu hỏi</h2>
            </div>
            <form method="POST" action="{{ route('admin.assignments.store') }}">
                @csrf
                <div class="px-5 py-5">
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

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="create-assignment-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
                        Tạo bộ câu hỏi
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    <dialog id="edit-assignment-modal" class="modal">
        <div class="modal-box w-2xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Sửa bộ câu hỏi</h2>
            </div>
            <form method="POST" action="" id="edit-assignment-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5">
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

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="edit-assignment-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
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
