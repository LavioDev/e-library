@extends('layouts.library')

@section('title', 'Nhiệm vụ đọc hiểu')

@section('content')
    <section class="space-y-5">
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.reading-classes.index') }}" class="flex min-w-max flex-nowrap items-center gap-2 [&_label]:sr-only">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium text-slate-700">Tên Nhiệm vụ đọc hiểu</label>
                        <input
                            id="keyword"
                            type="text"
                            name="keyword"
                            value="{{ $filters['keyword'] }}"
                            placeholder="Tìm theo tên Nhiệm vụ đọc hiểu"
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                        />
                    </div>

                    <div class="w-72 shrink-0">
                        <label for="text_id" class="block text-sm font-medium text-slate-700">Văn bản</label>
                        <select id="text_id" name="text_id" class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none">
                            <option value="">Tất cả văn bản</option>
                            @foreach ($texts as $text)
                                <option value="{{ $text->id }}" @selected($filters['text_id'] === (string) $text->id)>{{ $text->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                            Lọc
                        </button>
                        <a href="{{ route('admin.reading-classes.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                            Xóa lọc
                        </a>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none"
                            data-open-modal="create-reading-class-modal"
                        >
                            Thêm Nhiệm vụ đọc hiểu
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
                            <tr>
                                <td class="font-medium text-slate-900">{{ $readingClass->name }}</td>
                                <td>{{ $readingClass->texts->pluck('name')->implode(', ') ?: 'Chưa gắn văn bản' }}</td>
                                <td>{{ $readingClass->assignments_count }}</td>
                                <td>{{ optional($readingClass->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('admin.assignments.index', ['reading_class_id' => $readingClass->id]) }}"
                                            class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50"
                                        >
                                            Bộ câu hỏi
                                        </a>
                                        <a
                                            href="{{ route('admin.reading-classes.results', $readingClass) }}"
                                            class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50"
                                        >
                                            Kết quả
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50"
                                            data-open-modal="edit-reading-class-modal"
                                            data-reading-class='@json($readingClassPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.reading-classes.destroy', $readingClass) }}" data-confirm-delete="true" data-confirm-message="Xóa Nhiệm vụ đọc hiểu này?">
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
                                <td colspan="5" class="py-10 text-center text-slate-500">Chưa có Nhiệm vụ đọc hiểu nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $readingClasses->withQueryString()->links() }}
        </div>
    </section>

    <dialog id="create-reading-class-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Thêm Nhiệm vụ đọc hiểu</h2>
            </div>
            <form method="POST" action="{{ route('admin.reading-classes.store') }}">
                @csrf
                <div class="px-5 py-5">
                    @include('reading-classes._form_fields', [
                        'prefix' => 'create',
                        'texts' => $texts,
                        'users' => $users,
                        'name' => old('name', ''),
                        'selectedTextIds' => old('text_ids', []),
                        'selectedUserIds' => old('user_ids', []),
                    ])
                </div>

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="create-reading-class-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
                        Tạo Nhiệm vụ đọc hiểu
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    <dialog id="edit-reading-class-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Sửa Nhiệm vụ đọc hiểu</h2>
            </div>
            <form method="POST" action="" id="edit-reading-class-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5">
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

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="edit-reading-class-modal">
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
