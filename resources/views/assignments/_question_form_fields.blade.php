<div class="grid gap-4 md:grid-cols-2">
    <div class="space-y-2">
        <label for="{{ $prefix }}_type" class="block text-sm font-medium text-slate-700">Loại câu hỏi</label>
        <select
            id="{{ $prefix }}_type"
            name="type"
            required
            data-question-type="{{ $prefix }}"
            class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        >
            <option value="multiple_choice" @selected(($type ?? 'multiple_choice') === 'multiple_choice')>Trắc nghiệm</option>
            <option value="text_input" @selected(($type ?? '') === 'text_input')>Nhập văn bản</option>
            <option value="file_input" @selected(($type ?? '') === 'file_input')>Nộp tệp</option>
        </select>
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_position" class="block text-sm font-medium text-slate-700">Thứ tự câu</label>
        <input
            id="{{ $prefix }}_position"
            type="number"
            min="1"
            name="position"
            value="{{ $position ?? '' }}"
            required
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>

    <div class="space-y-2 md:col-span-2">
        <label for="{{ $prefix }}_prompt" class="block text-sm font-medium text-slate-700">Nội dung câu hỏi</label>
        <textarea
            id="{{ $prefix }}_prompt"
            name="prompt"
            rows="4"
            required
            class="textarea textarea-sm w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        >{{ $prompt ?? '' }}</textarea>
    </div>

    <div class="space-y-2">
        <label for="{{ $prefix }}_max_score" class="block text-sm font-medium text-slate-700">Điểm tối đa</label>
        <input
            id="{{ $prefix }}_max_score"
            type="number"
            step="0.25"
            min="0"
            name="max_score"
            value="{{ $maxScore ?? 1 }}"
            required
            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-none"
        />
    </div>

    <div class="space-y-3 md:col-span-2" data-mcq-fields="{{ $prefix }}">
        <div class="rounded-sm border border-slate-200 bg-slate-50 p-3">
            <div class="mb-3 flex items-center justify-between gap-3">
                <label class="block text-sm font-medium text-slate-700">Danh sách đáp án</label>
                <button
                    type="button"
                    class="btn btn-ghost btn-xs rounded-sm border border-slate-300 bg-white text-slate-700 hover:bg-slate-100"
                    data-add-mcq-option="{{ $prefix }}"
                >
                    + Thêm đáp án
                </button>
            </div>

            <div class="space-y-2" data-mcq-options-list="{{ $prefix }}"></div>
            <p class="mt-3 text-xs text-slate-500">Chọn vòng tròn ở đầu dòng để đặt đáp án đúng.</p>
        </div>

        <textarea
            id="{{ $prefix }}_options_raw"
            name="options_raw"
            rows="1"
            class="hidden"
            tabindex="-1"
            aria-hidden="true"
        >{{ $optionsRaw ?? '' }}</textarea>
        <input
            id="{{ $prefix }}_correct_answer"
            type="hidden"
            name="correct_answer"
            value="{{ $correctAnswer ?? '' }}"
        />
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

