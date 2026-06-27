<header class="overflow-visible border-b border-slate-200 bg-white/95 text-slate-700 shadow-[0_12px_35px_-28px_rgba(15,23,42,0.35)] backdrop-blur">
    <div class="mx-auto flex w-full max-w-7xl flex-nowrap items-center gap-3 overflow-visible px-4 py-4">
        <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-3 rounded-sm py-2 transition hover:bg-gray-50">
            <span class="flex size-9 shrink-0 items-center justify-center rounded-sm bg-blue-600 text-white shadow-[0_12px_24px_-16px_rgba(37,99,235,0.9)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.75 6.75A2.75 2.75 0 0 1 7.5 4h9A2.75 2.75 0 0 1 19.25 6.75v10.5A2.75 2.75 0 0 1 16.5 20h-9a2.75 2.75 0 0 1-2.75-2.75V6.75Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 8.5h7.5M8.25 12h7.5M8.25 15.5h4.5" />
                </svg>
            </span>
            <span class="min-w-0">
                <span class="block text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-blue-600">E-Library</span>
                <span class="block truncate font-semibold uppercase text-slate-900">Thư viện văn học mở rộng</span>
            </span>
        </a>

        <div class="ml-auto shrink-0">
            <nav>
                <ul class="flex flex-nowrap items-center gap-1 text-sm font-medium text-slate-700">
                    <li><a href="{{ route('home') }}" class="inline-flex rounded-sm px-3 py-2 hover:bg-slate-100">Trang chủ</a></li>
                    @auth
                        @if (auth()->user()->role === 'teacher')
                            <li class="relative dropdown">
                                <details class="group">
                                    <summary class="inline-flex cursor-pointer items-center gap-1 rounded-sm px-3 py-2 transition-colors hover:bg-slate-100 list-none [&::-webkit-details-marker]:hidden whitespace-nowrap">
                                        <span>Văn bản</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-slate-400 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </summary>
                                    <ul class="dropdown-content absolute left-0 top-full z-[1000] mt-2 w-56 rounded-sm border border-slate-200 bg-white p-2 shadow-[0_20px_48px_-24px_rgba(15,23,42,0.22)]">
                                        <li>
                                            <a href="{{ route('admin.texts.index') }}" class="block rounded-sm px-3 py-2 text-sm text-slate-700 transition-colors hover:bg-slate-100 whitespace-nowrap">
                                                Danh sách văn bản
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.text-topics.index') }}" class="block rounded-sm px-3 py-2 text-sm text-slate-700 transition-colors hover:bg-slate-100 whitespace-nowrap">
                                                Loại văn bản
                                            </a>
                                        </li>
                                    </ul>
                                </details>
                            </li>
                            <li class="relative dropdown">
                                <details class="group">
                                    <summary class="inline-flex cursor-pointer items-center gap-1 rounded-sm px-3 py-2 transition-colors hover:bg-slate-100 list-none [&::-webkit-details-marker]:hidden whitespace-nowrap">
                                        <span>Nhiệm vụ đọc hiểu</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-slate-400 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </summary>
                                    <ul class="dropdown-content absolute left-0 top-full z-[1000] mt-2 min-w-64 rounded-sm border border-slate-200 bg-white p-2 shadow-[0_20px_48px_-24px_rgba(15,23,42,0.22)]">
                                        <li>
                                            <a href="{{ route('admin.reading-classes.index') }}" class="block rounded-sm px-3 py-2 text-sm font-medium text-slate-800 transition-colors hover:bg-slate-100 whitespace-nowrap">
                                                Danh sách Nhiệm vụ đọc hiểu
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.assignments.index') }}" class="block rounded-sm px-3 py-2 text-sm font-medium text-slate-800 transition-colors hover:bg-slate-100 whitespace-nowrap">
                                                Bộ câu hỏi
                                            </a>
                                        </li>
                                    </ul>
                                </details>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="inline-flex rounded-sm px-3 py-2 hover:bg-slate-100">
                                    Người dùng
                                </a>
                            </li>
                        @elseif (auth()->user()->role === 'user')
                            <li>
                                <a href="{{ route('home') }}" class="inline-flex rounded-sm px-3 py-2 hover:bg-slate-100">
                                    Văn bản
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.reading-classes.index') }}" class="inline-flex rounded-sm px-3 py-2 hover:bg-slate-100">
                                    Nhiệm vụ đọc hiểu
                                </a>
                            </li>
                        @endif
                    @else
                        <li>
                            <a href="{{ route('login') }}" class="inline-flex rounded-sm px-3 py-2 hover:bg-slate-100">
                                Đăng nhập
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="inline-flex rounded-sm px-3 py-2 hover:bg-slate-100">
                                Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </nav>
        </div>

        <div class="flex shrink-0 items-center gap-1">
            <button
                type="button"
                id="global-search-trigger"
                class="btn btn-ghost btn-circle"
                aria-label="Tìm kiếm"
                @auth data-search-endpoint="{{ route('search.modal') }}" @endauth
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            @auth
                <div class="relative">
                    <details class="group">
                        <summary class="btn btn-ghost btn-circle h-auto min-h-0 p-0 shadow-none">
                            <span class="flex size-9 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </summary>
                        <div class="absolute right-0 top-full z-[1000] mt-3 w-56 rounded-box border border-slate-200 bg-base-100 p-2 shadow-[0_24px_48px_-24px_rgba(15,23,42,0.35)]">
                            <div class="px-3 py-2">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('account.show') }}" class="block w-full rounded-sm px-3 py-2 text-left text-sm font-medium text-slate-700 hover:bg-slate-100">
                                Tài khoản của tôi
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full rounded-sm px-3 py-2 text-left text-sm font-medium text-slate-700 hover:bg-slate-100">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-circle" aria-label="Đăng nhập">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 19.25a7.5 7.5 0 0 1 15 0" />
                    </svg>
                </a>
            @endauth
        </div>
    </div>
</header>

<script>
    document.addEventListener('click', function(e) {
        document.querySelectorAll('header details').forEach(function(details) {
            if (!details.contains(e.target)) {
                details.removeAttribute('open');
            }
        });
    });
</script>
