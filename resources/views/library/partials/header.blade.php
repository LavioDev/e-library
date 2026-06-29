<!-- Drawer Toggle Checkbox -->
<input type="checkbox" id="mobile-menu-drawer" class="peer hidden" />

<header style="position: relative; z-index: 50; background:oklch(99.4% 0.005 78 / 0.96); border-bottom:1px solid oklch(89% 0.018 72); backdrop-filter:blur(12px); box-shadow:0 8px 28px -16px oklch(35% 0.025 62 / 0.14);">
    <div class="mx-auto flex w-full max-w-7xl flex-nowrap items-center gap-3 overflow-visible px-4 py-3">
        <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-3 rounded-xl py-1.5 px-2 transition" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl shadow-sm" style="background:oklch(40% 0.068 54); color:oklch(98% 0.005 78); box-shadow:0 6px 16px -6px oklch(40% 0.068 54 / 0.5);">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.75 6.75A2.75 2.75 0 0 1 7.5 4h9A2.75 2.75 0 0 1 19.25 6.75v10.5A2.75 2.75 0 0 1 16.5 20h-9a2.75 2.75 0 0 1-2.75-2.75V6.75Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 8.5h7.5M8.25 12h7.5M8.25 15.5h4.5" />
                </svg>
            </span>
            <span class="min-w-0">
                <span class="block text-[0.6rem] font-bold uppercase tracking-[0.22em]" style="color:oklch(50% 0.055 56);">E-Library</span>
                <span class="block truncate font-semibold uppercase text-sm" style="color:oklch(18% 0.020 58); font-family:var(--font-serif); letter-spacing:-0.01em;">Thư viện văn học</span>
            </span>
        </a>

        <!-- Desktop Navigation -->
        <div class="ml-auto shrink-0 lg-nav-hide">
            <nav>
                <ul class="flex flex-nowrap items-center gap-1 text-sm font-medium" style="color:oklch(36% 0.025 64);">
                    <li>
                        <a href="{{ route('home') }}" class="inline-flex rounded-lg px-3 py-2 transition" onmouseover="this.style.background='oklch(94% 0.014 74)'" onmouseout="this.style.background=''">Trang chủ</a>
                    </li>
                    @auth
                        @if (auth()->user()->role === 'teacher')
                            <li class="relative dropdown">
                                <details class="group">
                                    <summary class="inline-flex cursor-pointer items-center gap-1 rounded-lg px-3 py-2 transition list-none [&::-webkit-details-marker]:hidden whitespace-nowrap" onmouseover="this.style.background='oklch(94% 0.014 74)'" onmouseout="this.style.background=''">
                                        <span>Văn bản</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:oklch(62% 0.022 68);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </summary>
                                    <ul class="dropdown-content absolute left-0 top-full z-[1000] mt-2 w-56 rounded-xl border p-2" style="border-color:oklch(88% 0.018 72); background:oklch(99.4% 0.005 78); box-shadow:0 20px 48px -20px oklch(30% 0.022 60 / 0.20);">
                                        <li><a href="{{ route('admin.texts.index') }}" class="block rounded-lg px-3 py-2 text-sm transition whitespace-nowrap" style="color:oklch(30% 0.022 62);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Danh sách văn bản</a></li>
                                        <li><a href="{{ route('admin.text-topics.index') }}" class="block rounded-lg px-3 py-2 text-sm transition whitespace-nowrap" style="color:oklch(30% 0.022 62);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Loại văn bản</a></li>
                                    </ul>
                                </details>
                            </li>
                            <li class="relative dropdown">
                                <details class="group">
                                    <summary class="inline-flex cursor-pointer items-center gap-1 rounded-lg px-3 py-2 transition list-none [&::-webkit-details-marker]:hidden whitespace-nowrap" onmouseover="this.style.background='oklch(94% 0.014 74)'" onmouseout="this.style.background=''">
                                        <span>Nhiệm vụ đọc hiểu</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:oklch(62% 0.022 68);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </summary>
                                    <ul class="dropdown-content absolute left-0 top-full z-[1000] mt-2 min-w-64 rounded-xl border p-2" style="border-color:oklch(88% 0.018 72); background:oklch(99.4% 0.005 78); box-shadow:0 20px 48px -20px oklch(30% 0.022 60 / 0.20);">
                                        <li><a href="{{ route('admin.reading-classes.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium transition whitespace-nowrap" style="color:oklch(26% 0.022 60);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Danh sách Nhiệm vụ đọc hiểu</a></li>
                                        <li><a href="{{ route('admin.assignments.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium transition whitespace-nowrap" style="color:oklch(26% 0.022 60);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Bộ câu hỏi</a></li>
                                    </ul>
                                </details>
                            </li>
                            <li><a href="{{ route('admin.users.index') }}" class="inline-flex rounded-lg px-3 py-2 transition" onmouseover="this.style.background='oklch(94% 0.014 74)'" onmouseout="this.style.background=''">Người dùng</a></li>
                        @elseif (auth()->user()->role === 'user')
                            <li><a href="{{ route('home') }}" class="inline-flex rounded-lg px-3 py-2 transition" onmouseover="this.style.background='oklch(94% 0.014 74)'" onmouseout="this.style.background=''">Văn bản</a></li>
                            <li><a href="{{ route('user.reading-classes.index') }}" class="inline-flex rounded-lg px-3 py-2 transition" onmouseover="this.style.background='oklch(94% 0.014 74)'" onmouseout="this.style.background=''">Nhiệm vụ đọc hiểu</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('login') }}" class="inline-flex rounded-lg px-3 py-2 transition" onmouseover="this.style.background='oklch(94% 0.014 74)'" onmouseout="this.style.background=''">Đăng nhập</a></li>
                        <li><a href="{{ route('register') }}" class="inline-flex rounded-lg px-3 py-2 transition" onmouseover="this.style.background='oklch(94% 0.014 74)'" onmouseout="this.style.background=''">Đăng ký</a></li>
                    @endauth
                </ul>
            </nav>
        </div>

        <!-- Action triggers: Search, Profile/Login, and Hamburger -->
        <div class="flex shrink-0 items-center gap-1 mobile-ml-auto desktop-ml-0">
            <button
                type="button"
                id="global-search-trigger"
                class="btn btn-ghost btn-circle"
                style="color:oklch(48% 0.030 64);"
                aria-label="Tìm kiếm"
                data-search-endpoint="{{ route('search.modal') }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            @auth
                <div class="relative">
                    <details class="group">
                        <summary class="btn btn-ghost btn-circle h-auto min-h-0 p-0 shadow-none">
                            <span class="flex size-9 items-center justify-center rounded-full font-semibold text-sm" style="background:oklch(92% 0.022 72); color:oklch(34% 0.042 60);">
                                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </summary>
                        <div class="absolute right-0 top-full z-[1000] mt-3 w-56 rounded-2xl border p-2" style="border-color:oklch(88% 0.018 72); background:oklch(99.4% 0.005 78); box-shadow:0 24px 48px -20px oklch(30% 0.022 60 / 0.22);">
                            <div class="px-3 py-2 mb-1">
                                <p class="truncate text-sm font-semibold" style="color:oklch(18% 0.020 58);">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs" style="color:oklch(58% 0.025 66);">{{ auth()->user()->email }}</p>
                            </div>
                            <div style="height:1px; background:oklch(91% 0.016 74); margin:4px 0 8px;"></div>
                            <a href="{{ route('account.show') }}" class="block w-full rounded-xl px-3 py-2 text-left text-sm font-medium transition" style="color:oklch(30% 0.022 62);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Tài khoản của tôi</a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full rounded-xl px-3 py-2 text-left text-sm font-medium transition" style="color:oklch(30% 0.022 62);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Đăng xuất</button>
                            </form>
                        </div>
                    </details>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-circle" style="color:oklch(48% 0.030 64);" aria-label="Đăng nhập">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 19.25a7.5 7.5 0 0 1 15 0" />
                    </svg>
                </a>
            @endauth

            <!-- Mobile Hamburger Button -->
            <label for="mobile-menu-drawer" class="btn btn-ghost btn-circle hamburger-hide" style="color:oklch(48% 0.030 64);" aria-label="Mở menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>
        </div>
    </div>
