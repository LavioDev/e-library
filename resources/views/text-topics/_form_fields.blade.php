<div class="grid gap-4 md:grid-cols-2">
    <div class="space-y-2">
        <label for="{{ $prefix }}_name" class="block text-sm font-medium text-slate-700">Tên loại văn bản</label>
        <input
            id="{{ $prefix }}_name"
            type="text"
            name="name"
            value="{{ $name ?? '' }}"
            required
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>

    @isset($textsCount)
        <div class="space-y-2">
            <label for="{{ $prefix }}_texts_count" class="block text-sm font-medium text-slate-700">Số văn bản</label>
            <input
                id="{{ $prefix }}_texts_count"
                type="text"
                value="{{ $textsCount }}"
                readonly
                tabindex="-1"
                class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-100 text-sm text-slate-500 shadow-none"
            />
        </div>
    @endisset

    @isset($createdAt)
        <div class="space-y-2 md:col-span-2">
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
