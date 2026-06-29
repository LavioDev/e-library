@extends('layouts.library')

@section('title', 'Thư viện văn bản văn học — Kho tư liệu mở')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..700;1,400..700&family=Lora:ital,wght@0,400..700;1,400..700&display=swap');

/* ================================================================
   PAGE-LEVEL DESIGN SYSTEM
   Font: Be Vietnam Pro (UI) · Lora (serif heading)
   Palette: warm brown–amber–cream
   ================================================================ */

/* ---------- CUSTOM PROPERTIES ----------------------------------- */
:root {
    /* — Colour scale — */
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

    /* — Surface — */
    --surf-0: oklch(99.8% 0.002 76);
    --surf-1: oklch(99.0% 0.006 74);
    --surf-2: oklch(97.5% 0.012 72);
    --surf-3: oklch(95.5% 0.018 70);

    /* — Text — */
    --tx-title:  oklch(16% 0.025 52);
    --tx-body:   oklch(28% 0.022 54);
    --tx-muted:  oklch(46% 0.018 58);
    --tx-faint:  oklch(64% 0.012 62);

    /* — Border — */
    --bd-subtle: oklch(91% 0.010 70);
    --bd-warm:   oklch(85% 0.020 66);
    --bd-strong: oklch(72% 0.034 60);

    /* — Gradient presets — */
    --g-hero:    linear-gradient(145deg, oklch(99.4% 0.004 80) 0%, oklch(97.5% 0.009 76) 50%, oklch(95.5% 0.015 72) 100%);
    --g-primary: linear-gradient(145deg, oklch(44% 0.064 54)   0%, oklch(36% 0.056 50) 100%);
    --g-accent:  linear-gradient(90deg,  oklch(44% 0.064 54)   0%, oklch(60% 0.052 58) 55%, oklch(76% 0.038 66) 100%);
    --g-card:    linear-gradient(160deg, oklch(99.8% 0.002 78)  0%, oklch(99.0% 0.006 74) 60%, oklch(97.8% 0.012 70) 100%);
    --g-footer:  linear-gradient(135deg, oklch(99.2% 0.005 75 / .85) 0%, oklch(97.5% 0.012 70 / .70) 100%);

    /* — Typography — */
    --font-ui:    'Bahnschrift', 'Segoe UI', ui-sans-serif, system-ui, sans-serif;
    --font-serif: 'EB Garamond', 'Lora', 'Georgia', ui-serif, serif;

    /* — Radius — */
    --r-sm:  0.5rem;
    --r-md:  0.75rem;
    --r-lg:  1rem;
    --r-xl:  1.25rem;
    --r-pill: 9999px;

    /* — Shadow — */
    --sh-card:  0 1px 2px oklch(25% 0.030 52 / .06),
                0 4px 14px -4px oklch(25% 0.030 52 / .10);
    --sh-hover: 0 2px 4px oklch(25% 0.030 52 / .04),
                0 14px 36px -8px oklch(38% 0.055 52 / .20);
    --sh-btn:   0 1px 0 oklch(100% 0 0 / .14) inset,
                0 4px 14px -2px oklch(44% 0.064 54 / .34);
    --sh-btn-h: 0 1px 0 oklch(100% 0 0 / .14) inset,
                0 8px 26px -4px oklch(44% 0.064 54 / .46);
}

/* ---------- BASE RESET FOR THIS PAGE ---------------------------- */
.lib-page * { font-family: var(--font-ui); }
.lib-page .serif { font-family: var(--font-serif); }

/* ================================================================
   HERO
   ================================================================ */
.lib-hero {
    position: relative;
    background: var(--g-hero);
    border: 1px solid var(--bd-warm);
    border-radius: var(--r-xl);
    overflow: hidden;
    box-shadow:
        0 1px 3px oklch(38% 0.040 58 / .07),
        0 10px 40px -10px oklch(38% 0.040 58 / .12);
}

/* Rainbow accent bar on top */
.lib-hero::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg,
        transparent      0%,
        oklch(44% .064 54 / .55) 20%,
        oklch(58% .056 56 / .85) 48%,
        oklch(74% .044 64 / .55) 75%,
        transparent      100%);
    pointer-events: none;
    z-index: 2;
}

