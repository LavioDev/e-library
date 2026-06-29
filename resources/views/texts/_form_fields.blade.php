<div class="grid gap-4 md:grid-cols-2">
    <div class="space-y-2">
        <label for="{{ $prefix }}_text_topic_id" class="block text-sm font-medium" style="color: oklch(30% 0.022 60);">Loại văn bản</label>
        <select
            id="{{ $prefix }}_text_topic_id"
            name="text_topic_id"
            required
            class="select select-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
        >
            <option value="">Chọn loại văn bản</option>
            @foreach ($textTopics as $textTopic)
                <option value="{{ $textTopic->id }}" @selected((string) ($textTopicId ?? '') === (string) $textTopic->id)>{{ $textTopic->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_difficulty" class="block text-sm font-medium" style="color: oklch(30% 0.022 60);">Mức độ</label>
        <select
            id="{{ $prefix }}_difficulty"
            name="difficulty"
            required
            class="select select-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
        >
            <option value="">Chọn mức độ</option>
            <option value="easy" @selected(($difficulty ?? '') === 'easy')>Dễ</option>
            <option value="medium" @selected(($difficulty ?? '') === 'medium')>Trung bình</option>
            <option value="hard" @selected(($difficulty ?? '') === 'hard')>Khó</option>
        </select>
    </div>

    <div class="space-y-2 md:col-span-2">
        <label for="{{ $prefix }}_name" class="block text-sm font-medium" style="color: oklch(30% 0.022 60);">Tên văn bản</label>
        <input
            id="{{ $prefix }}_name"
            type="text"
            name="name"
            value="{{ $name ?? '' }}"
            required
            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
        />
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_topic" class="block text-sm font-medium" style="color: oklch(30% 0.022 60);">Chủ đề</label>
        <input
            id="{{ $prefix }}_topic"
            type="text"
            name="topic"
            value="{{ $topic ?? '' }}"
            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
        />
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_author" class="block text-sm font-medium" style="color: oklch(30% 0.022 60);">Tác giả</label>
        <input
            id="{{ $prefix }}_author"
            type="text"
            name="author"
            value="{{ $author ?? '' }}"
            required
            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
        />
    </div>

    <div class="space-y-2 md:col-span-2">
        <label for="{{ $prefix }}_read_link" class="block text-sm font-medium" style="color: oklch(30% 0.022 60);">Link đọc</label>
        <input
            id="{{ $prefix }}_read_link"
            type="url"
            name="read_link"
            value="{{ $readLink ?? '' }}"
            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
        />
    </div>
</div>
