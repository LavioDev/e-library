@extends('layouts.library')

@section('title', 'Quản lý loại văn bản')

@section('content')
    <section class="space-y-5">
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.text-topics.index') }}" class="flex min-w-max flex-nowrap items-center gap-2 [&_label]:sr-only">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium text-slate-700">Tên loại văn bản</label>
                        <input
                            id="keyword"
                            type="text"
                            name="keyword"
                            value="{{ $filters['keyword'] }}"
                            placeholder="Tìm theo tên loại văn bản"
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                        />
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                            Lọc
                        </button>
                        <a href="{{ route('admin.text-topics.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                            Xóa lọc
                        </a>
                        <a href="{{ route('admin.text-topics.export') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-emerald-700 shadow-none hover:bg-emerald-50 hover:border-emerald-200">
                            <svg class="h-4 w-4 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Xuất Excel
                        </a>
                        <button
                            type="button"
                            class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50"
                            data-open-modal="import-text-topic-modal"
                        >
                            <svg class="h-4 w-4 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Nhập Excel
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none"
                            data-open-modal="create-text-topic-modal"
                        >
                            Thêm loại văn bản
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
                            <tr>
                                <td class="font-medium text-slate-900">{{ $textTopic->name }}</td>
                                <td>{{ $textTopic->texts_count }}</td>
                                <td>{{ optional($textTopic->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.texts.index', ['text_topic_id' => $textTopic->id]) }}" class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50">
                                            Các văn bản
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50"
                                            data-open-modal="edit-text-topic-modal"
                                            data-text-topic='@json($textTopicPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.text-topics.destroy', $textTopic) }}" data-confirm-delete="true" data-confirm-message="Xóa loại văn bản này?">
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
                                <td colspan="4" class="py-10 text-center text-slate-500">Chưa có loại văn bản nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $textTopics->withQueryString()->links() }}
        </div>
    </section>

    <dialog id="create-text-topic-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Thêm loại văn bản</h2>
            </div>
            <form method="POST" action="{{ route('admin.text-topics.store') }}">
                @csrf
                <div class="px-5 py-5">
                    @include('text-topics._form_fields', [
                        'prefix' => 'create',
                        'name' => old('name', ''),
                    ])
                </div>

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="create-text-topic-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
                        Tạo loại văn bản
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    <dialog id="edit-text-topic-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Sửa loại văn bản</h2>
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

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="edit-text-topic-modal">
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

    <dialog id="import-text-topic-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Nhập loại văn bản từ Excel</h2>
            </div>
            <form method="POST" action="{{ route('admin.text-topics.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="px-5 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Chọn file Excel/CSV (.xlsx, .xls, .csv)</label>
                        <input
                            type="file"
                            name="import_file"
                            accept=".xlsx,.xls,.csv"
                            required
                            class="file-input file-input-bordered w-full rounded-sm border border-slate-200 bg-white text-slate-800"
                        />
                    </div>
                    <div class="text-sm text-slate-500 bg-slate-50 p-4 rounded-sm border border-slate-100">
                        <p class="font-semibold text-slate-700 mb-1">Hướng dẫn:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>File Excel/CSV cần có cột đầu tiên là <strong>Tên loại văn bản</strong>.</li>
                            <li>Dòng đầu tiên của file được coi là tiêu đề cột và sẽ được bỏ qua khi nhập dữ liệu.</li>
                            <li>Tải file mẫu tại đây: <a href="{{ route('admin.text-topics.template') }}" class="text-primary hover:underline font-semibold inline-flex items-center gap-0.5">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Tải file mẫu
                            </a></li>
                        </ul>
                    </div>
                </div>

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="import-text-topic-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
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
