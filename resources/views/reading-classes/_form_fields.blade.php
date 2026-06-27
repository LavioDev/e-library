@php
    $selectedUserIds = collect($selectedUserIds ?? [])->map(fn ($id) => (string) $id)->all();
    $selectedTextIds = collect($selectedTextIds ?? [])->map(fn ($id) => (string) $id)->all();
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div class="space-y-2 md:col-span-2">
        <label for="{{ $prefix }}_name" class="block text-sm font-medium text-slate-700">Tên Nhiệm vụ đọc hiểu</label>
        <input
            id="{{ $prefix }}_name"
            type="text"
            name="name"
            value="{{ $name ?? '' }}"
            required
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>

    <div class="space-y-2 md:col-span-2">
        <label for="{{ $prefix }}_text_ids" class="block text-sm font-medium text-slate-700">Văn bản trong nhóm</label>
        <div class="max-h-56 space-y-2 overflow-auto rounded-sm border border-slate-200 bg-slate-50 p-3">
            @forelse ($texts as $text)
                <label class="flex cursor-pointer items-start gap-2 rounded-sm px-2 py-1 hover:bg-slate-100">
                    <input
                        type="checkbox"
                        name="text_ids[]"
                        value="{{ $text->id }}"
                        data-text-checkbox="{{ $prefix }}"
                        @checked(in_array((string) $text->id, $selectedTextIds, true))
                        class="checkbox checkbox-sm mt-0.5 rounded-sm border-slate-300"
                    />
                    <span class="text-sm text-slate-800">
                        {{ $text->name }}
                    </span>
                </label>
            @empty
                <p class="text-sm text-slate-500">Chưa có văn bản.</p>
            @endforelse
        </div>
        <p class="text-xs text-slate-500">Chọn một hoặc nhiều văn bản cho Nhiệm vụ đọc hiểu.</p>
    </div>

    <div class="space-y-2 md:col-span-2">
        <label for="{{ $prefix }}_user_ids" class="block text-sm font-medium text-slate-700">Người dùng trong nhóm</label>
        <div class="max-h-56 space-y-2 overflow-auto rounded-sm border border-slate-200 bg-slate-50 p-3">
            @forelse ($users as $user)
                <label class="flex cursor-pointer items-start gap-2 rounded-sm px-2 py-1 hover:bg-slate-100">
                    <input
                        type="checkbox"
                        name="user_ids[]"
                        value="{{ $user->id }}"
                        data-user-checkbox="{{ $prefix }}"
                        @checked(in_array((string) $user->id, $selectedUserIds, true))
                        class="checkbox checkbox-sm mt-0.5 rounded-sm border-slate-300"
                    />
                    <span class="text-sm text-slate-800">
                        {{ $user->name }} ({{ $user->email }}) - {{ $user->role === 'teacher' ? 'Giáo viên' : 'Người dùng' }}
                    </span>
                </label>
            @empty
                <p class="text-sm text-slate-500">Chưa có người dùng.</p>
            @endforelse
        </div>
        <p class="text-xs text-slate-500">Chọn một hoặc nhiều người dùng cho Nhiệm vụ đọc hiểu.</p>
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
</div>
