@extends('layouts.library')

@section('title', 'Nhiệm vụ đọc hiểu')

@section('content')
    <section class="space-y-6">
        {{-- ─── FILTERS & CONTROLS ─── --}}
        <div class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.reading-classes.index') }}" class="flex min-w-max flex-nowrap items-center gap-3 [&_label]:sr-only">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium">Tên Nhiệm vụ đọc hiểu</label>
                        <input
                            id="keyword"
                            type="text"
                            name="keyword"
                            value="{{ $filters['keyword'] }}"
                            placeholder="Tìm theo tên Nhiệm vụ đọc hiểu"
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        />
                    </div>

                    <div class="w-72 shrink-0">
                        <label for="text_id" class="block text-sm font-medium">Văn bản</label>
                        <select id="text_id" name="text_id" class="select select-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                                style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);">
                            <option value="">Tất cả văn bản</option>
                            @foreach ($texts as $text)
                                <option value="{{ $text->id }}" @selected($filters['text_id'] === (string) $text->id)>{{ $text->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                                style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                            Lọc
                        </button>
                        <a href="{{ route('admin.reading-classes.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                           style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                           onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                            Xóa lọc
                        </a>
                        <button
                            type="button"
                            class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                            data-open-modal="create-reading-class-modal"
                        >
                            Thêm Nhiệm vụ đọc hiểu
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
                            <th>Tên Nhiệm vụ đọc hiểu</th>
                            <th>Văn bản</th>
                            <th>Số bộ câu hỏi</th>
                            <th>Ngày tạo</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($readingClasses as $readingClass)
                            @php
                                $readingClassPayload = [
                                    'id' => $readingClass->id,
                                    'name' => $readingClass->name,
                                    'text_ids' => $readingClass->texts->pluck('id')->map(fn ($id) => (int) $id)->values()->all(),
                                    'assignments_count' => $readingClass->assignments_count,
                                    'user_ids' => $readingClass->users->pluck('id')->map(fn ($id) => (int) $id)->values()->all(),
                                    'created_at' => optional($readingClass->created_at)->format('d/m/Y H:i'),
                                    'edit_url' => route('admin.reading-classes.update', $readingClass),
                                ];
                            @endphp
                            <tr style="border-bottom: 1px solid oklch(92% 0.016 74);">
                                <td class="font-semibold" style="color: oklch(18% 0.020 58);">{{ $readingClass->name }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ $readingClass->texts->pluck('name')->implode(', ') ?: 'Chưa gắn văn bản' }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ $readingClass->assignments_count }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ optional($readingClass->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('admin.assignments.index', ['reading_class_id' => $readingClass->id]) }}"
                                            class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                        >
                                            Bộ câu hỏi
                                        </a>
                                        <a
                                            href="{{ route('admin.reading-classes.results', $readingClass) }}"
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
                                            data-open-modal="edit-reading-class-modal"
                                            data-reading-class='@json($readingClassPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.reading-classes.destroy', $readingClass) }}" data-confirm-delete="true" data-confirm-message="Xóa Nhiệm vụ đọc hiểu này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                                    style="border-color: oklch(76% 0.080 42 / 0.25); background: oklch(99.4% 0.005 78); color: oklch(38% 0.080 42);"
                                                    onmouseover="this.style.background='oklch(95% 0.020 40)'" onmouseout="this.style.background=''">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-500">Chưa có Nhiệm vụ đọc hiểu nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ─── PAGINATION ─── --}}
        @if ($readingClasses->lastPage() > 1)
            <div
                class="lib-pagi-wrap mt-6"
                data-pagination
                data-current-page="{{ $readingClasses->currentPage() }}"
                data-last-page="{{ $readingClasses->lastPage() }}"
                data-base-url="{{ url()->current() }}"
                data-param="page"
                data-window="2"
                aria-label="Phân trang"
            ></div>
        @endif
    </section>

    {{-- ─── CREATE MODAL ─── --}}
    <dialog id="create-reading-class-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Thêm Nhiệm vụ đọc hiểu</h2>
            </div>
            <form method="POST" action="{{ route('admin.reading-classes.store') }}">
                @csrf
                <div class="px-5 py-5 max-h-[60vh] overflow-y-auto">
                    @include('reading-classes._form_fields', [
                        'prefix' => 'create',
                        'texts' => $texts,
                        'users' => $users,
                        'name' => old('name', ''),
                        'selectedTextIds' => old('text_ids', []),
                        'selectedUserIds' => old('user_ids', []),
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="create-reading-class-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Tạo Nhiệm vụ đọc hiểu
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    {{-- ─── EDIT MODAL ─── --}}
    <dialog id="edit-reading-class-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Sửa Nhiệm vụ đọc hiểu</h2>
            </div>
            <form method="POST" action="" id="edit-reading-class-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5 max-h-[60vh] overflow-y-auto">
                    @include('reading-classes._form_fields', [
                        'prefix' => 'edit',
                        'texts' => $texts,
                        'users' => $users,
                        'name' => '',
                        'selectedTextIds' => [],
                        'selectedUserIds' => [],
                        'createdAt' => '',
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="edit-reading-class-modal">
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
        const editReadingClassForm = document.getElementById('edit-reading-class-form');

        const setUserCheckboxValues = (prefix, values) => {
            const selected = (values ?? []).map((value) => String(value));
            const checkboxes = document.querySelectorAll(`input[type="checkbox"][data-user-checkbox="${prefix}"]`);

            checkboxes.forEach((checkbox) => {
                checkbox.checked = selected.includes(checkbox.value);
            });
        };

        const setTextCheckboxValues = (prefix, values) => {
            const selected = (values ?? []).map((value) => String(value));
            const checkboxes = document.querySelectorAll(`input[type="checkbox"][data-text-checkbox="${prefix}"]`);

            checkboxes.forEach((checkbox) => {
                checkbox.checked = selected.includes(checkbox.value);
            });
        };

        modalOpeners.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.openModal);

                if (!modal) {
                    return;
                }

                const readingClassPayload = button.dataset.readingClass ? JSON.parse(button.dataset.readingClass) : null;

                if (modal.id === 'edit-reading-class-modal' && readingClassPayload) {
                    editReadingClassForm.action = readingClassPayload.edit_url;
                    document.getElementById('edit_name').value = readingClassPayload.name;
                    document.getElementById('edit_created_at').value = readingClassPayload.created_at;
                    setUserCheckboxValues('edit', readingClassPayload.user_ids);
                    setTextCheckboxValues('edit', readingClassPayload.text_ids);
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
