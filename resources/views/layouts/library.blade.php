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
                background: rgba(241, 245, 249, 0.72);
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
                border: 3px solid #cbd5e1;
                border-top-color: #2563eb;
                animation: pageLoadingSpin 0.7s linear infinite;
            }

            @keyframes pageLoadingSpin {
                to {
                    transform: rotate(360deg);
                }
            }
        </style>
        @stack('styles')
    </head>
    <body data-theme="library" class="bg-base-200">
        <div id="page-loading-overlay" aria-live="polite" aria-label="Đang tải dữ liệu">
            <div id="page-loading-spinner" role="status"></div>
        </div>

        <div class="flex min-h-screen flex-col">
            @include('library.partials.header')

            <main class="flex-1 bg-base-200">
                <div class="mx-auto w-full max-w-7xl p-4">
                    @if (session('status'))
                        <div class="mb-4 rounded-sm border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-sm border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
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
            <div class="modal-box max-w-md rounded-sm bg-white p-0 shadow-2xl">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Xác nhận xóa</h3>
                </div>
                <div class="px-5 py-4 text-sm text-slate-600">
                    <p id="confirm-delete-message">Bạn có chắc chắn muốn xóa bản ghi này?</p>
                    <p class="mt-2 text-sm font-medium text-blue-600">
                        Lưu ý: Hệ thống sẽ xóa luôn các dữ liệu liên quan của mục này. Hành động này không thể hoàn tác.
                    </p>
                </div>
                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" id="confirm-delete-cancel">
                        Hủy
                    </button>
                    <button type="button" class="btn btn-sm !h-10 min-h-10 rounded-sm border-0 bg-blue-600 px-4 text-white shadow-none hover:bg-blue-700" id="confirm-delete-submit">
                        Xóa
                    </button>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button aria-label="close" class="sr-only">close</button>
            </form>
        </dialog>

        <dialog id="global-search-modal" class="modal">
            <div class="modal-box max-w-2xl rounded-sm bg-white p-0 shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Tìm kiếm</h3>
                    <button
                        type="button"
                        id="global-search-close"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-sm text-slate-500 hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Đóng tìm kiếm"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-4 px-5 py-4">
                    <input
                        id="global-search-input"
                        type="text"
                        class="input input-sm !h-11 min-h-11 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                        placeholder="Nhập từ khóa để tìm..."
                        autocomplete="off"
                    />
                    <div id="global-search-state" class="text-sm text-slate-500">Nhập để bắt đầu tìm kiếm.</div>
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
