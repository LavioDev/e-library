@extends('layouts.library')

@section('title', 'Quản lý văn bản')

@section('content')
    <section class="space-y-5">
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.texts.index') }}" class="flex min-w-max flex-nowrap items-center gap-2 [&_label]:sr-only">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium text-slate-700">Tên, chủ đề hoặc tác giả</label>
                        <input id="keyword" type="text" name="keyword" value="{{ $filters['keyword'] }}" placeholder="Tìm theo tên, chủ đề hoặc tác giả" class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none" />
                    </div>

                    <div class="w-56 shrink-0">
                        <label for="text_topic_id" class="block text-sm font-medium text-slate-700">Loại văn bản</label>
                        <select id="text_topic_id" name="text_topic_id" class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none">
                            <option value="">Tất cả loại văn bản</option>
                            @foreach ($textTopics as $textTopic)
                                <option value="{{ $textTopic->id }}" @selected($filters['text_topic_id'] === (string) $textTopic->id)>{{ $textTopic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-40 shrink-0">
                        <label for="difficulty" class="block text-sm font-medium text-slate-700">Mức độ</label>
                        <select id="difficulty" name="difficulty" class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none">
                            <option value="">Tất cả mức độ</option>
                            <option value="easy" @selected($filters['difficulty'] === 'easy')>Dễ</option>
                            <option value="medium" @selected($filters['difficulty'] === 'medium')>Trung bình</option>
                            <option value="hard" @selected($filters['difficulty'] === 'hard')>Khó</option>
                        </select>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">Lọc</button>
                        <a href="{{ route('admin.texts.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">Xóa lọc</a>
                        <a href="{{ route('admin.texts.export', request()->query()) }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-emerald-700 shadow-none hover:border-emerald-200 hover:bg-emerald-50">
                            <svg class="mr-1 inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Xuất Excel
                        </a>
                        <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-open-modal="import-text-modal">
                            <svg class="mr-1 inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Nhập từ Excel
                        </button>
                        <button type="button" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none" data-open-modal="create-text-modal">Thêm văn bản</button>
                    </div>
                </form>
            </div>
        </div>

        <section class="overflow-hidden rounded-sm border border-slate-200 bg-white shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th>Tên văn bản</th>
                            <th>Chủ đề</th>
                            <th>Loại văn bản</th>
                            <th>Tác giả</th>
                            <th>Mức độ</th>
                            <th>Link đọc</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($texts as $text)
                            @php
                                $textPayload = [
                                    'id' => $text->id,
                                    'text_topic_id' => $text->text_topic_id,
                                    'topic' => $text->topic,
                                    'name' => $text->name,
                                    'author' => $text->author,
                                    'difficulty' => $text->difficulty,
                                    'read_link' => $text->read_link,
                                    'edit_url' => route('admin.texts.update', $text),
                                ];
                            @endphp
                            <tr>
                                <td class="font-medium text-slate-900">{{ $text->name }}</td>
                                <td>{{ $text->topic ?? '-' }}</td>
                                <td>{{ $text->textTopic?->name }}</td>
                                <td>{{ $text->author }}</td>
                                <td>
                                    <span class="rounded-sm px-2 py-1 text-xs font-semibold {{ $text->difficulty === 'hard' ? 'bg-rose-50 text-rose-700' : ($text->difficulty === 'medium' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700') }}">
                                        {{ $text->difficulty === 'easy' ? 'Dễ' : ($text->difficulty === 'medium' ? 'Trung bình' : 'Khó') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($text->read_link)
                                        <a href="{{ $text->read_link }}" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-blue-600 underline underline-offset-2 hover:text-blue-700">Mở link</a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.assignments.index', ['text_id' => $text->id]) }}" class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50">
                                            Nhiệm vụ đọc hiểu
                                        </a>
                                        <a href="{{ route('admin.texts.writer.edit', $text) }}" class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50">
                                            Viết
                                        </a>
                                        <button type="button" class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50" data-open-modal="edit-text-modal" data-text='@json($textPayload)'>
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.texts.destroy', $text) }}" data-confirm-delete="true" data-confirm-message="Xóa văn bản này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm rounded-sm border-0 bg-rose-600 text-white shadow-none hover:bg-rose-700">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-500">Chưa có văn bản nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>{{ $texts->withQueryString()->links() }}</div>
    </section>

    <dialog id="create-text-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Thêm văn bản</h2>
            </div>
            <form method="POST" action="{{ route('admin.texts.store') }}">
                @csrf
                <div class="px-5 py-5">
                    @include('texts._form_fields', ['prefix' => 'create', 'textTopics' => $textTopics, 'textTopicId' => old('text_topic_id', ''), 'topic' => old('topic', ''), 'name' => old('name', ''), 'author' => old('author', ''), 'difficulty' => old('difficulty', ''), 'readLink' => old('read_link', '')])
                </div>

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="create-text-modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">Tạo văn bản</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button aria-label="close" class="sr-only">close</button></form>
    </dialog>

    <dialog id="edit-text-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Sửa văn bản</h2>
            </div>
            <form method="POST" action="" id="edit-text-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5">
                    @include('texts._form_fields', ['prefix' => 'edit', 'textTopics' => $textTopics, 'textTopicId' => '', 'topic' => '', 'name' => '', 'author' => '', 'difficulty' => '', 'readLink' => ''])
                </div>

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="edit-text-modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">Lưu thay đổi</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button aria-label="close" class="sr-only">close</button></form>
    </dialog>

    <dialog id="import-text-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Nhập văn bản từ Excel</h2>
            </div>
            <form method="POST" action="{{ route('admin.texts.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 px-5 py-5">
                    <div>
                        <label for="import_text_topic_id" class="mb-2 block text-sm font-medium text-slate-700">Loại văn bản <span class="text-rose-500">*</span></label>
                        <select id="import_text_topic_id" name="text_topic_id" required class="select select-bordered w-full rounded-sm border border-slate-200 bg-white text-slate-800">
                            <option value="">Chọn loại văn bản</option>
                            @foreach ($textTopics as $textTopic)
                                <option value="{{ $textTopic->id }}">{{ $textTopic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Chọn file Excel/CSV (.xlsx, .xls, .csv) <span class="text-rose-500">*</span></label>
                        <input
                            type="file"
                            name="import_file"
                            accept=".xlsx,.xls,.csv"
                            required
                            class="file-input file-input-bordered w-full rounded-sm border border-slate-200 bg-white text-slate-800"
                        />
                    </div>

                    <div class="rounded-sm border border-slate-100 bg-slate-50 p-4 text-sm text-slate-500">
                        <p class="mb-1 font-semibold text-slate-700">Hướng dẫn:</p>
                        <ul class="list-disc space-y-1 pl-5">
                            <li>File Excel/CSV cần có các cột theo thứ tự: <strong>Tên văn bản</strong>, <strong>Chủ đề</strong>, <strong>Tác giả</strong>, <strong>Mức độ (Dễ/Trung bình/Khó)</strong>, <strong>Link đọc (Không bắt buộc)</strong>.</li>
                            <li>Dòng đầu tiên của file được coi là tiêu đề cột và sẽ được bỏ qua khi nhập dữ liệu.</li>
                            <li>Tải file mẫu tại đây:
                                <a href="{{ route('admin.texts.template') }}" class="inline-flex items-center gap-0.5 font-semibold text-primary hover:underline">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Tải file mẫu
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="import-text-modal">
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
        const editTextForm = document.getElementById('edit-text-form');

        modalOpeners.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.openModal);
                if (!modal) return;

                const textPayload = button.dataset.text ? JSON.parse(button.dataset.text) : null;
                if (modal.id === 'edit-text-modal' && textPayload) {
                    editTextForm.action = textPayload.edit_url;
                    document.getElementById('edit_text_topic_id').value = textPayload.text_topic_id;
                    document.getElementById('edit_topic').value = textPayload.topic ?? '';
                    document.getElementById('edit_name').value = textPayload.name;
                    document.getElementById('edit_author').value = textPayload.author;
                    document.getElementById('edit_difficulty').value = textPayload.difficulty;
                    document.getElementById('edit_read_link').value = textPayload.read_link ?? '';
                }

                modal.showModal();
            });
        });

        modalClosers.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.closeModal);
                if (modal) modal.close();
            });
        });
    </script>
@endpush
