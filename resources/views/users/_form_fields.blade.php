<div class="grid gap-4 md:grid-cols-2">
    <div class="space-y-2">
        <label for="{{ $prefix }}_name" class="block text-sm font-medium text-slate-700">Tên</label>
        <input
            id="{{ $prefix }}_name"
            type="text"
            name="name"
            value="{{ $name ?? '' }}"
            required
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_email" class="block text-sm font-medium text-slate-700">Email</label>
        <input
            id="{{ $prefix }}_email"
            type="email"
            name="email"
            value="{{ $email ?? '' }}"
            required
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_role" class="block text-sm font-medium text-slate-700">Vai trò</label>
        <select
            id="{{ $prefix }}_role"
            name="role"
            required
            class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        >
            <option value="user" @selected(($role ?? 'user') === 'user')>Người dùng</option>
            <option value="teacher" @selected(($role ?? '') === 'teacher')>Giáo viên</option>
        </select>
    </div>

    @isset($createdAt)
        <div class="space-y-2">
            <label for="{{ $prefix }}_created_at" class="block text-sm font-medium text-slate-700">Ngày tạo</label>
            <input
                id="{{ $prefix }}_created_at"
                type="text"
                value="{{ $createdAt }}"
                readonly
                tabindex="-1"
                class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-100 text-sm text-slate-500 shadow-none"
            />
        </div>
    @endisset

    <div class="space-y-2 md:col-span-2">
        <label for="{{ $prefix }}_password" class="block text-sm font-medium text-slate-700">Mật khẩu</label>
        <input
            id="{{ $prefix }}_password"
            type="password"
            name="password"
            {{ ! empty($passwordRequired) ? 'required' : '' }}
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
        @if (empty($passwordRequired))
            <p class="text-xs text-slate-500">Để trống nếu không muốn đổi mật khẩu.</p>
        @endif
    </div>

    <div class="space-y-2 md:col-span-2">
        <label for="{{ $prefix }}_password_confirmation" class="block text-sm font-medium text-slate-700">Xác nhận mật khẩu</label>
        <input
            id="{{ $prefix }}_password_confirmation"
            type="password"
            name="password_confirmation"
            {{ ! empty($passwordRequired) ? 'required' : '' }}
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>
</div>
