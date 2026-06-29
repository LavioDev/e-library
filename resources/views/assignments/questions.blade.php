@extends('layouts.library')

@section('title', 'Câu hỏi bộ câu hỏi')

@section('content')
    <section class="space-y-6">
        {{-- ─── FILTERS & HEADER INFO ─── --}}
        <div class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-sm font-bold uppercase tracking-wider" style="color: oklch(18% 0.020 58);">{{ $assignment->title }}</h1>
                    <p class="text-xs font-serif italic mt-0.5" style="color: oklch(46% 0.018 58);">
                        Nhiệm vụ đọc hiểu: <span class="font-sans font-semibold not-italic" style="color: oklch(34% 0.025 64);">{{ $assignment->readingClass?->name }}</span>
                    </p>
                </div>

                <form method="GET" action="{{ route('admin.assignments.questions.index', $assignment) }}" class="flex flex-wrap items-center gap-2">
                    <input
                        id="keyword"
                        type="text"
                        name="keyword"
                        value="{{ $filters['keyword'] ?? '' }}"
                        placeholder="Tìm theo nội dung câu hỏi"
                        class="input input-sm !h-10 min-h-10 w-72 rounded-xl border text-sm shadow-none focus:outline-none"
                        style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                    />
                    <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                        Lọc
                    </button>
                    <a href="{{ route('admin.assignments.questions.index', $assignment) }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                       style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                       onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                        Xóa lọc
                    </a>
                    <button
                        type="button"
                        class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                        style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                        onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                        data-open-modal="create-question-modal"
                    >
                        Thêm câu hỏi
                    </button>
                </form>
            </div>
        </div>

        {{-- ─── TABLE SECTION ─── --}}
        <section class="overflow-hidden rounded-2xl border shadow-sm" style="border-color: oklch(89% 0.018 72);">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead style="background: oklch(97% 0.010 76); color: oklch(30% 0.022 60); border-bottom: 1px solid oklch(89% 0.018 72);">
                        <tr>
                            <th class="w-20">Thứ tự</th>
                            <th class="w-36">Loại</th>
                            <th>Nội dung</th>
                            <th class="w-32">Điểm tối đa</th>
                            <th class="w-48">Đáp án đúng</th>
                            <th class="w-40">Ngày tạo</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($questions as $question)
                            @php
                                $options = is_array($question->options_json) ? $question->options_json : [];
                                $questionPayload = [
                                    'id' => $question->id,
                                    'type' => $question->type,
                                    'prompt' => $question->prompt,
                                    'position' => $question->position,
                                    'max_score' => (string) $question->max_score,
                                    'options_raw' => implode("\n", $options),
                                    'correct_answer' => $question->correct_answer,
                                    'created_at' => optional($question->created_at)->format('d/m/Y H:i'),
                                    'edit_url' => route('admin.assignments.questions.update', [$assignment, $question]),
                                ];

                                $typeLabel = match ($question->type) {
                                    'multiple_choice' => 'Trắc nghiệm',
                                    'text_input' => 'Nhập văn bản',
                                    'file_input' => 'Nộp tệp',
                                    default => 'Khác',
                                };
                            @endphp
                            <tr style="border-bottom: 1px solid oklch(92% 0.016 74);">
                                <td class="font-semibold" style="color: oklch(18% 0.020 58);">{{ $question->position }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ $typeLabel }}</td>
                                <td class="max-w-lg">
                                    <p class="line-clamp-2 font-medium" style="color: oklch(18% 0.020 58);">{{ $question->prompt }}</p>
                                </td>
                                <td style="color: oklch(34% 0.025 64);">{{ rtrim(rtrim((string) $question->max_score, '0'), '.') }}</td>
                                <td class="max-w-xs truncate" style="color: oklch(34% 0.025 64);">{{ $question->type === 'multiple_choice' ? ($question->correct_answer ?: '-') : '-' }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ optional($question->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                            data-open-modal="edit-question-modal"
                                            data-question='@json($questionPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.assignments.questions.destroy', [$assignment, $question]) }}" data-confirm-delete="true" data-confirm-message="Xóa câu hỏi này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                                    style="border-color: oklch(76% 0.080 42 / 0.25); background: oklch(99.4% 0.005 78); color: oklch(38% 0.080 42);"
                                                    onmouseover="this.style.background='oklch(95% 0.020 40)'" onmouseout="this.style.background=''">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-500">Chưa có câu hỏi nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>

    {{-- ─── CREATE MODAL ─── --}}
    <dialog id="create-question-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Thêm câu hỏi</h2>
            </div>
            <form method="POST" action="{{ route('admin.assignments.questions.store', $assignment) }}" id="create-question-form">
                @csrf
                <div class="px-5 py-5 max-h-[60vh] overflow-y-auto">
                    @include('assignments._question_form_fields', [
                        'prefix' => 'create',
                        'type' => old('type', 'multiple_choice'),
                        'prompt' => old('prompt', ''),
                        'position' => old('position', max(1, $nextPosition ?? 1)),
                        'maxScore' => old('max_score', 1),
                        'optionsRaw' => old('options_raw', ''),
                        'correctAnswer' => old('correct_answer', ''),
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="create-question-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Tạo câu hỏi
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    {{-- ─── EDIT MODAL ─── --}}
    <dialog id="edit-question-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Sửa câu hỏi</h2>
            </div>
            <form method="POST" action="" id="edit-question-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5 max-h-[60vh] overflow-y-auto">
                    @include('assignments._question_form_fields', [
                        'prefix' => 'edit',
                        'type' => 'multiple_choice',
                        'prompt' => '',
                        'position' => '',
                        'maxScore' => 1,
                        'optionsRaw' => '',
                        'correctAnswer' => '',
                        'createdAt' => '',
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="edit-question-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>
@endsection

@push('scripts')
    <script>
        const modalOpeners = document.querySelectorAll('[data-open-modal]');
        const modalClosers = document.querySelectorAll('[data-close-modal]');
        const editQuestionForm = document.getElementById('edit-question-form');
        const createQuestionForm = document.getElementById('create-question-form');

        const parseOptions = (raw) => {
            return (raw ?? '')
                .split(/\r?\n/)
                .map((line) => line.trim())
                .filter((line) => line !== '');
        };

        const createMcqEditor = (prefix) => {
            const optionsList = document.querySelector(`[data-mcq-options-list="${prefix}"]`);
            const addButton = document.querySelector(`[data-add-mcq-option="${prefix}"]`);
            const optionsRawField = document.getElementById(`${prefix}_options_raw`);
            const correctAnswerField = document.getElementById(`${prefix}_correct_answer`);

            if (!optionsList || !addButton || !optionsRawField || !correctAnswerField) {
                return null;
            }

            const toLabel = (index) => {
                if (index < 26) return String.fromCharCode(65 + index);
                return `#${index + 1}`;
            };

            const sync = () => {
                const rows = Array.from(optionsList.querySelectorAll('[data-mcq-row]'));
                const selectedRadio = rows.find((row) => row.querySelector('input[data-mcq-radio]')?.checked);
                if (!selectedRadio) {
                    const firstRadio = rows[0]?.querySelector('input[data-mcq-radio]');
                    if (firstRadio) firstRadio.checked = true;
                }

                const options = rows
                    .map((row) => row.querySelector('input[data-mcq-input]')?.value?.trim() ?? '')
                    .filter((value) => value !== '');

                optionsRawField.value = options.join('\n');

                const checkedRow = rows.find((row) => {
                    const radio = row.querySelector('input[data-mcq-radio]');
                    return Boolean(radio?.checked);
                });
                const checkedValue = checkedRow?.querySelector('input[data-mcq-input]')?.value?.trim() ?? '';
                correctAnswerField.value = checkedValue;
            };

            const updateRowLabels = () => {
                const rows = Array.from(optionsList.querySelectorAll('[data-mcq-row]'));
                rows.forEach((row, index) => {
                    const label = row.querySelector('[data-mcq-label]');
                    if (label) label.textContent = toLabel(index);
                });
            };

            const ensureAtLeastTwoRows = () => {
                const currentRows = optionsList.querySelectorAll('[data-mcq-row]').length;
                if (currentRows > 0) return;

                createRow('', true);
                createRow('', false);
                updateRowLabels();
                sync();
            };

            const createRow = (value = '', checked = false) => {
                const row = document.createElement('div');
                row.setAttribute('data-mcq-row', '1');
                row.className = 'flex items-center gap-2 rounded-xl border p-2 mb-2 bg-white';
                row.style.borderColor = 'oklch(90% 0.015 72)';
                row.innerHTML = `
                    <span data-mcq-label class="w-7 text-center text-xs font-bold" style="color: oklch(46% 0.018 58);">A</span>
                    <input type="radio" data-mcq-radio name="${prefix}_correct_choice" class="radio radio-sm border-slate-300" style="border-color: oklch(86% 0.020 72);" />
                    <input type="text" data-mcq-input class="input input-sm !h-9 min-h-9 flex-1 rounded-xl border text-sm shadow-none focus:outline-none" style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);" placeholder="Nhập đáp án" />
                    <button type="button" data-remove-mcq-option class="btn btn-ghost btn-xs rounded-xl border px-2 py-1 transition" style="border-color: oklch(76% 0.080 42 / 0.2); background: oklch(99.4% 0.005 78); color: oklch(38% 0.080 42);" onmouseover="this.style.background='oklch(95% 0.020 40)'" onmouseout="this.style.background=''">Xóa</button>
                `;

                const input = row.querySelector('input[data-mcq-input]');
                const radio = row.querySelector('input[data-mcq-radio]');
                const removeButton = row.querySelector('[data-remove-mcq-option]');

                if (input) {
                    input.value = value;
                    input.addEventListener('input', sync);
                }

                if (radio) {
                    radio.checked = checked;
                    radio.addEventListener('change', sync);
                }

                if (removeButton) {
                    removeButton.addEventListener('click', () => {
                        row.remove();
                        updateRowLabels();
                        ensureAtLeastTwoRows();
                        sync();
                    });
                }

                optionsList.appendChild(row);
                updateRowLabels();
            };

            const render = (rawOptions, correctAnswer) => {
                optionsList.innerHTML = '';
                const options = parseOptions(rawOptions);

                if (options.length === 0) {
                    createRow('', true);
                    createRow('', false);
                } else {
                    options.forEach((option) => createRow(option, option === correctAnswer));

                    const hasChecked = Array.from(optionsList.querySelectorAll('input[data-mcq-radio]')).some((radio) => radio.checked);
                    if (!hasChecked) {
                        const firstRadio = optionsList.querySelector('input[data-mcq-radio]');
                        if (firstRadio) firstRadio.checked = true;
                    }
                }

                updateRowLabels();
                sync();
            };

            addButton.addEventListener('click', () => {
                createRow('', false);
                sync();
            });

            render(optionsRawField.value, correctAnswerField.value);

            return {
                renderFromHidden() {
                    render(optionsRawField.value, correctAnswerField.value);
                },
                sync,
            };
        };

        const mcqEditors = {
            create: createMcqEditor('create'),
            edit: createMcqEditor('edit'),
        };

        const toggleMcqFields = (prefix) => {
            const typeField = document.querySelector(`[data-question-type="${prefix}"]`);
            const mcqFields = document.querySelector(`[data-mcq-fields="${prefix}"]`);
            const isMcq = typeField?.value === 'multiple_choice';

            if (!typeField || !mcqFields) {
                return;
            }

            mcqFields.style.display = isMcq ? 'block' : 'none';

            if (isMcq) {
                mcqEditors[prefix]?.renderFromHidden();
            }
        };

        const bindTypeChange = (prefix) => {
            const typeField = document.querySelector(`[data-question-type="${prefix}"]`);
            if (!typeField) {
                return;
            }

            typeField.addEventListener('change', () => toggleMcqFields(prefix));
            toggleMcqFields(prefix);
        };

        bindTypeChange('create');
        bindTypeChange('edit');

        modalOpeners.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.openModal);

                if (!modal) {
                    return;
                }

                const questionPayload = button.dataset.question ? JSON.parse(button.dataset.question) : null;

                if (modal.id === 'edit-question-modal' && questionPayload) {
                    editQuestionForm.action = questionPayload.edit_url;
                    document.getElementById('edit_type').value = questionPayload.type;
                    document.getElementById('edit_prompt').value = questionPayload.prompt;
                    document.getElementById('edit_position').value = questionPayload.position;
                    document.getElementById('edit_max_score').value = questionPayload.max_score;
                    document.getElementById('edit_options_raw').value = questionPayload.options_raw ?? '';
                    document.getElementById('edit_correct_answer').value = questionPayload.correct_answer ?? '';
                    document.getElementById('edit_created_at').value = questionPayload.created_at ?? '';
                    mcqEditors.edit?.renderFromHidden();
                    toggleMcqFields('edit');
                }

                modal.showModal();
            });
        });

        modalClosers.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.closeModal);

                if (modal) {
                    modal.close();
                }
            });
        });

        createQuestionForm?.addEventListener('submit', () => {
            mcqEditors.create?.sync();
        });

        editQuestionForm?.addEventListener('submit', () => {
            mcqEditors.edit?.sync();
        });
    </script>
@endpush
