@extends('layouts.library')

@section('title', 'Quản lý loại văn bản')

@section('content')
    <section class="space-y-6">
        {{-- ─── FILTERS & CONTROLS ─── --}}
        <div class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.text-topics.index') }}" class="flex min-w-max flex-nowrap items-center gap-3 [&_label]:sr-only">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium">Tên loại văn bản</label>
                        <input
                            id="keyword"
                            type="text"
                            name="keyword"
                            value="{{ $filters['keyword'] }}"
                            placeholder="Tìm theo tên loại văn bản"
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        />
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                                style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                            Lọc
                        </button>
                        <a href="{{ route('admin.text-topics.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                           style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                           onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                            Xóa lọc
                        </a>
                        <a href="{{ route('admin.text-topics.export') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                           style="border-color: oklch(76% 0.040 155 / 0.4); background: oklch(99.4% 0.005 78); color: oklch(30% 0.060 155);"
                           onmouseover="this.style.background='oklch(95% 0.020 150)'" onmouseout="this.style.background=''">
                            <svg class="h-4 w-4 mr-1 inline-block shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Xuất Excel
                        </a>
                        <button
                            type="button"
                            class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-open-modal="import-text-topic-modal"
                        >
                            <svg class="h-4 w-4 mr-1 inline-block shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Nhập Excel
                        </button>
                        <button
                            type="button"
                            class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                            data-open-modal="create-text-topic-modal"
                        >
                            Thêm loại văn bản
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
                            <th>Tên loại văn bản</th>
                            <th>Số văn bản</th>
                            <th>Ngày tạo</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($textTopics as $textTopic)
                            @php
                                $textTopicPayload = [
                                    'id' => $textTopic->id,
                                    'name' => $textTopic->name,
                                    'texts_count' => $textTopic->texts_count,
                                    'created_at' => optional($textTopic->created_at)->format('d/m/Y H:i'),
                                    'destroy_url' => route('admin.text-topics.destroy', $textTopic),
                                    'edit_url' => route('admin.text-topics.update', $textTopic),
                                ];
                            @endphp
                            <tr style="border-bottom: 1px solid oklch(92% 0.016 74);">
                                <td class="font-semibold" style="color: oklch(18% 0.020 58);">{{ $textTopic->name }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ $textTopic->texts_count }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ optional($textTopic->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.texts.index', ['text_topic_id' => $textTopic->id]) }}" 
                                           class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                           style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                           onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                                            Các văn bản
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                            data-open-modal="edit-text-topic-modal"
                                            data-text-topic='@json($textTopicPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.text-topics.destroy', $textTopic) }}" data-confirm-delete="true" data-confirm-message="Xóa loại văn bản này?">
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
                                <td colspan="4" class="py-10 text-center text-slate-500">Chưa có loại văn bản nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ─── PAGINATION ─── --}}
        @if ($textTopics->lastPage() > 1)
            <div
                class="lib-pagi-wrap mt-6"
                data-pagination
                data-current-page="{{ $textTopics->currentPage() }}"
                data-last-page="{{ $textTopics->lastPage() }}"
                data-base-url="{{ url()->current() }}"
                data-param="page"
                data-window="2"
                aria-label="Phân trang"
            ></div>
        @endif
    </section>

    {{-- ─── CREATE MODAL ─── --}}
    <dialog id="create-text-topic-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Thêm loại văn bản</h2>
            </div>
            <form method="POST" action="{{ route('admin.text-topics.store') }}">
                @csrf
                <div class="px-5 py-5">
                    @include('text-topics._form_fields', [
                        'prefix' => 'create',
                        'name' => old('name', ''),
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="create-text-topic-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Tạo loại văn bản
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    {{-- ─── EDIT MODAL ─── --}}
    <dialog id="edit-text-topic-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Sửa loại văn bản</h2>
            </div>
            <form method="POST" action="" id="edit-text-topic-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5">
                    @include('text-topics._form_fields', [
                        'prefix' => 'edit',
                        'name' => '',
                        'textsCount' => '',
                        'createdAt' => '',
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="edit-text-topic-modal">
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

    {{-- ─── IMPORT MODAL ─── --}}
    <dialog id="import-text-topic-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Nhập loại văn bản từ Excel</h2>
            </div>
            <form method="POST" action="{{ route('admin.text-topics.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="px-5 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: oklch(34% 0.025 64);">Chọn file Excel/CSV (.xlsx, .xls, .csv)</label>
                        <input
                            type="file"
                            name="import_file"
                            accept=".xlsx,.xls,.csv"
                            required
                            class="file-input file-input-bordered w-full rounded-xl bg-white text-slate-800"
                            style="border-color: oklch(86% 0.020 72);"
                        />
                    </div>
                    <div class="text-sm p-4 rounded-xl border" style="background: oklch(97% 0.010 76); border-color: oklch(90% 0.018 74);">
                        <p class="font-semibold mb-1" style="color: oklch(18% 0.020 58);">Hướng dẫn:</p>
                        <ul class="list-disc pl-5 space-y-1" style="color: oklch(34% 0.025 64);">
                            <li>File Excel/CSV cần có cột đầu tiên là <strong>Tên loại văn bản</strong>.</li>
                            <li>Dòng đầu tiên của file được coi là tiêu đề cột và sẽ được bỏ qua khi nhập dữ liệu.</li>
                            <li>Tải file mẫu tại đây: <a href="{{ route('admin.text-topics.template') }}" class="font-semibold inline-flex items-center gap-0.5 hover:opacity-80" style="color: oklch(40% 0.068 54);">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Tải file mẫu
                            </a></li>
                        </ul>
                    </div>
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="import-text-topic-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Nhập dữ liệu
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
        const editTextTopicForm = document.getElementById('edit-text-topic-form');

        modalOpeners.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.openModal);

                if (!modal) {
                    return;
                }

                const textTopicPayload = button.dataset.textTopic ? JSON.parse(button.dataset.textTopic) : null;

                if (modal.id === 'edit-text-topic-modal' && textTopicPayload) {
                    editTextTopicForm.action = textTopicPayload.edit_url;
                    document.getElementById('edit_name').value = textTopicPayload.name;
                    document.getElementById('edit_texts_count').value = textTopicPayload.texts_count;
                    document.getElementById('edit_created_at').value = textTopicPayload.created_at;
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