</header>

<!-- Drawer Backdrop -->
<div class="drawer-backdrop">
    <label for="mobile-menu-drawer" class="absolute inset-0 cursor-default"></label>

    <!-- Drawer Panel -->
    <div class="drawer-panel z-[2010]">
        <div class="flex items-center justify-between pb-4 mb-4" style="border-bottom:1px solid oklch(91% 0.016 74);">
            <span class="font-semibold uppercase text-sm" style="color:oklch(18% 0.020 58);">Menu</span>
            <label for="mobile-menu-drawer" class="btn btn-ghost btn-circle btn-sm" aria-label="Đóng menu" style="color:oklch(48% 0.030 64);">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </label>
        </div>

        <nav class="flex-1 overflow-y-auto pr-1">
            <ul class="flex flex-col gap-1 text-base font-medium" style="color:oklch(34% 0.025 64);">
                <li><a href="{{ route('home') }}" class="flex rounded-xl px-3 py-2.5 transition" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Trang chủ</a></li>
                @auth
                    @if (auth()->user()->role === 'teacher')
                        <li>
                            <details class="group">
                                <summary class="flex cursor-pointer items-center justify-between rounded-xl px-3 py-2.5 transition list-none [&::-webkit-details-marker]:hidden" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                                    <span>Văn bản</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:oklch(62% 0.022 68);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </summary>
                                <ul class="mt-1 pl-4 flex flex-col gap-1" style="border-left:2px solid oklch(90% 0.016 74);">
                                    <li><a href="{{ route('admin.texts.index') }}" class="block rounded-xl px-3 py-2 text-sm transition" style="color:oklch(42% 0.025 64);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Danh sách văn bản</a></li>
                                    <li><a href="{{ route('admin.text-topics.index') }}" class="block rounded-xl px-3 py-2 text-sm transition" style="color:oklch(42% 0.025 64);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Loại văn bản</a></li>
                                </ul>
                            </details>
                        </li>
                        <li>
                            <details class="group">
                                <summary class="flex cursor-pointer items-center justify-between rounded-xl px-3 py-2.5 transition list-none [&::-webkit-details-marker]:hidden" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                                    <span>Nhiệm vụ đọc hiểu</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:oklch(62% 0.022 68);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </summary>
                                <ul class="mt-1 pl-4 flex flex-col gap-1" style="border-left:2px solid oklch(90% 0.016 74);">
                                    <li><a href="{{ route('admin.reading-classes.index') }}" class="block rounded-xl px-3 py-2 text-sm transition" style="color:oklch(42% 0.025 64);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Danh sách Nhiệm vụ đọc hiểu</a></li>
                                    <li><a href="{{ route('admin.assignments.index') }}" class="block rounded-xl px-3 py-2 text-sm transition" style="color:oklch(42% 0.025 64);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Bộ câu hỏi</a></li>
                                </ul>
                            </details>
                        </li>
                        <li><a href="{{ route('admin.users.index') }}" class="flex rounded-xl px-3 py-2.5 transition" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Người dùng</a></li>
                    @elseif (auth()->user()->role === 'user')
                        <li><a href="{{ route('home') }}" class="flex rounded-xl px-3 py-2.5 transition" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Văn bản</a></li>
                        <li><a href="{{ route('user.reading-classes.index') }}" class="flex rounded-xl px-3 py-2.5 transition" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Nhiệm vụ đọc hiểu</a></li>
                    @endif
                @else
                    <li><a href="{{ route('login') }}" class="flex rounded-xl px-3 py-2.5 transition" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Đăng nhập</a></li>
                    <li><a href="{{ route('register') }}" class="flex rounded-xl px-3 py-2.5 transition" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Đăng ký</a></li>
                @endauth
            </ul>
        </nav>

        @auth
            <div class="pt-4 mt-4" style="border-top:1px solid oklch(91% 0.016 74);">
                <div class="px-3 py-2 mb-3 rounded-xl" style="background:oklch(95% 0.012 75);">
                    <p class="truncate text-sm font-semibold" style="color:oklch(18% 0.020 58);">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs" style="color:oklch(58% 0.025 66);">{{ auth()->user()->email }}</p>
                </div>
                <div class="flex flex-col gap-1">
                    <a href="{{ route('account.show') }}" class="flex rounded-xl px-3 py-2 text-sm transition" style="color:oklch(30% 0.022 62);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Tài khoản của tôi</a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex w-full rounded-xl px-3 py-2 text-sm text-left transition" style="color:oklch(30% 0.022 62);" onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">Đăng xuất</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</div>

