@extends('layouts.library')

@section('title', 'Xem văn bản')

@section('content')
    <section class="space-y-5">
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">Xem văn bản</h1>
                    <p class="text-sm text-slate-500">{{ $text->name }} • {{ $text->author }}</p>
                </div>
                @if ($text->read_link)
                    <div>
                        <a href="{{ $text->read_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-sm border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Link đọc
                        </a>
                    </div>
                @endif
            </div>



            @if (!empty($text->description))
                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Mô tả</label>
                    <div class="rounded-sm border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $text->description }}
                    </div>
                </div>
            @endif

            @if ($text->textFiles->isNotEmpty())
                <div class="mb-5 space-y-4">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Tài liệu/Phương tiện đi kèm</label>
                    <div class="space-y-4">
                        @foreach ($text->textFiles as $file)
                            <div class="rounded-sm border border-slate-200 bg-slate-50 p-4 shadow-[0_4px_12px_-6px_rgba(15,23,42,0.15)]">
                                <div class="mb-2 flex items-center justify-between border-b border-slate-200 pb-2">
                                    <span class="text-sm font-semibold text-slate-800">{{ $file->file_name }}</span>
                                    <span class="rounded bg-blue-50 px-2 py-0.5 text-xs font-semibold uppercase text-blue-700">
                                        {{ $file->file_type === 'image' ? 'Hình ảnh' : ($file->file_type === 'video' ? 'Video' : ($file->file_type === 'audio' ? 'Âm thanh' : 'Tài liệu')) }}
                                    </span>
                                </div>
                                
                                <div class="mt-2 flex justify-center">
                                    @if ($file->file_type === 'image')
                                        <div class="overflow-hidden rounded-sm border border-slate-200 bg-white p-1">
                                            <img src="{{ route('texts.files.serve', ['text' => $text, 'filename' => basename($file->file_path)]) }}" alt="{{ $file->file_name }}" class="max-h-[400px] w-auto max-w-full object-contain" />
                                        </div>
                                    @elseif ($file->file_type === 'video')
                                        <div class="w-full overflow-hidden rounded-sm border border-slate-200 bg-black">
                                            <video controls class="w-full max-h-[450px]">
                                                <source src="{{ route('texts.files.serve', ['text' => $text, 'filename' => basename($file->file_path)]) }}">
                                                Trình duyệt của bạn không hỗ trợ xem video trực tiếp.
                                            </video>
                                        </div>
                                    @elseif ($file->file_type === 'audio')
                                        <div class="w-full p-2 bg-white rounded-sm border border-slate-200">
                                            <audio controls class="w-full">
                                                <source src="{{ route('texts.files.serve', ['text' => $text, 'filename' => basename($file->file_path)]) }}">
                                                Trình duyệt của bạn không hỗ trợ nghe âm thanh trực tiếp.
                                            </audio>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <a href="{{ route('texts.files.serve', ['text' => $text, 'filename' => basename($file->file_path)]) }}" target="_blank" class="text-sm font-semibold text-blue-600 underline hover:text-blue-700">
                                                Tải về file đính kèm
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($previewError))
                <div class="mb-4 rounded-sm border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    {{ $previewError }}
                </div>
            @endif

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Nội dung văn bản</label>
                
                @if($text->textLinks->isNotEmpty())
                    <div id="links-preview" class="space-y-6 mb-6">
                        @foreach($text->textLinks as $link)
                            @php
                                $isYoutube = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/live\/)([a-zA-Z0-9_-]{11})/', $link->url, $ytMatch);
                                $isDrive = preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $link->url, $driveMatch);
                            @endphp
                            @if($isYoutube)
                                <div class="media-container w-full" data-id="{{ $link->id }}">
                                    <div class="relative w-full rounded-lg overflow-hidden border border-slate-200 shadow-sm" style="aspect-ratio: 16/9; width: 100%;">
                                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src="https://www.youtube.com/embed/{{ $ytMatch[1] }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    @auth
                                        @if (auth()->user()->role === 'teacher')
                                            <div class="mt-2 flex justify-end">
                                                <form method="POST" action="{{ route('admin.texts.writer.destroy-link', ['text' => $text, 'link' => $link]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên kết này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded-sm bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow hover:bg-rose-700">
                                                        Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @elseif($isDrive)
                                <div class="media-container w-full" data-id="{{ $link->id }}">
                                    @if($link->getDriveType() === 'video')
                                        <div class="relative w-full rounded-lg overflow-hidden border border-slate-200 shadow-sm" style="aspect-ratio: 16/9; width: 100%;">
                                            <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src="https://drive.google.com/file/d/{{ $driveMatch[1] }}/preview" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    @else
                                        <img src="https://drive.google.com/thumbnail?id={{ $driveMatch[1] }}&sz=w1600" alt="Google Drive Image" class="w-full h-auto rounded-lg border border-slate-200 shadow-sm" style="display: block; width: 100%; height: auto;" />
                                    @endif
                                    @auth
                                        @if (auth()->user()->role === 'teacher')
                                            <div class="mt-2 flex justify-end">
                                                <form method="POST" action="{{ route('admin.texts.writer.destroy-link', ['text' => $text, 'link' => $link]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên kết này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded-sm bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow hover:bg-rose-700">
                                                        Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="writer-preview min-h-[60vh] overflow-auto rounded-sm border border-slate-200 bg-white p-5">
                    {!! $previewHtml !!}
                </div>
            </div>
        </div>

        <div id="comments" class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-slate-900">Bình luận bài học</h2>
                <p class="text-sm text-slate-500">{{ $comments->count() }} bình luận</p>
            </div>

            @auth
                <form method="POST" action="{{ route('texts.comments.store', $text) }}" class="mb-5 space-y-3">
                    @csrf
                    <div>
                        <label for="comment-content" class="mb-1 block text-sm font-medium text-slate-700">Nội dung bình luận</label>
                        <textarea
                            id="comment-content"
                            name="content"
                            rows="4"
                            class="w-full rounded-sm border border-slate-300 px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            placeholder="Viết bình luận của bạn..."
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-xs text-blue-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="inline-flex rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Gửi bình luận
                    </button>
                </form>
            @else
                <div class="mb-5 rounded-sm border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    Bạn cần đăng nhập để bình luận.
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">Đăng nhập</a>
                </div>
            @endauth

            <div class="space-y-3">
                @forelse ($comments as $comment)
                    <article class="rounded-sm border border-slate-200 bg-white p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold uppercase text-blue-700">
                                {{ \Illuminate\Support\Str::of($comment->user->name)->substr(0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="text-sm font-semibold text-slate-800">{{ $comment->user->name }}</p>
                                    <div class="flex items-center gap-3">
                                        <p class="text-xs text-slate-500">{{ $comment->created_at?->format('H:i d/m/Y') }}</p>
                                        @auth
                                            @if (
                                                auth()->user()->role === 'teacher'
                                                || (auth()->user()->role === 'user' && (string) $comment->user_id === (string) auth()->id())
                                            )
                                                <form method="POST" action="{{ route('texts.comments.destroy', [$text, $comment]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-medium text-rose-600 hover:text-rose-700">
                                                        Xóa
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $comment->content }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-sm border border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-center text-sm text-slate-500">
                        Chưa có bình luận nào cho bài học này.
                    </div>
                @endforelse
            </div>
        </div>


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
    </style>
@endpush
