@extends('layouts.library')

@section('title', 'Tài khoản của tôi')

@section('content')
    <section class="space-y-5">
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Tài khoản của tôi</h1>
            </div>

            <form method="POST" action="{{ route('account.update') }}" class="space-y-5 mt-5">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-slate-700">Tên</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="created_at" class="block text-sm font-medium text-slate-700">Ngày tạo</label>
                        <input
                            id="created_at"
                            type="text"
                            value="{{ optional($user->created_at)->format('d/m/Y H:i') }}"
                            readonly
                            tabindex="-1"
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-100 text-sm text-slate-500 shadow-none"
                        />
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-slate-700">Gmail</label>
                        <input
                            id="email"
                            type="text"
                            value="{{ $user->email }}"
                            readonly
                            tabindex="-1"
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-100 text-sm text-slate-500 shadow-none"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="block text-sm font-medium text-slate-700">Quyền</label>
                        <input
                            id="role"
                            type="text"
                            value="{{ $user->role === 'teacher' ? 'Giáo viên' : 'Người dùng' }}"
                            readonly
                            tabindex="-1"
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-100 text-sm text-slate-500 shadow-none"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-slate-700">Mật khẩu mới</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Xác nhận mật khẩu mới</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password"
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
                        />
                    </div>
                </div>

                <p class="text-xs text-slate-500">Để trống 2 ô mật khẩu nếu không muốn thay đổi.</p>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
