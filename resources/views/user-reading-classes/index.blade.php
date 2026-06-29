@extends('layouts.library')

@section('title', 'Nhiệm vụ đọc hiểu của tôi')

@push('styles')
<style>
/* Design tokens matching home page */
:root {
    --p-50:  oklch(98.8% 0.006 70);
    --p-100: oklch(96.4% 0.014 68);
    --p-200: oklch(90.0% 0.026 64);
    --p-300: oklch(80.0% 0.040 60);
    --p-400: oklch(66.0% 0.052 56);
    --p-500: oklch(52.0% 0.060 54);
    --p-600: oklch(42.0% 0.062 52);
    --p-700: oklch(34.0% 0.055 50);
    --p-800: oklch(26.0% 0.044 50);
    --p-900: oklch(18.0% 0.030 50);

    --tx-title:  oklch(16% 0.025 52);
    --tx-body:   oklch(28% 0.022 54);
    --tx-muted:  oklch(46% 0.018 58);
    --tx-faint:  oklch(64% 0.012 62);

    --bd-subtle: oklch(91% 0.010 70);
    --bd-warm:   oklch(85% 0.020 66);
    --bd-strong: oklch(72% 0.034 60);

    --g-primary: linear-gradient(145deg, oklch(44% 0.064 54)   0%, oklch(36% 0.056 50) 100%);
    --g-accent:  linear-gradient(90deg,  oklch(44% 0.064 54)   0%, oklch(60% 0.052 58) 55%, oklch(76% 0.038 66) 100%);
    --g-card:    linear-gradient(160deg, oklch(99.8% 0.002 78)  0%, oklch(99.0% 0.006 74) 60%, oklch(97.8% 0.012 70) 100%);

    --font-ui:    'Bahnschrift', 'Segoe UI', ui-sans-serif, system-ui, sans-serif;
    --font-serif: 'EB Garamond', 'Lora', 'Georgia', ui-serif, serif;

    --r-sm:  0.5rem;
    --r-md:  0.75rem;
    --r-lg:  1rem;
    --r-xl:  1.25rem;
    --r-pill: 9999px;

    --sh-card:  0 1px 2px oklch(25% 0.030 52 / .06),
                0 4px 14px -4px oklch(25% 0.030 52 / .10);
    --sh-hover: 0 2px 4px oklch(25% 0.030 52 / .04),
                0 14px 36px -8px oklch(38% 0.055 52 / .20);
}

/* ---------- BASE RESET FOR THIS PAGE ---------------------------- */
.lib-page * { font-family: var(--font-ui); }
.lib-page .serif { font-family: var(--font-serif); }

/* Card styles */
.lib-card {
    position: relative;
    display: flex;
    flex-direction: column;
    background: oklch(99.6% 0.002 78);
    border: 1px solid var(--bd-subtle);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: var(--sh-card);
    transition:
        border-color .48s ease,
        box-shadow   .48s ease,
        transform    .56s cubic-bezier(.34, 1.45, .64, 1);
}

.lib-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--g-accent);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .56s cubic-bezier(.34, 1.45, .64, 1);
    pointer-events: none;
    z-index: 1;
}

.lib-card:hover {
    border-color: var(--bd-warm);
    transform: translateY(-4px);
    box-shadow: var(--sh-hover);
}

.lib-card:hover::before {
    transform: scaleX(1);
}

.lib-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    padding: 1.25rem 1.4rem;
}

.lib-topic-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.30rem;
    padding: 0.18rem 0.50rem;
    border-radius: 0.3rem;
    border: 1px solid oklch(88% .018 68);
    background: oklch(96% .012 70);
    color: var(--p-600);
    font-size: 0.595rem;
    font-weight: 700;
    letter-spacing: 0.13em;
    text-transform: uppercase;
    align-self: flex-start;
}
.lib-topic-chip svg { flex-shrink: 0; opacity: .65; }

.lib-card-title {
    font-family: var(--font-serif);
    font-size: 1.05rem;
    font-weight: 600;
    line-height: 1.4;
    color: var(--tx-title);
    text-decoration: none;
    transition: color .2s ease;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.lib-card:hover .lib-card-title { color: var(--p-600); }

.lib-card-author {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.76rem;
    font-weight: 400;
    color: var(--tx-muted);
}
.lib-card-author svg { flex-shrink: 0; }

.lib-card-stats {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.72rem;
    color: var(--tx-muted);
    margin-top: 0.2rem;
}
.lib-card-stats-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}
.lib-card-stats-divider {
    width: 1px;
    height: 0.65rem;
    background-color: var(--bd-subtle);
}

.lib-card-footer {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.4rem;
    border-top: 1px solid oklch(93% .010 72);
}

.lib-read-link {
    display: inline-flex;
    align-items: center;
    gap: 0.30rem;
    font-size: 0.74rem;
    font-weight: 600;
    color: var(--p-700);
    text-decoration: none;
    transition: color .18s ease, gap .18s ease;
    letter-spacing: 0.01em;
}
.lib-read-link:hover { color: var(--p-500); gap: 0.45rem; }
.lib-read-link svg { flex-shrink: 0; transition: transform .18s ease; }
.lib-read-link:hover svg { transform: translateX(3px); }

.lib-card-date {
    font-size: 0.70rem;
    font-weight: 600;
    color: var(--tx-faint);
    margin-left: auto;
}
</style>
@endpush

@section('content')
    <section class="lib-page space-y-6">
        <!-- Cards List -->
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($readingClasses as $class)
                <article class="lib-card">
                    <div class="lib-card-body">
                        <!-- Top Row: Topic Badge -->
                        <span class="lib-topic-chip">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Nhiệm vụ đọc hiểu
                        </span>

                        <!-- Class Name / Title -->
                        <a href="{{ route('user.reading-classes.show', $class) }}" class="lib-card-title">
                            {{ $class->name }}
                        </a>

                        <!-- Members count -->
                        <div class="lib-card-author">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>{{ $class->users_count }} thành viên cùng nhóm</span>
                        </div>

                        <!-- Stats and Info -->
                        <div class="lib-card-stats">
                            <div class="lib-card-stats-item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span><strong>{{ $class->texts_count }}</strong> văn bản liên kết</span>
                            </div>
                            <div class="lib-card-stats-divider"></div>
                            <div class="lib-card-stats-item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <span><strong>{{ $class->assignments_count }}</strong> nhiệm vụ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Links -->
                    <div class="lib-card-footer">
                        <a href="{{ route('user.reading-classes.show', $class) }}" class="lib-read-link">
                            Chi tiết
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <span class="lib-card-date">
                            {{ optional($class->created_at)->format('d/m/Y') }}
                        </span>
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
