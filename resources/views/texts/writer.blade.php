@extends('layouts.library')

@section('title', 'Xem văn bản')

@section('content')
    <section class="space-y-6">
        <div class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="mb-4">
                <h1 class="text-xl font-semibold" style="color: oklch(18% 0.020 58);">Xem văn bản</h1>
                <p class="text-sm" style="color: oklch(50% 0.025 65);">{{ $text->name }} • {{ $text->author }}</p>
            </div>

            <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
                <div class="flex-1 min-w-[250px]">
                    <label class="mb-1 block text-sm font-medium" style="color: oklch(30% 0.022 60);">Tiêu đề văn bản</label>
                    <input type="text" value="{{ $document->title ?? $text->name }}" class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none" style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);" readonly />
                </div>
                <div class="shrink-0">
                    <p class="mb-1 text-sm font-medium" style="color: oklch(30% 0.022 60);">Nhập/Xuất</p>
                    <div class="flex items-center gap-2">
                        <form id="writer_import_form" action="{{ route('admin.texts.writer.import-docx', $text) }}" method="POST" enctype="multipart/form-data" class="m-0">
                            @csrf
                            <input id="writer_import_file" type="file" name="import_file" accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="hidden" />
                            <button type="button" id="writer_import_button" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition" style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Nhập DOCX</button>
                        </form>
                        <a href="{{ route('admin.texts.writer.export-docx', $text) }}" id="writer_export_button" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition" style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62); display: inline-flex; align-items: center; justify-content: center;" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Xuất DOCX</a>
                    </div>
                </div>
            </div>

            @if (!empty($previewError))
                <div class="mb-4 rounded-sm border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    {{ $previewError }}
                </div>
            @endif

            <div>
                <label class="mb-1.5 block text-sm font-medium" style="color: oklch(30% 0.022 60);">Nội dung văn bản (Preview)</label>
                <div class="flex items-center gap-2 mb-2">
                    <input type="url" id="link_input" placeholder="Nhập link YouTube hoặc Google Drive" class="input input-sm !h-10 min-h-10 w-full rounded-xl border shadow-none" style="border-color: oklch(86% 0.020 72); background: oklch(99.8% 0.003 75); color: oklch(20% 0.022 60);" />
                    <button type="button" id="add_link_btn" class="btn btn-sm !h-10 min-h-10 w-28 rounded-xl border shadow-none px-0 shrink-0 transition" style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Thêm link</button>
                </div>
                <!-- Form tải lên file trực tiếp -->
                <form id="upload-file-ajax-form" class="flex items-center gap-2 mb-4">
                    <div class="flex-1 relative">
                        <!-- Hidden input file -->
                        <input type="file" id="file_upload_input" class="hidden" accept="image/*,video/*,audio/*" />
                        <!-- Text placeholder to look like the link input -->
                        <input type="text" id="file_name_display" placeholder="Chọn tệp đính kèm (Ảnh, Video, Âm thanh, ...)" class="input input-sm !h-10 min-h-10 w-full rounded-xl border cursor-pointer shadow-none" style="border-color: oklch(86% 0.020 72); background: oklch(99.8% 0.003 75); color: oklch(20% 0.022 60);" readonly />
                    </div>
                    <button type="button" id="add_file_btn" class="btn btn-sm !h-10 min-h-10 w-28 rounded-xl border shadow-none px-0 shrink-0 transition" style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Thêm file</button>
                </form>

                <div id="links-preview" class="space-y-6 mb-6">
                    @foreach($text->textLinks as $link)
                        @php
                            $isYoutube = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/live\/)([a-zA-Z0-9_-]{11})/', $link->url, $ytMatch);
                            $isDrive = preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $link->url, $driveMatch);
                        @endphp
                        @if($isYoutube || $isDrive)
                            <div class="media-card group rounded-lg transition-all duration-300" data-id="{{ $link->id }}">
                                <div class="embed-container w-full">
                                    @if($isYoutube)
                                        <div class="relative w-full rounded-lg overflow-hidden border border-slate-200 shadow-sm" style="aspect-ratio: 16/9; width: 100%;">
                                            <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src="https://www.youtube.com/embed/{{ $ytMatch[1] }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    @elseif($isDrive)
                                        @if($link->getDriveType() === 'video')
                                            <div class="relative w-full rounded-lg overflow-hidden border border-slate-200 shadow-sm" style="aspect-ratio: 16/9; width: 100%;">
                                                <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src="https://drive.google.com/file/d/{{ $driveMatch[1] }}/preview" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                        @else
                                            <img src="https://drive.google.com/thumbnail?id={{ $driveMatch[1] }}&sz=w1600" alt="Google Drive Image" class="w-full h-auto rounded-lg border border-slate-200 shadow-sm" style="display: block; width: 100%; height: auto;" />
                                        @endif
                                    @endif
                                </div>
                                <div class="mt-2 flex justify-end">
                                     <button type="button" class="delete-link-btn inline-flex items-center rounded-xl px-3 py-1.5 text-xs font-semibold text-white shadow transition hover:opacity-90" style="background: oklch(58% 0.140 24);" data-id="{{ $link->id }}" title="Gỡ bỏ">
                                         Xóa
                                     </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="writer-preview min-h-[60vh] overflow-auto rounded-xl border p-5 md:p-8"
                     style="background: oklch(99.4% 0.005 78); border-color: oklch(89% 0.018 72); font-family: var(--font-serif); font-size: 1.05rem; line-height: 1.8; color: oklch(20% 0.022 60);">
                    {!! $previewHtml !!}
                </div>

                <!-- Tài liệu đính kèm / File đính kèm -->
                <div class="mt-6 pt-6" style="border-top: 1px solid oklch(91% 0.016 74);">
                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-medium" style="color: oklch(30% 0.022 60);">Tài liệu đính kèm (Ảnh, Video, Âm thanh)</label>
                        <p class="text-xs" style="color: oklch(50% 0.025 65);">Quản lý các file đa phương tiện đính kèm của văn bản này</p>
                    </div>

                    <!-- Danh sách các file đã tải lên -->
                    <div id="attached-files-list" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($text->textFiles as $file)
                            <div class="flex items-center justify-between rounded-xl border p-3" style="background: oklch(98.5% 0.005 78 / 0.5); border-color: oklch(90% 0.018 74);" data-file-id="{{ $file->id }}">
                                <div class="min-w-0 flex-1 pr-3">
                                    <p class="truncate text-sm font-semibold" style="color: oklch(24% 0.020 58);" title="{{ $file->file_name }}">
                                        {{ $file->file_name }}
                                    </p>
                                    <div class="mt-1 flex items-center gap-2 text-xs" style="color: oklch(50% 0.025 65);">
                                        <span class="rounded bg-slate-200 px-1.5 py-0.5 font-bold uppercase" style="background: oklch(92% 0.015 72); color: oklch(40% 0.022 62);">
                                            {{ $file->file_type }}
                                        </span>
                                        @if($file->file_size)
                                            <span>•</span>
                                            <span>{{ number_format($file->file_size / 1024, 1) }} KB</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0 text-xs font-bold">
                                    <a href="{{ route('texts.files.serve', ['text' => $text, 'filename' => basename($file->file_path)]) }}" target="_blank" style="color: oklch(40% 0.068 54);">
                                        Xem
                                    </a>
                                    <span style="color: oklch(89% 0.018 72);">|</span>
                                    <button type="button" class="delete-file-btn" style="color: oklch(58% 0.140 24);" data-file-id="{{ $file->id }}">
                                        Xóa
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div id="comments" class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="mb-4">
                <h2 class="text-lg font-semibold" style="color: oklch(18% 0.020 58);">Bình luận thảo luận</h2>
                <p class="text-xs" style="color: oklch(50% 0.025 65);">{{ $comments->count() }} bình luận</p>
            </div>

            <div class="space-y-3">
                @forelse ($comments as $comment)
                    <article class="rounded-xl border p-4" style="background: oklch(99.4% 0.005 78); border-color: oklch(90% 0.018 74);">
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold uppercase"
                                 style="background: oklch(92% 0.022 72); color: oklch(34% 0.042 60);">
                                {{ \Illuminate\Support\Str::of($comment->user->name)->substr(0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center justify-between gap-2 border-b pb-1.5" style="border-color: oklch(92% 0.016 74);">
                                    <p class="text-xs font-bold" style="color: oklch(18% 0.020 58);">{{ $comment->user->name }}</p>
                                    <div class="flex items-center gap-3">
                                        <p class="text-[0.65rem]" style="color: oklch(50% 0.025 65);">{{ $comment->created_at?->format('H:i d/m/Y') }}</p>
                                        @auth
                                            @if (
                                                auth()->user()->role === 'teacher'
                                                || (auth()->user()->role === 'user' && (string) $comment->user_id === (string) auth()->id())
                                            )
                                                <form method="POST" action="{{ route('texts.comments.destroy', [$text, $comment]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[0.65rem] font-bold transition hover:opacity-85" style="color: oklch(58% 0.140 24);">
                                                        Xóa
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <p class="mt-2 whitespace-pre-line text-xs md:text-sm leading-relaxed" style="color: oklch(30% 0.022 60);">{{ $comment->content }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed p-6 text-center text-xs md:text-sm"
                         style="background: oklch(99.4% 0.005 78 / 0.4); border-color: oklch(85% 0.020 72); color: oklch(50% 0.025 65);">
                        Chưa có ý kiến thảo luận nào.
                    </div>
                @endforelse
            </div>
        </div>
    <!-- Glassmorphic Loading Overlay HTML -->
    <div id="docx-loading-overlay" aria-live="assertive" aria-label="Đang xử lý">
        <div class="docx-loading-card">
            <div class="docx-spinner-wrapper">
                <div class="docx-spinner-outer"></div>
                <div class="docx-spinner-inner"></div>
            </div>
            <h3 id="docx-loading-title" class="text-lg font-semibold mb-2" style="color:oklch(99.4% 0.005 78);">Đang xử lý</h3>
            <p id="docx-loading-text" class="text-sm" style="color:oklch(80% 0.015 72);">Vui lòng chờ trong giây lát...</p>
        </div>
    </div>

    <!-- Modal xác nhận xóa link -->
    <dialog id="delete_link_modal" class="modal">
        <div class="modal-box relative p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h3 class="font-bold text-lg" style="color: oklch(18% 0.020 58);">Xác nhận gỡ bỏ</h3>
            </div>
            <div class="px-5 py-4 text-sm" style="color: oklch(44% 0.025 64);">
                <p>Bạn có chắc chắn muốn gỡ bỏ liên kết này khỏi văn bản không?</p>
            </div>
            <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                <form method="dialog" class="m-0 flex gap-2">
                    <button class="btn btn-sm !h-9 min-h-9 rounded-lg px-4 shadow-none transition" style="background: oklch(95% 0.012 75); border: 1px solid oklch(86% 0.020 72); color: oklch(36% 0.025 62);">Hủy</button>
                    <button id="confirm_delete_link_btn" type="button" class="btn btn-error btn-sm !h-9 min-h-9 rounded-lg border-0 px-4 text-white shadow-none" style="background: oklch(58% 0.140 24);">Gỡ bỏ</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Modal xác nhận xóa file -->
    <dialog id="delete_file_modal" class="modal">
        <div class="modal-box relative p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h3 class="font-bold text-lg" style="color: oklch(18% 0.020 58);">Xác nhận xóa file</h3>
            </div>
            <div class="px-5 py-4 text-sm" style="color: oklch(44% 0.025 64);">
                <p>Bạn có chắc chắn muốn xóa file đính kèm này không?</p>
            </div>
            <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                <form method="dialog" class="m-0 flex gap-2">
                    <button class="btn btn-sm !h-9 min-h-9 rounded-lg px-4 shadow-none transition" style="background: oklch(95% 0.012 75); border: 1px solid oklch(86% 0.020 72); color: oklch(36% 0.025 62);">Hủy</button>
                    <button id="confirm_delete_file_btn" type="button" class="btn btn-error btn-sm !h-9 min-h-9 rounded-lg border-0 px-4 text-white shadow-none" style="background: oklch(58% 0.140 24);">Xóa</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</section>
@endsection

@push('styles')
    <style>
        .writer-preview table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.75rem 0;
        }

        .writer-preview th,
        .writer-preview td {
            border: 1px solid #cbd5e1;
            padding: 0.4rem 0.5rem;
            vertical-align: top;
        }

        .writer-preview p,
        .writer-preview li,
        .writer-preview td,
        .writer-preview th {
            line-height: 1.55;
        }

        .writer-preview img {
            max-width: 100%;
            height: auto;
        }

        /* Modern Glassmorphic Loading Overlay */
        #docx-loading-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(8px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #docx-loading-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .docx-loading-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 1.25rem;
            padding: 2.5rem;
            max-width: 380px;
            width: calc(100% - 2rem);
            margin: 1rem;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transform: scale(0.95);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #docx-loading-overlay.active .docx-loading-card {
            transform: scale(1);
        }

        .docx-spinner-wrapper {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
        }

        .docx-spinner-outer {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 4px solid oklch(40% 0.068 54 / 0.15);
            border-top-color: oklch(40% 0.068 54);
            border-right-color: oklch(40% 0.068 54);
            animation: docx-spin 0.8s linear infinite;
        }

        .docx-spinner-inner {
            position: absolute;
            inset: 12px;
            border-radius: 50%;
            border: 3px solid oklch(62% 0.040 66 / 0.15);
            border-bottom-color: oklch(62% 0.040 66);
            border-left-color: oklch(62% 0.040 66);
            animation: docx-spin-reverse 1.2s linear infinite;
        }

        @keyframes docx-spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes docx-spin-reverse {
            to {
                transform: rotate(-360deg);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const importButton = document.getElementById('writer_import_button');
            const importInput = document.getElementById('writer_import_file');
            const importForm = document.getElementById('writer_import_form');
            const exportButton = document.getElementById('writer_export_button');
            const loadingOverlay = document.getElementById('docx-loading-overlay');
            const loadingTitle = document.getElementById('docx-loading-title');
            const loadingText = document.getElementById('docx-loading-text');

            if (!loadingOverlay) {
                return;
            }

            const showLoading = (title, text) => {
                if (loadingTitle) loadingTitle.textContent = title;
                if (loadingText) loadingText.textContent = text;
                loadingOverlay.classList.add('active');
            };

            const hideLoading = () => {
                loadingOverlay.classList.remove('active');
            };

            if (importButton && importInput && importForm) {
                importButton.addEventListener('click', () => {
                    importInput.click();
                });

                importInput.addEventListener('change', () => {
                    if (!importInput.files || !importInput.files.length) {
                        return;
                    }

                    showLoading('Đang nhập dữ liệu', 'Vui lòng chờ trong giây lát trong khi hệ thống đang xử lý và phân tích tệp DOCX...');
                    importForm.submit();
                });
            }

            if (exportButton) {
                exportButton.addEventListener('click', () => {
                    showLoading('Đang xuất dữ liệu', 'Hệ thống đang khởi tạo và tải xuống tệp DOCX của bạn...');
                    
                    // Since file download won't trigger a page navigation/reload,
                    // we hide the loading screen after a safe duration.
                    setTimeout(() => {
                        hideLoading();
                    }, 4000);
                });
            }

            // Quản lý file đính kèm
            const fileUploadInput = document.getElementById('file_upload_input');
            const fileNameDisplay = document.getElementById('file_name_display');
            const addFileBtn = document.getElementById('add_file_btn');
            const attachedFilesList = document.getElementById('attached-files-list');
            const deleteFileModal = document.getElementById('delete_file_modal');
            const confirmDeleteFileBtn = document.getElementById('confirm_delete_file_btn');
            let currentDeleteFileAction = null;

            if (confirmDeleteFileBtn && deleteFileModal) {
                confirmDeleteFileBtn.addEventListener('click', async () => {
                    const originalText = confirmDeleteFileBtn.textContent;
                    confirmDeleteFileBtn.textContent = 'Đang xóa...';
                    confirmDeleteFileBtn.disabled = true;
                    if (currentDeleteFileAction) {
                        await currentDeleteFileAction();
                        currentDeleteFileAction = null;
                    }
                    confirmDeleteFileBtn.textContent = originalText;
                    confirmDeleteFileBtn.disabled = false;
                    deleteFileModal.close();
                });
            }

            const getFileHtml = (file) => {
                const sizeKb = file.file_size ? (file.file_size / 1024).toFixed(1) + ' KB' : '0 KB';
                const serveUrl = `{{ route('texts.files.serve', ['text' => $text, 'filename' => ':filename']) }}`.replace(':filename', file.file_path.split('/').pop());
                return `
                    <div class="flex items-center justify-between rounded-xl border p-3" style="background: oklch(98.5% 0.005 78 / 0.5); border-color: oklch(90% 0.018 74);" data-file-id="${file.id}">
                        <div class="min-w-0 flex-1 pr-3">
                            <p class="truncate text-sm font-semibold" style="color: oklch(24% 0.020 58);" title="${file.file_name}">
                                ${file.file_name}
                            </p>
                            <div class="mt-1 flex items-center gap-2 text-xs" style="color: oklch(50% 0.025 65);">
                                <span class="rounded bg-slate-200 px-1.5 py-0.5 font-bold uppercase" style="background: oklch(92% 0.015 72); color: oklch(40% 0.022 62);">
                                    ${file.file_type}
                                </span>
                                <span>•</span>
                                <span>${sizeKb}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0 text-xs font-bold">
                            <a href="${serveUrl}" target="_blank" style="color: oklch(40% 0.068 54);">
                                Xem
                            </a>
                            <span style="color: oklch(89% 0.018 72);">|</span>
                             <button type="button" class="delete-file-btn hover:opacity-85" style="color: oklch(58% 0.140 24);" data-file-id="${file.id}">
                                 Xóa
                             </button>
                        </div>
                    </div>`;
            };

            const setupFileDeleteHandler = (btn) => {
                btn.addEventListener('click', () => {
                    const fileId = btn.dataset.fileId;
                    if (!fileId) return;

                    currentDeleteFileAction = async () => {
                        try {
                            btn.disabled = true;
                            const response = await fetch(`{{ route('admin.texts.writer.destroy-file', [$text, ':id']) }}`.replace(':id', fileId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });

                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }

                            const result = await response.json();
                            if (result.success) {
                                const card = document.querySelector(`[data-file-id="${fileId}"]`);
                                if (card) {
                                    card.style.opacity = '0';
                                    card.style.transform = 'scale(0.95)';
                                    setTimeout(() => card.remove(), 300);
                                }
                            } else {
                                alert('Không thể xóa file.');
                                btn.disabled = false;
                            }
                        } catch (error) {
                            console.error(error);
                            alert('Đã xảy ra lỗi khi xóa file.');
                            btn.disabled = false;
                        }
                    };

                    if (deleteFileModal) {
                        deleteFileModal.showModal();
                    } else if (confirm('Bạn có chắc chắn muốn xóa file đính kèm này?')) {
                        currentDeleteFileAction();
                    }
                });
            };

            document.querySelectorAll('.delete-file-btn').forEach(setupFileDeleteHandler);

            if (fileUploadInput && fileNameDisplay && addFileBtn && attachedFilesList) {
                const triggerFileSelect = () => {
                    fileUploadInput.click();
                };

                fileNameDisplay.addEventListener('click', triggerFileSelect);

                fileUploadInput.addEventListener('change', () => {
                    if (fileUploadInput.files.length > 0) {
                        fileNameDisplay.value = fileUploadInput.files[0].name;
                    } else {
                        fileNameDisplay.value = '';
                    }
                });

                addFileBtn.addEventListener('click', async () => {
                    if (fileUploadInput.files.length === 0) {
                        triggerFileSelect();
                        return;
                    }

                    const formData = new FormData();
                    formData.append('files[]', fileUploadInput.files[0]);
                    formData.append('_token', '{{ csrf_token() }}');

                    addFileBtn.disabled = true;
                    const originalText = addFileBtn.textContent;
                    addFileBtn.textContent = 'Đang tải lên...';

                    try {
                        const response = await fetch(`{{ route('admin.texts.writer.store-files', $text) }}`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Đã xảy ra lỗi.');
                        }

                        const result = await response.json();
                        if (result.success && result.files) {
                            result.files.forEach(file => {
                                const newFileHtml = getFileHtml(file);
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = newFileHtml;
                                const newCard = tempDiv.firstElementChild;
                                
                                newCard.style.opacity = '0';
                                newCard.style.transform = 'scale(0.95)';
                                newCard.style.transition = 'all 0.3s ease';
                                
                                attachedFilesList.appendChild(newCard);
                                
                                setTimeout(() => {
                                    newCard.style.opacity = '1';
                                    newCard.style.transform = 'scale(1)';
                                }, 50);

                                const deleteBtn = newCard.querySelector('.delete-file-btn');
                                if (deleteBtn) {
                                    setupFileDeleteHandler(deleteBtn);
                                }
                            });

                            fileUploadInput.value = '';
                            fileNameDisplay.value = '';
                        }
                    } catch (error) {
                        console.error(error);
                        alert(error.message || 'Đã xảy ra lỗi khi tải file lên.');
                    } finally {
                        addFileBtn.disabled = false;
                        addFileBtn.textContent = 'Thêm file';
                    }
                });
            }

            // Quản lý liên kết (YouTube, Google Drive)
            const linkInput = document.getElementById('link_input');
            const addLinkBtn = document.getElementById('add_link_btn');
            const linksPreview = document.getElementById('links-preview');
            const deleteModal = document.getElementById('delete_link_modal');
            const confirmDeleteBtn = document.getElementById('confirm_delete_link_btn');
            let currentDeleteAction = null;

            if (confirmDeleteBtn && deleteModal) {
                confirmDeleteBtn.addEventListener('click', async () => {
                    const originalText = confirmDeleteBtn.textContent;
                    confirmDeleteBtn.textContent = 'Đang xử lý...';
                    confirmDeleteBtn.disabled = true;
                    if (currentDeleteAction) {
                        await currentDeleteAction();
                        currentDeleteAction = null;
                    }
                    confirmDeleteBtn.textContent = originalText;
                    confirmDeleteBtn.disabled = false;
                    deleteModal.close();
                });
            }

            if (linkInput && addLinkBtn && linksPreview) {
                const getEmbedHtml = (url, id, driveType = null) => {
                    const ytRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/live\/)([a-zA-Z0-9_-]{11})/;
                    const driveRegex = /drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/;

                    const ytMatch = url.match(ytRegex);
                    const driveMatch = url.match(driveRegex);

                    if (ytMatch) {
                        const videoId = ytMatch[1];
                        return `
                            <div class="media-card group rounded-lg transition-all duration-300 mb-6" data-id="${id}">
                                <div class="embed-container w-full">
                                    <div class="relative w-full rounded-lg overflow-hidden border border-slate-200 shadow-sm" style="aspect-ratio: 16/9; width: 100%;">
                                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src="https://www.youtube.com/embed/${videoId}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div class="mt-2 flex justify-end">
                                    <button type="button" class="delete-link-btn inline-flex items-center rounded-xl px-3 py-1.5 text-xs font-semibold text-white shadow transition hover:opacity-90" style="background: oklch(58% 0.140 24);" data-id="${id}" title="Gỡ bỏ">
                                        Xóa
                                    </button>
                                </div>
                            </div>`;
                    }

                    if (driveMatch) {
                        const fileId = driveMatch[1];
                        const driveEmbed = driveType === 'video' ? `
                            <div class="relative w-full rounded-lg overflow-hidden border border-slate-200 shadow-sm" style="aspect-ratio: 16/9; width: 100%;">
                                <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src="https://drive.google.com/file/d/${fileId}/preview" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>` : `
                            <img src="https://drive.google.com/thumbnail?id=${fileId}&sz=w1600" alt="Google Drive Image" class="w-full h-auto rounded-lg border border-slate-200 shadow-sm" style="display: block; width: 100%; height: auto;" />`;

                        return `
                            <div class="media-card group rounded-lg transition-all duration-300 mb-6" data-id="${id}">
                                <div class="embed-container w-full">
                                    ${driveEmbed}
                                </div>
                                <div class="mt-2 flex justify-end">
                                    <button type="button" class="delete-link-btn inline-flex items-center rounded-xl px-3 py-1.5 text-xs font-semibold text-white shadow transition hover:opacity-90" style="background: oklch(58% 0.140 24);" data-id="${id}" title="Gỡ bỏ">
                                        Xóa
                                    </button>
                                </div>
                            </div>`;
                    }

                    return null;
                };

                const setupDeleteHandler = (btn) => {
                    btn.addEventListener('click', () => {
                        const linkId = btn.dataset.id;
                        if (!linkId) return;

                        currentDeleteAction = async () => {
                            try {
                                btn.disabled = true;
                                const response = await fetch(`{{ route('admin.texts.writer.destroy-link', ['text' => $text, 'link' => ':id']) }}`.replace(':id', linkId), {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                });

                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }

                                const result = await response.json();
                                if (result.success) {
                                    const card = document.querySelector(`.media-card[data-id="${linkId}"]`);
                                    if (card) {
                                        card.style.opacity = '0';
                                        card.style.transform = 'scale(0.95)';
                                        setTimeout(() => card.remove(), 300);
                                    }
                                } else {
                                    alert('Không thể xóa liên kết.');
                                    btn.disabled = false;
                                }
                            } catch (error) {
                                console.error(error);
                                alert('Đã xảy ra lỗi khi xóa liên kết.');
                                btn.disabled = false;
                            }
                        };

                        if (deleteModal) {
                            deleteModal.showModal();
                        } else if (confirm('Bạn có chắc chắn muốn gỡ bỏ liên kết này?')) {
                            currentDeleteAction();
                        }
                    });
                };

                // Gắn sự kiện xóa cho các phần tử có sẵn khi load trang
                document.querySelectorAll('.delete-link-btn').forEach(setupDeleteHandler);

                addLinkBtn.addEventListener('click', async () => {
                    const url = linkInput.value.trim();
                    if (!url) return;

                    const embedHtml = getEmbedHtml(url, 'temp');
                    if (!embedHtml) {
                        alert('Liên kết không hợp lệ. Vui lòng nhập link YouTube hoặc Google Drive.');
                        return;
                    }

                    addLinkBtn.disabled = true;
                    const originalText = addLinkBtn.textContent;
                    addLinkBtn.textContent = 'Đang lưu...';

                    try {
                        const response = await fetch(`{{ route('admin.texts.writer.store-link', $text) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ url })
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Đã xảy ra lỗi.');
                        }

                        const result = await response.json();
                        if (result.success && result.link) {
                            const finalHtml = getEmbedHtml(result.link.url, result.link.id, result.link.drive_type);
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = finalHtml;
                            const newCard = tempDiv.firstElementChild;
                            
                            // Gắn hiệu ứng ban đầu
                            newCard.style.opacity = '0';
                            newCard.style.transform = 'scale(0.95)';
                            
                            linksPreview.appendChild(newCard);
                            
                            // Trigger reflow/animation
                            setTimeout(() => {
                                newCard.style.opacity = '1';
                                newCard.style.transform = 'scale(1)';
                            }, 50);

                            // Gắn trình xử lý sự kiện xóa cho thẻ mới
                            const newDeleteBtn = newCard.querySelector('.delete-link-btn');
                            if (newDeleteBtn) {
                                setupDeleteHandler(newDeleteBtn);
                            }

                            linkInput.value = '';
                        }
                    } catch (error) {
                        console.error(error);
                        alert(error.message || 'Đã xảy ra lỗi khi lưu liên kết.');
                    } finally {
                        addLinkBtn.disabled = false;
                        addLinkBtn.textContent = originalText;
                    }
                });
            }
        })();
    </script>
@endpush
