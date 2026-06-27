@extends('layouts.library')

@section('title', 'Thư viện văn bản văn học mở rộng')

@section('content')
    <section class="space-y-5">
        <div class="space-y-4">
            <div class="overflow-hidden rounded-sm border border-slate-200 bg-gradient-to-r from-white via-slate-50 to-blue-50 text-slate-800 shadow-[0_20px_48px_-36px_rgba(15,23,42,0.28)]">
                <div class="grid gap-4 p-5 md:grid-cols-[1.4fr_0.6fr] md:items-center">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Kho tư liệu mở</p>
                        <h2 class="text-2xl font-semibold leading-tight text-slate-900">Bộ sưu tập văn bản chọn lọc cho học tập và giảng dạy.</h2>
                    </div>

                    <div class="hidden md:flex md:justify-end">
                        <div class="grid grid-cols-3 gap-2">
                            <span class="h-14 w-14 rounded-sm bg-blue-100"></span>
                            <span class="h-14 w-14 rounded-sm bg-slate-100"></span>
                            <span class="h-14 w-14 rounded-sm bg-blue-50"></span>
                            <span class="h-14 w-14 rounded-sm bg-slate-100"></span>
                            <span class="h-14 w-14 rounded-sm bg-blue-100"></span>
                            <span class="h-14 w-14 rounded-sm bg-slate-100"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($texts as $text)
                <article class="overflow-hidden rounded-sm border border-slate-200 bg-white shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)] transition-all hover:-translate-y-0.5 hover:border-slate-300">
                    <div class="space-y-4 p-5">
                        <h3 class="min-h-16 text-[1.35rem] leading-8 text-slate-900">
                            @php
                                $textUrl = auth()->check() && auth()->user()->role === 'teacher'
                                    ? route('admin.texts.writer.edit', $text)
                                    : route('texts.content.show', $text);
                            @endphp
                            <a href="{{ $textUrl }}" class="hover:text-blue-700">
                                {{ $text->name }}
                            </a>
                        </h3>
                        <div class="text-sm text-slate-600">
                            <span class="font-medium">Tác giả tác phẩm:</span> {{ $text->author }}
                        </div>
                    </div>
                    <div class="flex items-center justify-between bg-slate-50 px-5 py-4 text-sm text-slate-600">
                        <div class="flex items-center gap-2 text-sm text-amber-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 7.5A1.5 1.5 0 0 1 4.5 6h4.379a1.5 1.5 0 0 1 1.06.44l1.12 1.12a1.5 1.5 0 0 0 1.06.44H19.5A1.5 1.5 0 0 1 21 9.5v8A1.5 1.5 0 0 1 19.5 19h-15A1.5 1.5 0 0 1 3 17.5v-10Z" />
                            </svg>
                            <span class="text-slate-600">{{ $text->textTopic?->name ?? 'Chưa phân loại' }}</span>
                        </div>
                        @if ($text->read_link)
                            <a href="{{ $text->read_link }}" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-blue-600 underline underline-offset-2 hover:text-blue-700">
                                Mở link
                            </a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-sm border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                    Chưa có văn bản nào để hiển thị.
                </div>
            @endforelse
        </div>

        @if ($texts->hasPages())
            <div class="flex items-center justify-center gap-2">
                @if ($texts->onFirstPage())
                    <span class="rounded-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-400">Trước</span>
                @else
                    <a href="{{ $texts->previousPageUrl() }}" class="rounded-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-slate-300 hover:text-blue-700">Trước</a>
                @endif

                @foreach ($texts->getUrlRange(1, $texts->lastPage()) as $page => $url)
                    @if ($page == $texts->currentPage())
                        <span class="rounded-sm border border-blue-600 bg-white px-3 py-2 text-sm font-semibold text-blue-700">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="rounded-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-slate-300 hover:text-blue-700">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($texts->hasMorePages())
                    <a href="{{ $texts->nextPageUrl() }}" class="rounded-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-slate-300 hover:text-blue-700">Sau</a>
                @else
                    <span class="rounded-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-400">Sau</span>
                @endif
            </div>
        @endif
    </section>
@endsection
