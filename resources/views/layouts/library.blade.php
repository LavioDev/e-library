<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Thư viện văn bản văn học mở rộng')</title>

        <script>
            (() => {
                try {
                    if (sessionStorage.getItem('page_loading') === '1') {
                        document.documentElement.classList.add('page-loading-active');
                    }
                } catch (_) {}
            })();
        </script>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @import url('https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..700;1,400..700&family=Lora:ital,wght@0,400..700;1,400..700&display=swap');

            :root {
                --font-ui: 'Bahnschrift', 'Segoe UI', ui-sans-serif, system-ui, sans-serif;
                --font-serif: 'EB Garamond', 'Lora', 'Georgia', ui-serif, serif;

                /* — Core tokens for global components (like pagination) — */
                --r-sm:  0.5rem;
                --bd-subtle: oklch(91% 0.010 70);
                --tx-muted:  oklch(46% 0.018 58);
                --tx-faint:  oklch(64% 0.012 62);
                --p-700: oklch(34% 0.055 50);
                --g-primary: linear-gradient(145deg, oklch(44% 0.064 54) 0%, oklch(36% 0.056 50) 100%);
            }

            body, button, input, select, textarea {
                font-family: var(--font-ui);
            }

            .serif, .font-serif {
                font-family: var(--font-serif) !important;
            }

            .sans, .font-sans {
                font-family: var(--font-ui) !important;
            }

            summary::-webkit-details-marker {
                display: none;
            }

            #page-loading-overlay {
                position: fixed;
                inset: 0;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                background: oklch(97% 0.010 76 / 0.80);
                backdrop-filter: blur(2px);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: opacity 140ms ease;
            }

            .page-loading-active #page-loading-overlay {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }

            #page-loading-spinner {
                width: 2.6rem;
                height: 2.6rem;
                border-radius: 9999px;
                border: 3px solid oklch(86% 0.020 72);
                border-top-color: oklch(40% 0.068 54);
                animation: pageLoadingSpin 0.7s linear infinite;
            }

            @keyframes pageLoadingSpin {
                to {
                    transform: rotate(360deg);
                }
            }

            /* ================================================================
               GLOBAL PAGINATION STYLE
               ================================================================ */
            .lib-pagi-wrap {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.35rem;
                padding-top: 1.75rem;
            }

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

            /* Enforce pure white background for all table body rows */
            .table tbody tr {
                background-color: oklch(100% 0 0) !important;
            }
        </style>
        @stack('styles')
    </head>
    <body data-theme="library" data-layout="library" class="bg-base-200">
        <div id="page-loading-overlay" aria-live="polite" aria-label="Đang tải dữ liệu">
            <div id="page-loading-spinner" role="status"></div>
        </div>

        <div class="flex min-h-screen flex-col">
            @include('library.partials.header')

            <main class="flex-1 bg-base-200">
                <div class="mx-auto w-full max-w-7xl p-4">
                    @if (session('status'))
                        <div class="mb-4 flex items-center gap-2 rounded-xl border px-4 py-3 text-sm" style="border-color:oklch(75% 0.080 155 / 0.4); background:oklch(96% 0.022 155 / 0.5); color:oklch(34% 0.072 155);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 flex items-center gap-2 rounded-xl border px-4 py-3 text-sm" style="border-color:oklch(72% 0.090 42 / 0.4); background:oklch(97% 0.018 58 / 0.6); color:oklch(38% 0.080 42);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (!request()->routeIs('home'))
                        @hasSection('breadcrumbs')
                            @yield('breadcrumbs')
                        @else
                            @include('library.partials.breadcrumbs')
                        @endif
                    @endif

                    @yield('content')
                </div>
            </main>

            @include('library.partials.footer')
        </div>

        <dialog id="confirm-delete-modal" class="modal">
            <div class="modal-box max-w-md p-0 shadow-2xl" style="background:oklch(99.4% 0.005 78); border:1px solid oklch(88% 0.020 72); border-radius:16px;">
                <div class="px-5 py-4" style="border-bottom:1px solid oklch(90% 0.018 74);">
                    <h3 class="text-base font-semibold" style="color:oklch(18% 0.020 58);">Xác nhận xóa</h3>
                </div>
                <div class="px-5 py-4 text-sm" style="color:oklch(44% 0.025 64);">
                    <p id="confirm-delete-message">Bạn có chắc chắn muốn xóa bản ghi này?</p>
                    <p class="mt-2 text-xs font-medium rounded-lg px-3 py-2" style="color:oklch(38% 0.080 42); background:oklch(97% 0.018 58 / 0.5); border:1px solid oklch(82% 0.045 52 / 0.4);">
                        Hành động này không thể hoàn tác.
                    </p>
                </div>
                <div class="modal-action mt-0 px-5 py-4" style="border-top:1px solid oklch(90% 0.018 74);">
                    <button type="button" id="confirm-delete-cancel" class="btn btn-sm !h-9 min-h-9 rounded-lg px-4 shadow-none" style="background:oklch(95% 0.012 75); border:1px solid oklch(86% 0.020 72); color:oklch(36% 0.025 62);">
                        Hủy
                    </button>
                    <button type="button" id="confirm-delete-submit" class="btn btn-sm !h-9 min-h-9 rounded-lg border-0 px-4 text-white shadow-none" style="background:oklch(58% 0.140 24); hover:background:oklch(50% 0.140 24);">
                        Xóa
                    </button>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button aria-label="close" class="sr-only">close</button>
            </form>
        </dialog>

        <dialog id="global-search-modal" class="modal">
            <div class="modal-box max-w-2xl p-0 shadow-2xl" style="background:oklch(99.4% 0.005 78); border:1px solid oklch(88% 0.020 72); border-radius:16px;">
                <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid oklch(90% 0.018 74);">
                    <h3 class="text-base font-semibold" style="color:oklch(18% 0.020 58); font-family:var(--font-serif);">Tìm kiếm</h3>
                    <button
                        type="button"
                        id="global-search-close"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition"
                        style="color:oklch(55% 0.025 65);"
                        aria-label="Đóng tìm kiếm"
                        onmouseover="this.style.background='oklch(93% 0.016 74)'"
                        onmouseout="this.style.background=''"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="space-y-3 px-5 py-4">
                    <input
                        id="global-search-input"
                        type="text"
                        class="input input-sm !h-11 min-h-11 w-full rounded-xl text-sm shadow-none"
                        style="border:1px solid oklch(86% 0.020 72); background:oklch(97% 0.010 76); color:oklch(20% 0.022 60);"
                        placeholder="Tìm tác phẩm, tác giả…"
                        autocomplete="off"
                    />
                    <div id="global-search-state" class="text-sm" style="color:oklch(58% 0.025 66);">Nhập để bắt đầu tìm kiếm.</div>
                    <div id="global-search-results" class="hidden max-h-[55vh] overflow-auto"></div>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button aria-label="close" class="sr-only">close</button>
            </form>
        </dialog>

        <script>
            (() => {
                const storageKey = 'page_loading';
                const root = document.documentElement;
                const loaderDelayMs = 120;
                const loaderHardTimeoutMs = 15000;
                let loaderDelayTimer = null;
                let loaderHardTimer = null;

                const showLoading = () => {
                    root.classList.add('page-loading-active');
                    try {
                        sessionStorage.setItem(storageKey, '1');
                    } catch (_) {}
                };

                const hideLoading = () => {
                    if (loaderDelayTimer) {
                        clearTimeout(loaderDelayTimer);
                        loaderDelayTimer = null;
                    }
                    if (loaderHardTimer) {
                        clearTimeout(loaderHardTimer);
                        loaderHardTimer = null;
                    }
                    root.classList.remove('page-loading-active');
                    try {
                        sessionStorage.removeItem(storageKey);
                    } catch (_) {}
                };

                const queueLoading = () => {
                    if (loaderDelayTimer) return;

                    loaderDelayTimer = window.setTimeout(() => {
                        loaderDelayTimer = null;
                        showLoading();
                        loaderHardTimer = window.setTimeout(() => {
                            hideLoading();
                        }, loaderHardTimeoutMs);
                    }, loaderDelayMs);
                };

                const shouldIgnoreLink = (link, event) => {
                    if (!link) return true;
                    if (event.defaultPrevented) return true;
                    if (event.button !== 0) return true;
                    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return true;
                    if (link.target && link.target !== '_self') return true;
                    if (link.hasAttribute('download')) return true;
                    if (link.getAttribute('data-no-loader') === '1') return true;

                    const href = link.getAttribute('href') ?? '';
                    if (href === '' || href.startsWith('#') || href.startsWith('javascript:')) return true;

                    try {
                        const url = new URL(link.href, window.location.href);
                        if (url.origin !== window.location.origin) return true;

                        const current = `${window.location.pathname}${window.location.search}${window.location.hash}`;
                        const next = `${url.pathname}${url.search}${url.hash}`;
                        if (current === next) return true;
                    } catch (_) {
                        return true;
                    }

                    return false;
                };

                document.addEventListener('click', (event) => {
                    const link = event.target instanceof Element ? event.target.closest('a[href]') : null;
                    if (shouldIgnoreLink(link, event)) return;
                    queueLoading();
                });

                document.addEventListener('submit', (event) => {
                    const form = event.target;
                    if (!(form instanceof HTMLFormElement)) return;
                    if (form.getAttribute('data-no-loader') === '1') return;
                    if (form.target && form.target !== '_self') return;
                    if ((form.getAttribute('method') ?? 'get').toLowerCase() === 'dialog') return;
                    if (event.defaultPrevented) return;
                    queueLoading();
                });

                window.addEventListener('pageshow', hideLoading);
                window.addEventListener('load', hideLoading);
            })();
        </script>

        <script>
            (() => {
                const modal = document.getElementById('confirm-delete-modal');
                const messageEl = document.getElementById('confirm-delete-message');
                const cancelButton = document.getElementById('confirm-delete-cancel');
                const submitButton = document.getElementById('confirm-delete-submit');
                let pendingForm = null;

                if (!modal || !messageEl || !cancelButton || !submitButton) return;

                document.addEventListener('submit', (event) => {
                    const form = event.target;
                    if (!(form instanceof HTMLFormElement)) return;
                    if (form.dataset.confirmDelete !== 'true') return;
                    if (form.dataset.confirmed === 'true') {
                        form.dataset.confirmed = 'false';
                        return;
                    }

                    event.preventDefault();
                    pendingForm = form;
                    messageEl.textContent = form.dataset.confirmMessage || 'Bạn có chắc chắn muốn xóa bản ghi này?';
                    modal.showModal();
                });

                submitButton.addEventListener('click', () => {
                    if (!pendingForm) return;
                    pendingForm.dataset.confirmed = 'true';
                    modal.close();
                    pendingForm.requestSubmit();
                });

                const closeModal = () => {
                    pendingForm = null;
                    modal.close();
                };

                cancelButton.addEventListener('click', closeModal);
                modal.addEventListener('close', () => {
                    pendingForm = null;
                });
            })();
        </script>

        <script>
            (() => {
                const trigger = document.getElementById('global-search-trigger');
                const modal = document.getElementById('global-search-modal');
                const closeButton = document.getElementById('global-search-close');
                const input = document.getElementById('global-search-input');
                const stateEl = document.getElementById('global-search-state');
                const resultsEl = document.getElementById('global-search-results');
                const endpoint = trigger?.dataset.searchEndpoint ?? '';
                const debounceMs = 300;
                let debounceTimer = null;
                let requestId = 0;

                if (!trigger || !modal || !closeButton || !input || !stateEl || !resultsEl || !endpoint) {
                    return;
                }

                const setState = (message) => {
                    stateEl.textContent = message;
                    stateEl.classList.remove('hidden');
                };

                const clearResults = () => {
                    resultsEl.innerHTML = '';
                    resultsEl.classList.add('hidden');
                };

                const renderGroups = (groups) => {
                    const filteredGroups = groups.filter((group) => Array.isArray(group.items) && group.items.length > 0);
                    if (filteredGroups.length === 0) {
                        clearResults();
                        setState('Không tìm thấy kết quả phù hợp.');
                        return;
                    }

                    stateEl.classList.add('hidden');
                    resultsEl.classList.remove('hidden');
                    resultsEl.innerHTML = filteredGroups.map((group) => {
                        const items = group.items.map((item) => `
                            <a href="${item.url}" class="block rounded-sm px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 focus:bg-slate-100 focus:outline-none">
                                ${item.name}
                            </a>
                        `).join('');

                        return `
                            <section class="mb-4 last:mb-0">
                                <h4 class="mb-2 text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">${group.label}</h4>
                                <div class="space-y-1">${items}</div>
                            </section>
                        `;
                    }).join('');
                };

                const performSearch = async (keyword) => {
                    requestId += 1;
                    const currentRequestId = requestId;

                    setState('Đang tìm kiếm...');
                    clearResults();

                    try {
                        const url = new URL(endpoint, window.location.origin);
                        url.searchParams.set('keyword', keyword);
                        const response = await fetch(url.toString(), {
                            headers: {
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Request failed');
                        }

                        const payload = await response.json();
                        if (currentRequestId !== requestId) {
                            return;
                        }

                        renderGroups(Array.isArray(payload.groups) ? payload.groups : []);
                    } catch (_) {
                        if (currentRequestId !== requestId) {
                            return;
                        }
                        clearResults();
                        setState('Không thể tìm kiếm lúc này. Vui lòng thử lại.');
                    }
                };

                const scheduleSearch = (keyword) => {
                    if (debounceTimer !== null) {
                        window.clearTimeout(debounceTimer);
                    }

                    debounceTimer = window.setTimeout(() => {
                        debounceTimer = null;
                        performSearch(keyword);
                    }, debounceMs);
                };

                const resetModal = () => {
                    if (debounceTimer !== null) {
                        window.clearTimeout(debounceTimer);
                        debounceTimer = null;
                    }
                    requestId += 1;
                    input.value = '';
                    clearResults();
                    setState('Nhập để bắt đầu tìm kiếm.');
                };

                trigger.addEventListener('click', () => {
                    modal.showModal();
                    window.setTimeout(() => input.focus(), 0);
                });

                closeButton.addEventListener('click', () => {
                    modal.close();
                });

                input.addEventListener('input', () => {
                    const keyword = input.value.trim();
                    if (keyword === '') {
                        if (debounceTimer !== null) {
                            window.clearTimeout(debounceTimer);
                            debounceTimer = null;
                        }
                        requestId += 1;
                        clearResults();
                        setState('Nhập để bắt đầu tìm kiếm.');
                        return;
                    }

                    scheduleSearch(keyword);
                });

                modal.addEventListener('close', resetModal);
            })();
        </script>

        @stack('scripts')
    </body>
</html>