/* Bottom-right warm glow orb */
.lib-hero::after {
    content: '';
    position: absolute; bottom: -5rem; right: -5rem;
    width: 22rem; height: 22rem;
    border-radius: 50%;
    background: radial-gradient(circle,
        oklch(80% .060 66 / .20) 0%,
        oklch(80% .060 66 / .06) 45%,
        transparent 70%);
    pointer-events: none;
}

/* Top-left glow orb */
.lib-hero-glow-tl {
    position: absolute; top: -4rem; left: -4rem;
    width: 16rem; height: 16rem;
    border-radius: 50%;
    background: radial-gradient(circle,
        oklch(90% .032 70 / .22) 0%,
        transparent 65%);
    pointer-events: none;
}

/* Hero inner layout */
.lib-hero-inner {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    padding: 3.25rem 3.5rem;
}
@media (max-width: 639px) {
    .lib-hero-inner { padding: 2.25rem 1.75rem; }
}



/* ================================================================
   EYEBROW BADGE
   ================================================================ */
.lib-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.32rem 0.80rem;
    border-radius: var(--r-pill);
    border: 1px solid oklch(44% .064 54 / .20);
    background: linear-gradient(135deg, oklch(99.0% .006 74) 0%, oklch(97.2% .012 70) 100%);
    color: var(--p-700);
    font-size: 0.625rem;
    font-weight: 700;
    letter-spacing: 0.20em;
    text-transform: uppercase;
    box-shadow:
        0 1px 0 oklch(100% 0 0 / .65) inset,
        0 2px 8px oklch(44% .064 54 / .08);
}

/* ================================================================
   HERO HEADING & SUBTEXT
   ================================================================ */
.lib-hero-title {
    font-family: var(--font-serif);
    font-size: clamp(1.6rem, 3.5vw, 2.45rem);
    font-weight: 700;
    line-height: 1.32;
    color: var(--tx-title);
    max-width: 44rem;
}

.lib-hero-title .highlight {
    background: linear-gradient(120deg, oklch(44% .064 54) 0%, oklch(58% .056 56) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-style: italic;
}

.lib-hero-sub {
    font-size: 0.90rem;
    line-height: 1.75;
    color: var(--tx-muted);
    max-width: 54rem;
    font-weight: 400;
}

/* ================================================================
   CTA BUTTON
   ================================================================ */
.lib-btn-cta {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.65rem 1.35rem;
    border-radius: var(--r-sm);
    border: 1px solid oklch(36% .056 50 / .35);
    background: var(--g-primary);
    color: oklch(98% .004 76);
    font-size: 0.80rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    text-decoration: none;
    box-shadow: var(--sh-btn);
    transition: transform 0.24s cubic-bezier(.4,0,.2,1),
                box-shadow 0.24s cubic-bezier(.4,0,.2,1);
}
/* Glass sheen */
.lib-btn-cta::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(160deg, oklch(100% 0 0 / .10) 0%, transparent 55%);
    pointer-events: none;
}
.lib-btn-cta:hover {
    transform: translateY(-2px);
    box-shadow: var(--sh-btn-h);
}
.lib-btn-cta .cta-icon { transition: transform .22s ease; }
.lib-btn-cta:hover .cta-icon { transform: translateY(2px); }

/* ================================================================
   SECTION HEADER
   ================================================================ */
.lib-section-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--bd-subtle);
}

.lib-section-label {
    display: flex;
    align-items: center;
    gap: 0.45rem;
}

.lib-accent-bar {
    display: inline-block;
    width: 3px;
    height: 1.1em;
    border-radius: 2px;
    background: var(--g-accent);
    flex-shrink: 0;
}

.lib-section-eyebrow {
    font-size: 0.595rem;
    font-weight: 700;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--tx-muted);
}

.lib-section-title {
    font-family: var(--font-serif);
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--tx-title);
    line-height: 1.3;
    margin-top: 0.25rem;
}

