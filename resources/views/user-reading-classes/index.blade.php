@extends('layouts.library')

@section('title', 'Nhiệm vụ đọc hiểu của tôi')

@section('content')
    <section class="space-y-6">
        <!-- Cards List -->
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($readingClasses as $class)
                <article class="relative flex flex-col justify-between overflow-hidden rounded-2xl border p-5 shadow-sm transition-all hover:translate-y-[-4px] hover:shadow-md group"
                         style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
                    <!-- Top Accent highlight line on hover -->
                    <div class="absolute top-0 left-0 right-0 h-1 origin-left scale-x-0 transition-transform duration-500 group-hover:scale-x-100" style="background: var(--g-primary);"></div>
                    
                    <div class="space-y-4">
                        <!-- Top Info -->
                        <div class="flex items-center justify-between">
                            <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block"
                                  style="background: oklch(62% 0.090 240 / 0.15); color: oklch(35% 0.080 240);">
                                Nhiệm vụ đọc hiểu
                            </span>
                            <span class="text-xs font-semibold" style="color: oklch(46% 0.018 58);">
                                {{ optional($class->created_at)->format('d/m/Y') }}
                            </span>
                        </div>

                        <!-- Class Name -->
                        <div class="space-y-1">
                            <h2 class="text-xl font-bold font-serif leading-snug" style="color: oklch(18% 0.020 58);">
                                {{ $class->name }}
                            </h2>
                            <p class="text-xs flex items-center gap-1 font-medium" style="color: oklch(34% 0.025 64);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>{{ $class->users_count }} thành viên cùng nhóm</span>
                            </p>
                        </div>

                        <!-- Stats and Info -->
                        <div class="flex items-center gap-4 text-xs pt-1" style="color: oklch(34% 0.025 64);">
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span><strong>{{ $class->texts_count }}</strong> văn bản liên kết</span>
                            </div>
                            <div class="h-3 w-px bg-slate-200"></div>
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <span><strong>{{ $class->assignments_count }}</strong> nhiệm vụ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Button -->
                    <div class="mt-5 border-t pt-4" style="border-color: oklch(90% 0.018 74);">
                        <a
                            href="{{ route('user.reading-classes.show', $class) }}"
                            class="btn btn-sm w-full rounded-xl border-0 text-white shadow-none transition"
                            style="background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                        >
                            Chi tiết
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed p-8 text-center text-sm shadow-sm md:col-span-2 xl:col-span-3"
                     style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72); color: oklch(46% 0.018 58);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto size-12 mb-3" style="color: oklch(64% 0.012 62);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="font-bold mb-1" style="color: oklch(18% 0.020 58);">Bạn chưa tham gia nhiệm vụ đọc hiểu nào</p>
                    <p class="text-xs font-serif italic" style="color: oklch(46% 0.018 58);">Vui lòng liên hệ với thầy cô giáo để được thêm vào nhóm học tập.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
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
@endsection

@push('scripts')
<script src="{{ asset('js/library/pagination.js') }}"></script>
@endpush
