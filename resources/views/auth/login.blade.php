<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Đăng nhập | Thư viện văn bản văn học mở rộng</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @import url('https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..700;1,400..700&family=Lora:ital,wght@0,400..700;1,400..700&display=swap');

            :root {
                --font-ui: 'Bahnschrift', 'Segoe UI', ui-sans-serif, system-ui, sans-serif;
                --font-serif: 'EB Garamond', 'Lora', 'Georgia', ui-serif, serif;
                --g-primary: linear-gradient(145deg, oklch(44% 0.064 54) 0%, oklch(36% 0.056 50) 100%);
            }

            body {
                font-family: var(--font-ui);
                background: oklch(98.8% 0.004 76) !important;
            }

            .serif {
                font-family: var(--font-serif);
            }
        </style>
    </head>
    <body data-theme="library" class="flex min-h-screen items-center justify-center px-4 py-8">
        <div class="w-full max-w-md rounded-2xl border p-6 sm:p-8 shadow-sm" 
             style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            
            {{-- ─── HEADER ─── --}}
            <div class="space-y-2 pb-4 border-b" style="border-color: oklch(90% 0.018 74);">
                <p class="text-xs font-bold uppercase tracking-[0.18em]" style="color: oklch(44% 0.064 54);">Hệ thống</p>
                <h1 class="text-2xl font-bold" style="color: oklch(18% 0.020 58);">Đăng nhập tài khoản</h1>
                <p class="text-sm font-serif italic" style="color: oklch(46% 0.018 58);">Nhập email và mật khẩu của bạn để vào thư viện.</p>
            </div>

            {{-- ─── NOTIFICATION ─── --}}
            @if (session('status'))
                <div class="mt-6 rounded-xl border px-3 py-2 text-sm"
                     style="background: oklch(52% 0.090 155 / 0.1); border-color: oklch(52% 0.090 155 / 0.25); color: oklch(30% 0.070 155);">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-xl border px-3 py-2 text-sm"
                     style="background: oklch(72% 0.090 42 / 0.1); border-color: oklch(72% 0.090 42 / 0.25); color: oklch(38% 0.080 42);">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- ─── FORM ─── --}}
            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5" novalidate>
                @csrf

                <div class="space-y-2">
                    <label for="email" class="block text-xs font-bold" style="color: oklch(34% 0.025 64);">Địa chỉ Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="Nhập email của bạn"
                        class="input h-11 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                        style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                    />
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold" style="color: oklch(34% 0.025 64);">Mật khẩu truy cập</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Nhập mật khẩu của bạn"
                        class="input h-11 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                        style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                    />
                </div>

                <button
                    type="submit"
                    class="btn h-11 w-full rounded-xl border-0 text-sm font-bold text-white shadow-none transition mt-2"
                    style="background: var(--g-primary);"
                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                >
                    Đăng nhập
                </button>

                <div class="text-center text-sm mt-6 pt-4 border-t" style="border-color: oklch(90% 0.018 74); color: oklch(46% 0.018 58);">
                    Chưa có tài khoản? 
                    <a href="{{ route('register') }}" class="font-bold hover:underline transition-all" style="color: oklch(40% 0.068 54);">
                        Đăng ký ngay
                    </a>
                </div>
            </form>
        </div>
    </body>
</html>