.lib-page-counter {
    font-size: 0.78rem;
    color: var(--tx-faint);
    font-weight: 400;
    white-space: nowrap;
}
.lib-page-counter strong {
    font-weight: 700;
    background: linear-gradient(120deg, oklch(44% .064 54) 0%, oklch(58% .056 56) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ================================================================
   BOOK CARD  — clean, minimal
   ================================================================ */
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

/* Thin top accent indicator expanding from left to right on hover (reduced speed) */
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

/* Card body */
.lib-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    padding: 1.2rem 1.4rem;
}

/* Topic chip */
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

/* Card title */
.lib-card-title {
    font-family: var(--font-serif);
    font-size: 0.975rem;
    font-weight: 600;
    line-height: 1.45;
    color: var(--tx-title);
    text-decoration: none;
    transition: color .2s ease;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.lib-card:hover .lib-card-title { color: var(--p-600); }

/* Author row */
.lib-card-author {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.76rem;
    font-weight: 400;
    color: var(--tx-muted);
    font-style: italic;
    margin-top: auto;
}
.lib-card-author svg { flex-shrink: 0; }

/* Card footer — minimal, borderless */
.lib-card-footer {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.4rem;
    border-top: 1px solid oklch(93% .010 72);
}

/* Read link — text-only, with arrow */
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

/* External source link */
.lib-ext-link {
    display: inline-flex;
    align-items: center;
    gap: 0.28rem;
    font-size: 0.72rem;
    font-weight: 400;
    color: var(--tx-faint);
    text-decoration: none;
    transition: color .18s ease;
    margin-left: auto;
}
.lib-ext-link:hover { color: var(--p-600); }

/* ================================================================
   EMPTY STATE
   ================================================================ */
.lib-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 3.5rem 2rem;
    border: 1px dashed oklch(84% .022 68);
    border-radius: var(--r-lg);
    background: linear-gradient(135deg,
        oklch(99.2% .006 76 / .55) 0%,
        oklch(97.5% .014 70 / .40) 100%);
    color: var(--tx-muted);
    text-align: center;
}
.lib-empty svg { opacity: .35; }
.lib-empty p {
    font-family: var(--font-serif);
    font-style: italic;
    font-size: 0.92rem;
}

/* ================================================================
   PAGINATION
   ================================================================ */
.lib-pagi-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    padding-top: 1.75rem;
}

/* Shared base for btn / active / disabled */
.lib-pagi-btn,
.lib-pagi-active,
.lib-pagi-disabled {
    width: 2.10rem; height: 2.10rem;
    display: flex; align-items: center; justify-content: center;
    border-radius: var(--r-sm);
    font-size: 0.76rem;
    font-weight: 600;
    flex-shrink: 0;
}

.lib-pagi-btn {
    border: 1px solid var(--bd-subtle);
    background: oklch(99.4% .003 76);
    color: var(--tx-muted);
    text-decoration: none;
    transition: border-color .18s ease, background .18s ease,
                color .18s ease, transform .18s ease;
}
.lib-pagi-btn:hover {
    border-color: oklch(76% .028 64);
    color: var(--p-700);
    background: oklch(96.5% .012 70);
    transform: translateY(-1px);
}

.lib-pagi-active {
    border: 1.5px solid oklch(38% .058 52 / .30);
    background: var(--g-primary);
    color: oklch(98% .004 76);
    font-weight: 700;
    box-shadow:
        0 1px 0 oklch(100% 0 0 / .14) inset,
        0 3px 10px -2px oklch(44% .064 54 / .38);
}

.lib-pagi-disabled {
    border: 1px solid var(--bd-subtle);
    background: oklch(99.0% .004 76 / .40);
    color: oklch(82% .008 70);
    opacity: .45;
    cursor: not-allowed;
}

.lib-pagi-ellipsis {
    width: 2.10rem; height: 2.10rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.78rem;
    color: var(--tx-faint);
    letter-spacing: 0.05em;
    user-select: none;
}

/* ================================================================
   MICRO-ANIMATIONS
   ================================================================ */