<style>
    body.overflow-hidden { overflow: hidden !important; }

    .drawer-backdrop {
        pointer-events: none;
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: flex;
        justify-content: flex-end;
        background-color: oklch(22% 0.018 60 / 0.35);
        backdrop-filter: blur(4px);
        opacity: 0;
        transition: opacity 300ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .drawer-panel {
        position: relative;
        width: 20rem;
        max-width: 85vw;
        height: 100%;
        background-color: oklch(99.4% 0.005 78);
        padding: 1.5rem;
        box-shadow: -8px 0 32px -8px oklch(28% 0.022 60 / 0.15);
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    #mobile-menu-drawer:checked ~ .drawer-backdrop {
        pointer-events: auto;
        opacity: 1;
    }
    #mobile-menu-drawer:checked ~ .drawer-backdrop .drawer-panel {
        transform: translateX(0);
    }

    @media (max-width: 1023px) {
        .lg-nav-hide { display: none !important; }
        .mobile-ml-auto { margin-left: auto !important; }
    }
    @media (min-width: 1024px) {
        .hamburger-hide { display: none !important; }
        .desktop-ml-0 { margin-left: 0 !important; }
    }
</style>

<script>
    document.addEventListener('click', function(e) {
        document.querySelectorAll('header details').forEach(function(details) {
            if (!details.contains(e.target)) {
                details.removeAttribute('open');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const drawerToggle = document.getElementById('mobile-menu-drawer');
        if (drawerToggle) {
            drawerToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
        }
    });
</script>
