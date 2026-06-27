<div class="grid gap-4 md:grid-cols-2">
    <div class="space-y-2">
        <label for="{{ $prefix }}_reading_class_id" class="block text-sm font-medium text-slate-700">Nhiệm vụ đọc hiểu</label>
        <select
            id="{{ $prefix }}_reading_class_id"
            name="reading_class_id"
            required
            class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        >
            <option value="">Chọn Nhiệm vụ đọc hiểu</option>
            @foreach ($readingClasses as $readingClass)
                <option value="{{ $readingClass->id }}" @selected((string) ($readingClassId ?? '') === (string) $readingClass->id)>{{ $readingClass->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_title" class="block text-sm font-medium text-slate-700">Tên bộ câu hỏi</label>
        <input
            id="{{ $prefix }}_title"
            type="text"
            name="title"
            value="{{ $title ?? '' }}"
            required
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>

    <div class="space-y-2 md:col-span-2">
        <label for="{{ $prefix }}_description" class="block text-sm font-medium text-slate-700">Mô tả</label>
        <textarea
            id="{{ $prefix }}_description"
            name="description"
            rows="3"
            class="textarea textarea-sm w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        >{{ $description ?? '' }}</textarea>
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_open_at" class="block text-sm font-medium text-slate-700">Thời gian mở</label>
        <input
            id="{{ $prefix }}_open_at"
            type="datetime-local"
            name="open_at"
            value="{{ $openAt ?? '' }}"
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_due_at" class="block text-sm font-medium text-slate-700">Hạn nộp</label>
        <input
            id="{{ $prefix }}_due_at"
            type="datetime-local"
            name="due_at"
            value="{{ $dueAt ?? '' }}"
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>

    <div class="space-y-2 md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
            <input type="hidden" name="is_published" value="0" />
            <input
                id="{{ $prefix }}_is_published"
                type="checkbox"
                name="is_published"
                value="1"
                @checked((bool) ($isPublished ?? false))
                class="checkbox checkbox-sm rounded-sm border-slate-300"
            />
            <span>Xuất bản bộ câu hỏi</span>
        </label>
    </div>

    @isset($questionsCount)
        <div class="space-y-2">
            <label for="{{ $prefix }}_questions_count" class="block text-sm font-medium text-slate-700">Số câu hỏi</label>
            <input
                id="{{ $prefix }}_questions_count"
                type="text"
                value="{{ $questionsCount }}"
                readonly
                tabindex="-1"
                class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-100 text-sm text-slate-500 shadow-none"
            />
        </div>
    @endisset

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