@keyframes lib-rise {
    from { opacity: 0; transform: translateY(18px) scale(.98); }
    to   { opacity: 1; transform: translateY(0)    scale(1);   }
}
.lib-anim { animation: lib-rise .46s cubic-bezier(.34, 1.20, .64, 1) both; }

@keyframes lib-fade-in {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.lib-hero-inner { animation: lib-fade-in .55s ease both; }

</style>
@endpush

@section('content')
<div class="lib-page space-y-8">

    {{-- ================================================================
         HERO BANNER — Bố cục gốc được giữ nguyên
         ================================================================ --}}
    <div class="lib-hero">
        {{-- Ambient glows --}}
        <div class="lib-hero-glow-tl"></div>



        <div class="lib-hero-inner">

            {{-- Eyebrow badge --}}
            <div>
                <span class="lib-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                    </svg>
                    Kho tư liệu mở
                </span>
            </div>

            {{-- Main heading --}}
            <h1 class="lib-hero-title serif">
                Bộ sưu tập văn bản <span class="highlight">chọn lọc</span>
                <br>
                dành cho học tập &amp; giảng dạy
            </h1>

            {{-- Sub-description --}}
            <p class="lib-hero-sub">
                Khám phá kho văn học phong phú — thơ, văn xuôi, truyện ngắn, kịch và
                nhiều thể loại khác nữa.
            </p>

            {{-- CTA row --}}
            <div class="flex flex-wrap items-center gap-3">
                <a href="#lib-texts" class="lib-btn-cta">
                    Khám phá ngay
                </a>
            </div>

        </div>
    </div>

    {{-- ================================================================
         SECTION HEADER
         ================================================================ --}}
    <div id="lib-texts" class="lib-section-header">

        <div>
            <div class="lib-section-label">
                <span class="lib-accent-bar"></span>
                <span class="lib-section-eyebrow">Thư viện</span>
            </div>
            <h2 class="lib-section-title">Danh sách văn bản</h2>
        </div>

        <p class="lib-page-counter">
            <strong>{{ $texts->total() }}</strong> tác phẩm
        </p>
    </div>

    {{-- ================================================================
         BOOK GRID
         ================================================================ --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

        @forelse ($texts as $index => $text)
            @php
                $textUrl = auth()->check() && auth()->user()->role === 'teacher'
                    ? route('admin.texts.writer.edit', $text)
                    : route('texts.content.show', $text);
                $delay = ($index % 6) * 65;
            @endphp

            <article class="lib-card lib-anim" style="animation-delay: {{ $delay }}ms;">

                <div class="lib-card-body">

                    {{-- Topic chip --}}
                    <span class="lib-topic-chip">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                        </svg>
                        {{ $text->textTopic?->name ?? 'Chưa phân loại' }}
                    </span>

                    {{-- Title --}}
                    <a href="{{ $textUrl }}" class="lib-card-title">{{ $text->name }}</a>

                    {{-- Author --}}
                    <div class="lib-card-author">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                        {{ $text->author }}
                    </div>

                </div>

                {{-- Card footer — clean text links --}}
                <div class="lib-card-footer">
                    <a href="{{ $textUrl }}" class="lib-read-link">
                        Đọc
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    @if ($text->read_link)
                        <a href="{{ $text->read_link }}" target="_blank" rel="noopener noreferrer" class="lib-ext-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                            Mở link
                        </a>
                    @endif
                </div>

            </article>

        @empty
            <div class="lib-empty md:col-span-2 xl:col-span-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                          d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                </svg>
                <p>Chưa có văn bản nào trong mục này.</p>
            </div>
        @endforelse

    </div>

    {{-- ================================================================
         PAGINATION — rendered by resources/js/library/pagination.js
         ================================================================ --}}
    @if ($texts->lastPage() > 1)
        <div
            class="lib-pagi-wrap"
            data-pagination
            data-current-page="{{ $texts->currentPage() }}"
            data-last-page="{{ $texts->lastPage() }}"
            data-base-url="{{ url()->current() }}"
            data-param="page"
            data-window="2"
            aria-label="Phân trang"
        ></div>
    @endif

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/library/pagination.js') }}"></script>
@endpush