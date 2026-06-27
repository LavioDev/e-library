@extends('layouts.library')

@section('title', 'Nhiệm vụ đọc hiểu của tôi')

@section('content')
    <section class="space-y-6">
        <!-- Cards List -->
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($readingClasses as $class)
                <article class="flex flex-col justify-between overflow-hidden rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)] transition-all hover:translate-y-[-2px] hover:shadow-[0_22px_48px_-30px_rgba(15,23,42,0.4)]">
                    <div class="space-y-4">
                        <!-- Top Info -->
                        <div class="flex items-center justify-between">
                            <span class="badge badge-sm border-blue-200 bg-blue-50 text-blue-700 text-xs font-semibold normal-case rounded-sm px-2">
                                Nhiệm vụ đọc hiểu
                            </span>
                            <span class="text-xs text-slate-400">
                                {{ optional($class->created_at)->format('d/m/Y') }}
                            </span>
                        </div>

                        <!-- Class Name -->
                        <div class="space-y-1">
                            <h2 class="text-xl font-bold text-slate-900 leading-snug">
                                {{ $class->name }}
                            </h2>
                            <p class="text-xs text-slate-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>{{ $class->users_count }} thành viên cùng nhóm</span>
                            </p>
                        </div>

                        <!-- Stats and Info -->
                        <div class="flex items-center gap-4 text-xs text-slate-600 pt-1">
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
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        <a
                            href="{{ route('user.reading-classes.show', $class) }}"
                            class="btn btn-sm w-full rounded-sm border-0 bg-blue-600 text-white shadow-none hover:bg-blue-700 text-center animate-all duration-200"
                        >
                            Chi tiết
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-sm border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto size-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="font-medium text-slate-800 mb-1">Bạn chưa tham gia nhiệm vụ đọc hiểu nào</p>
                    <p class="text-xs text-slate-400">Vui lòng liên hệ với thầy cô giáo để được thêm vào nhóm học tập.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($readingClasses->hasPages())
            <div class="pt-4">
                {{ $readingClasses->links() }}
            </div>
        @endif
    </section>
@endsection
