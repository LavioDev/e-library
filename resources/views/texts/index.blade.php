@extends('layouts.library')

@section('title', 'Quản lý văn bản')

@section('content')
    <section class="space-y-6">
        {{-- ─── FILTERS & CONTROLS ─── --}}
        <div class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.texts.index') }}" class="flex min-w-max flex-nowrap items-center gap-3 [&_label]:sr-only">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium">Tên, chủ đề hoặc tác giả</label>
                        <input id="keyword" type="text" name="keyword" value="{{ $filters['keyword'] }}" placeholder="Tìm theo tên, chủ đề hoặc tác giả" 
                               class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                               style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);" />
                    </div>

                    <div class="w-56 shrink-0">
                        <label for="text_topic_id" class="block text-sm font-medium">Loại văn bản</label>
                        <select id="text_topic_id" name="text_topic_id" class="select select-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                                style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);">
                            <option value="">Tất cả loại văn bản</option>
                            @foreach ($textTopics as $textTopic)
                                <option value="{{ $textTopic->id }}" @selected($filters['text_topic_id'] === (string) $textTopic->id)>{{ $textTopic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-40 shrink-0">
                        <label for="difficulty" class="block text-sm font-medium">Mức độ</label>
                        <select id="difficulty" name="difficulty" class="select select-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                                style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);">
                            <option value="">Tất cả mức độ</option>
                            <option value="easy" @selected($filters['difficulty'] === 'easy')>Dễ</option>
                            <option value="medium" @selected($filters['difficulty'] === 'medium')>Trung bình</option>
                            <option value="hard" @selected($filters['difficulty'] === 'hard')>Khó</option>
                        </select>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                                style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Lọc</button>
                        <a href="{{ route('admin.texts.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                           style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                           onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Xóa lọc</a>
                        <a href="{{ route('admin.texts.export', request()->query()) }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                           style="border-color: oklch(80% 0.080 155 / 0.3); background: oklch(96% 0.022 155 / 0.5); color: oklch(34% 0.072 155);"
                           onmouseover="this.style.background='oklch(93% 0.035 155 / 0.5)'" onmouseout="this.style.background=''">
                            <svg class="mr-1 inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Xuất Excel
                        </a>
                        <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                                style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                data-open-modal="import-text-modal">
                            <svg class="mr-1 inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Nhập từ Excel
                        </button>
                        <button type="button" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-xl border-0 px-4 text-white shadow-none" 
                                style="background: oklch(40% 0.068 54);"
                                onmouseover="this.style.background='oklch(34% 0.072 52)'" onmouseout="this.style.background=''"
                                data-open-modal="create-text-modal">Thêm văn bản</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── TABLE VIEW ─── --}}
        <section class="overflow-hidden rounded-2xl border shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead style="background: oklch(97% 0.010 76); color: oklch(30% 0.022 60); border-bottom: 1px solid oklch(89% 0.018 72);">
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
                            <tr style="border-bottom: 1px solid oklch(92% 0.016 74);">
                                <td class="font-semibold" style="color: oklch(18% 0.020 58);">{{ $text->name }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ $text->topic ?? '-' }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ $text->textTopic?->name }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ $text->author }}</td>
                                <td>
                                    <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block"
                                          style="{{ $text->difficulty === 'hard' ? 'background: oklch(72% 0.090 42 / 0.15); color: oklch(38% 0.080 42);' : ($text->difficulty === 'medium' ? 'background: oklch(55% 0.080 230 / 0.15); color: oklch(30% 0.060 230);' : 'background: oklch(52% 0.090 155 / 0.15); color: oklch(30% 0.070 155);') }}">
                                        {{ $text->difficulty === 'easy' ? 'Dễ' : ($text->difficulty === 'medium' ? 'Trung bình' : 'Khó') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($text->read_link)
                                        <a href="{{ $text->read_link }}" target="_blank" rel="noopener noreferrer" class="font-medium underline underline-offset-4 hover:opacity-80" style="color: oklch(40% 0.068 54);">Mở link</a>
                                    @else
                                        <span style="color: oklch(62% 0.020 68);">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.assignments.index', ['text_id' => $text->id]) }}" class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                           style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                           onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                                            Nhiệm vụ đọc hiểu
                                        </a>
                                        <a href="{{ route('admin.texts.writer.edit', $text) }}" class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                           style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                           onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                                            Viết
                                        </a>
                                        <button type="button" class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                                style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                                onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                                data-open-modal="edit-text-modal" data-text='@json($textPayload)'>
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.texts.destroy', $text) }}" data-confirm-delete="true" data-confirm-message="Xóa văn bản này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm rounded-xl border-0 px-3 text-white shadow-none" style="background: oklch(58% 0.140 24);">Xóa</button>
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

        @if ($texts->lastPage() > 1)
            <div
                class="lib-pagi-wrap mt-6"
                data-pagination
                data-current-page="{{ $texts->currentPage() }}"
                data-last-page="{{ $texts->lastPage() }}"
                data-base-url="{{ url()->current() }}"
                data-param="page"
                data-window="2"
                aria-label="Phân trang"
            ></div>
        @endif
    </section>

    {{-- ─── CREATE MODAL ─── --}}
    <dialog id="create-text-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Thêm văn bản</h2>
            </div>
            <form method="POST" action="{{ route('admin.texts.store') }}">
                @csrf
                <div class="px-5 py-5">
                    @include('texts._form_fields', ['prefix' => 'create', 'textTopics' => $textTopics, 'textTopicId' => old('text_topic_id', ''), 'topic' => old('topic', ''), 'name' => old('name', ''), 'author' => old('author', ''), 'difficulty' => old('difficulty', ''), 'readLink' => old('read_link', '')])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-9 min-h-9 rounded-lg px-4 shadow-none" style="background: oklch(95% 0.012 75); border: 1px solid oklch(86% 0.020 72); color: oklch(36% 0.025 62);" data-close-modal="create-text-modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm !h-9 min-h-9 rounded-lg border-0 px-4 text-white shadow-none" style="background: oklch(40% 0.068 54);">Tạo văn bản</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button aria-label="close" class="sr-only">close</button></form>
    </dialog>

    {{-- ─── EDIT MODAL ─── --}}
    <dialog id="edit-text-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Sửa văn bản</h2>
            </div>
            <form method="POST" action="" id="edit-text-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5">
                    @include('texts._form_fields', ['prefix' => 'edit', 'textTopics' => $textTopics, 'textTopicId' => '', 'topic' => '', 'name' => '', 'author' => '', 'difficulty' => '', 'readLink' => ''])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-9 min-h-9 rounded-lg px-4 shadow-none" style="background: oklch(95% 0.012 75); border: 1px solid oklch(86% 0.020 72); color: oklch(36% 0.025 62);" data-close-modal="edit-text-modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm !h-9 min-h-9 rounded-lg border-0 px-4 text-white shadow-none" style="background: oklch(40% 0.068 54);">Lưu thay đổi</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button aria-label="close" class="sr-only">close</button></form>
    </dialog>

    {{-- ─── IMPORT MODAL ─── --}}
    <dialog id="import-text-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Nhập văn bản từ Excel</h2>
            </div>
            <form method="POST" action="{{ route('admin.texts.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 px-5 py-5">
                    <div>
                        <label for="import_text_topic_id" class="mb-2 block text-sm font-medium">Loại văn bản <span class="text-rose-500">*</span></label>
                        <select id="import_text_topic_id" name="text_topic_id" required class="select select-bordered w-full rounded-xl"
                                style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);">
                            <option value="">Chọn loại văn bản</option>
                            @foreach ($textTopics as $textTopic)
                                <option value="{{ $textTopic->id }}">{{ $textTopic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">Chọn file Excel/CSV (.xlsx, .xls, .csv) <span class="text-rose-500">*</span></label>
                        <input
                            type="file"
                            name="import_file"
                            accept=".xlsx,.xls,.csv"
                            required
                            class="file-input file-input-bordered w-full rounded-xl"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        />
                    </div>

                    <div class="rounded-xl border p-4 text-xs md:text-sm leading-relaxed"
                         style="background: oklch(97% 0.010 76); border-color: oklch(90% 0.018 74); color: oklch(45% 0.025 65);">
                        <p class="mb-1 font-semibold" style="color: oklch(18% 0.020 58);">Hướng dẫn:</p>
                        <ul class="list-disc space-y-1 pl-5">
                            <li>File Excel/CSV cần có các cột theo thứ tự: <strong>Tên văn bản</strong>, <strong>Chủ đề</strong>, <strong>Tác giả</strong>, <strong>Mức độ (Dễ/Trung bình/Khó)</strong>, <strong>Link đọc (Không bắt buộc)</strong>.</li>
                            <li>Dòng đầu tiên của file được coi là tiêu đề cột và sẽ được bỏ qua khi nhập dữ liệu.</li>
                            <li>Tải file mẫu tại đây:
                                <a href="{{ route('admin.texts.template') }}" class="inline-flex items-center gap-0.5 font-bold transition hover:opacity-85" style="color: oklch(40% 0.068 54);">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Tải file mẫu
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-9 min-h-9 rounded-lg px-4 shadow-none" style="background: oklch(95% 0.012 75); border: 1px solid oklch(86% 0.020 72); color: oklch(36% 0.025 62);" data-close-modal="import-text-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-9 min-h-9 rounded-lg border-0 px-4 text-white shadow-none" style="background: oklch(40% 0.068 54);">
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

@push('scripts')
<script src="{{ asset('js/library/pagination.js') }}"></script>
@endpush
