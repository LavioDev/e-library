<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Đăng nhập | Thư viện văn bản văn học mở rộng</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body data-theme="library" class="bg-base-200">
        <div class="flex min-h-screen flex-col" id="login-page-shell">
            <main class="flex flex-1 items-center justify-center bg-base-200 px-4 py-8">
                <section class="w-full max-w-md rounded-sm border border-slate-200 bg-white shadow-[0_24px_60px_-40px_rgba(15,23,42,0.35)]">
                    <div class="p-6 sm:p-8">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Đăng nhập</p>
                            <h1 class="text-2xl font-semibold text-slate-900">Đăng nhập tài khoản</h1>
                            <p class="text-sm leading-6 text-slate-500">Nhập email và mật khẩu để tiếp tục.</p>
                        </div>

                        @if (session('status'))
                            <div class="mt-6 rounded-sm border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mt-6 rounded-sm border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5" novalidate>
                            @csrf

                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    placeholder="Nhập email của bạn"
                                    class="input h-11 w-full rounded-sm border border-slate-200 bg-slate-50 text-slate-800 shadow-none outline-none placeholder:text-slate-400 focus:border-blue-400"
                                />
                            </div>

                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-medium text-slate-700">Mật khẩu</label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Nhập mật khẩu của bạn"
                                    class="input h-11 w-full rounded-sm border border-slate-200 bg-slate-50 text-slate-800 shadow-none outline-none placeholder:text-slate-400 focus:border-blue-400"
                                />
                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary h-11 w-full rounded-sm border-0 px-4 text-sm font-medium text-white shadow-none"
                            >
                                Đăng nhập
                            </button>

                            <div class="text-center text-sm text-slate-500 mt-4 pt-2">
                                Chưa có tài khoản? 
                                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">Đăng ký ngay</a>
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
