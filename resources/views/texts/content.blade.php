@extends('layouts.library')

@section('title', 'Xem văn bản')

@section('content')
    <section class="space-y-6">
        {{-- ─── TEXT METADATA CARD ─── --}}
        <div class="rounded-2xl border p-6 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-4 border-b pb-4" style="border-color: oklch(91% 0.016 74);">
                <div>
                    <h1 class="font-serif font-bold text-xl md:text-2xl" style="color: oklch(18% 0.020 58);">Xem văn bản</h1>
                    <p class="text-xs md:text-sm mt-1" style="color: oklch(45% 0.025 65);">{{ $text->name }} • {{ $text->author }}</p>
                </div>
                @if ($text->read_link)
                    <div>
                        <a href="{{ $text->read_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-xl border px-3.5 py-2 text-xs font-bold transition hover:opacity-85"
                           style="background: oklch(94% 0.014 74 / 0.6); border-color: oklch(87% 0.018 72); color: oklch(40% 0.068 54);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span>Link đọc bên ngoài</span>
                        </a>
                    </div>
                @endif
            </div>

            @if (!empty($text->description))
                <div class="mb-5">
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider" style="color: oklch(50% 0.025 65);">Mô tả</label>
                    <div class="rounded-xl border p-4 text-xs md:text-sm leading-relaxed whitespace-pre-line"
                         style="background: oklch(97% 0.010 76); border-color: oklch(90% 0.018 74); color: oklch(30% 0.022 60);">
                        {{ $text->description }}
                    </div>
                </div>
            @endif

            @if ($text->textFiles->isNotEmpty())
                <div class="mb-5 space-y-3">
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wider" style="color: oklch(50% 0.025 65);">Tài liệu/Phương tiện đi kèm</label>
                    <div class="space-y-4">
                        @foreach ($text->textFiles as $file)
                            <div class="rounded-xl border p-4" style="background: oklch(98.5% 0.005 78 / 0.5); border-color: oklch(90% 0.018 74);">
                                <div class="mb-2 flex items-center justify-between border-b pb-2" style="border-color: oklch(91% 0.016 74);">
                                    <span class="text-sm font-semibold" style="color: oklch(24% 0.020 58);">{{ $file->file_name }}</span>
                                    <span class="rounded-lg px-2.5 py-0.5 text-[0.65rem] font-bold uppercase"
                                          style="background: oklch(94% 0.014 74 / 0.8); color: oklch(40% 0.068 54);">
                                        {{ $file->file_type === 'image' ? 'Hình ảnh' : ($file->file_type === 'video' ? 'Video' : ($file->file_type === 'audio' ? 'Âm thanh' : 'Tài liệu')) }}
                                    </span>
                                </div>
                                
                                <div class="mt-2 flex justify-center">
                                    @if ($file->file_type === 'image')
                                        @php
                                            $isTeacher = auth()->check() && auth()->user()->role === 'teacher';
                                        @endphp
                                        <div class="overflow-hidden rounded-xl border p-1" style="background: oklch(99.4% 0.005 78); border-color: oklch(89% 0.018 72);">
                                            <img src="{{ route('texts.files.serve', ['text' => $text, 'filename' => basename($file->file_path)]) }}" alt="{{ $file->file_name }}" class="max-h-[400px] w-auto max-w-full object-contain rounded-lg"
                                                 @if(!$isTeacher) onerror="this.closest('.rounded-xl.border').style.display='none';" @endif />
                                        </div>
                                    @elseif ($file->file_type === 'video')
                                        <div class="w-full overflow-hidden rounded-xl border bg-black" style="border-color: oklch(89% 0.018 72);">
                                            <video controls class="w-full max-h-[450px]">
                                                <source src="{{ route('texts.files.serve', ['text' => $text, 'filename' => basename($file->file_path)]) }}">
                                                Trình duyệt của bạn không hỗ trợ xem video trực tiếp.
                                            </video>
                                        </div>
                                    @elseif ($file->file_type === 'audio')
                                        <div class="w-full p-2 rounded-xl border" style="background: oklch(99.4% 0.005 78); border-color: oklch(89% 0.018 72);">
                                            <audio controls class="w-full">
                                                <source src="{{ route('texts.files.serve', ['text' => $text, 'filename' => basename($file->file_path)]) }}">
                                                Trình duyệt của bạn không hỗ trợ nghe âm thanh trực tiếp.
                                            </audio>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: oklch(55% 0.025 65);">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <a href="{{ route('texts.files.serve', ['text' => $text, 'filename' => basename($file->file_path)]) }}" target="_blank" class="text-xs md:text-sm font-semibold underline underline-offset-4 hover:opacity-80"
                                               style="color: oklch(40% 0.068 54);">
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
                <div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="border-color:oklch(72% 0.090 42 / 0.4); background:oklch(97% 0.018 58 / 0.6); color:oklch(38% 0.080 42);">
                    {{ $previewError }}
                </div>
            @endif

            {{-- ─── MAIN TEXT CONTENT ─── --}}
            <div class="space-y-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider" style="color: oklch(50% 0.025 65);">Nội dung văn bản</label>
                
                @if($text->textLinks->isNotEmpty())
                    <div id="links-preview" class="space-y-6 mb-6">
                        @foreach($text->textLinks as $link)
                            @php
                                $isYoutube = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/live\/)([a-zA-Z0-9_-]{11})/', $link->url, $ytMatch);
                                $isDrive = preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $link->url, $driveMatch);
                            @endphp
                            @if($isYoutube)
                                <div class="media-container w-full" data-id="{{ $link->id }}">
                                    <div class="relative w-full rounded-2xl overflow-hidden border shadow-sm" style="aspect-ratio: 16/9; width: 100%; border-color: oklch(89% 0.018 72);">
                                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src="https://www.youtube.com/embed/{{ $ytMatch[1] }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    @auth
                                        @if (auth()->user()->role === 'teacher')
                                            <div class="mt-2 flex justify-end">
                                                <form method="POST" action="{{ route('admin.texts.writer.destroy-link', ['text' => $text, 'link' => $link]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên kết này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded-xl px-3 py-1.5 text-xs font-semibold text-white shadow transition hover:opacity-90"
                                                            style="background: oklch(58% 0.140 24);">
                                                        Xóa liên kết
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @elseif($isDrive)
                                <div class="media-container w-full" data-id="{{ $link->id }}">
                                    @if($link->getDriveType() === 'video')
                                        <div class="relative w-full rounded-2xl overflow-hidden border shadow-sm" style="aspect-ratio: 16/9; width: 100%; border-color: oklch(89% 0.018 72);">
                                            <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src="https://drive.google.com/file/d/{{ $driveMatch[1] }}/preview" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    @else
                                        @php
                                            $isTeacher = auth()->check() && auth()->user()->role === 'teacher';
                                        @endphp
                                        <img src="https://drive.google.com/thumbnail?id={{ $driveMatch[1] }}&sz=w1600" alt="Google Drive Image" class="w-full h-auto rounded-2xl border shadow-sm" style="display: block; width: 100%; height: auto; border-color: oklch(89% 0.018 72);"
                                             @if(!$isTeacher) onerror="this.closest('.media-container').style.display='none';" @endif />
                                    @endif
                                    @auth
                                        @if (auth()->user()->role === 'teacher')
                                            <div class="mt-2 flex justify-end">
                                                <form method="POST" action="{{ route('admin.texts.writer.destroy-link', ['text' => $text, 'link' => $link]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên kết này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded-xl px-3 py-1.5 text-xs font-semibold text-white shadow transition hover:opacity-90"
                                                            style="background: oklch(58% 0.140 24);">
                                                        Xóa liên kết
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

                <div class="writer-preview min-h-[60vh] overflow-auto rounded-xl border p-5 md:p-8"
                     style="background: oklch(99.4% 0.005 78); border-color: oklch(89% 0.018 72); font-family: var(--font-serif); font-size: 1.05rem; line-height: 1.8; color: oklch(20% 0.022 60);">
                    {!! $previewHtml !!}
                </div>
            </div>
        </div>

        {{-- ─── COMMENTS SECTION ─── --}}
        <div id="comments" class="rounded-2xl border p-6 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="mb-5">
                <h2 class="font-serif font-bold text-lg" style="color: oklch(18% 0.020 58);">Bình luận thảo luận</h2>
                <p class="text-xs mt-1" style="color: oklch(50% 0.025 65);">{{ $comments->count() }} bình luận</p>
            </div>

            @auth
                <form method="POST" action="{{ route('texts.comments.store', $text) }}" class="mb-6 space-y-3">
                    @csrf
                    <div>
                        <textarea
                            id="comment-content"
                            name="content"
                            rows="3"
                            class="w-full rounded-xl border px-4 py-3 text-sm shadow-none focus:outline-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                            placeholder="Viết bình luận hoặc câu hỏi của bạn tại đây..."
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-xs" style="color: oklch(58% 0.140 24);">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border-0 px-5 text-white shadow-none transition"
                            style="background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Gửi bình luận
                    </button>
                </form>
            @else
                <div class="mb-6 rounded-xl border px-4 py-3.5 text-xs md:text-sm"
                     style="background: oklch(97% 0.010 76); border-color: oklch(90% 0.018 74); color: oklch(42% 0.025 65);">
                    Bạn cần đăng nhập để tham gia thảo luận. 
                    <a href="{{ route('login') }}" class="font-bold underline underline-offset-2 transition hover:opacity-80" style="color: oklch(40% 0.068 54);">Đăng nhập ngay</a>
                </div>
            @endauth

            <div class="space-y-4">
                @forelse ($comments as $comment)
                    <article class="rounded-xl border p-4 shadow-sm" style="background: oklch(99.4% 0.005 78); border-color: oklch(90% 0.018 74);">
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold uppercase shadow-inner"
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
                        Chưa có ý kiến thảo luận nào. Hãy là người đầu tiên chia sẻ cảm nghĩ!
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
            border: 1px solid oklch(89% 0.018 72);
            padding: 0.5rem;
            vertical-align: top;
        }

        .writer-preview p,
        .writer-preview li,
        .writer-preview td,
        .writer-preview th {
            line-height: 1.8;
        }

        .writer-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
@endpush
