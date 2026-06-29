@extends('layouts.library')

@section('title', 'Tài khoản của tôi')

@section('content')
    <section class="space-y-6">
        <div class="rounded-2xl border p-6 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="pb-4 border-b" style="border-color: oklch(90% 0.018 74);">
                <h1 class="text-xl font-bold uppercase tracking-wider" style="color: oklch(18% 0.020 58);">Tài khoản của tôi</h1>
            </div>

            <form method="POST" action="{{ route('account.update') }}" class="space-y-6 mt-6">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="block text-xs font-bold" style="color: oklch(34% 0.025 64);">Tên hiển thị</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="created_at" class="block text-xs font-bold" style="color: oklch(34% 0.025 64);">Ngày tạo tài khoản</label>
                        <input
                            id="created_at"
                            type="text"
                            value="{{ optional($user->created_at)->format('d/m/Y H:i') }}"
                            readonly
                            tabindex="-1"
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none cursor-not-allowed"
                            style="border-color: oklch(89% 0.018 72); background: oklch(95% 0.012 74); color: oklch(46% 0.018 58);"
                        />
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-bold" style="color: oklch(34% 0.025 64);">Địa chỉ Email (Gmail)</label>
                        <input
                            id="email"
                            type="text"
                            value="{{ $user->email }}"
                            readonly
                            tabindex="-1"
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none cursor-not-allowed"
                            style="border-color: oklch(89% 0.018 72); background: oklch(95% 0.012 74); color: oklch(46% 0.018 58);"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="block text-xs font-bold" style="color: oklch(34% 0.025 64);">Vai trò hệ thống</label>
                        <input
                            id="role"
                            type="text"
                            value="{{ $user->role === 'teacher' ? 'Giáo viên' : 'Người dùng' }}"
                            readonly
                            tabindex="-1"
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none cursor-not-allowed"
                            style="border-color: oklch(89% 0.018 72); background: oklch(95% 0.012 74); color: oklch(46% 0.018 58);"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-xs font-bold" style="color: oklch(34% 0.025 64);">Mật khẩu mới</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-xs font-bold" style="color: oklch(34% 0.025 64);">Xác nhận mật khẩu mới</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password"
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        />
                    </div>
                </div>

                <p class="text-xs font-serif italic text-slate-500" style="color: oklch(46% 0.018 58);">
                    💡 Nhập mật khẩu mới nếu muốn thay đổi. Hãy để trống 2 trường mật khẩu trên nếu bạn chỉ muốn thay đổi tên hiển thị.
                </p>

                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-5 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
