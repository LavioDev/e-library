@extends('layouts.library')

@section('title', 'Câu hỏi bộ câu hỏi')

@section('content')
    <section class="space-y-5">
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-sm font-semibold text-slate-900">{{ $assignment->title }}</h1>
                    <p class="text-xs text-slate-600">
                        Nhiệm vụ đọc hiểu: <span class="font-medium">{{ $assignment->readingClass?->name }}</span>
                    </p>
                </div>

                <form method="GET" action="{{ route('admin.assignments.questions.index', $assignment) }}" class="flex items-center gap-2">
                    <input
                        id="keyword"
                        type="text"
                        name="keyword"
                        value="{{ $filters['keyword'] ?? '' }}"
                        placeholder="Tìm theo nội dung câu hỏi"
                        class="input input-sm !h-10 min-h-10 w-72 rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                    />
                    <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                        Lọc
                    </button>
                    <a href="{{ route('admin.assignments.questions.index', $assignment) }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                        Xóa lọc
                    </a>
                    <button
                        type="button"
                        class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none"
                        data-open-modal="create-question-modal"
                    >
                        Thêm câu hỏi
                    </button>
                </form>
            </div>
        </div>

        <section class="overflow-hidden rounded-sm border border-slate-200 bg-white shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th>Thứ tự</th>
                            <th>Loại</th>
                            <th>Nội dung</th>
                            <th>Điểm tối đa</th>
                            <th>Đáp án đúng</th>
                            <th>Ngày tạo</th>
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
                            <tr>
                                <td>{{ $question->position }}</td>
                                <td>{{ $typeLabel }}</td>
                                <td class="max-w-lg">
                                    <p class="line-clamp-2 font-medium text-slate-900">{{ $question->prompt }}</p>
                                </td>
                                <td>{{ rtrim(rtrim((string) $question->max_score, '0'), '.') }}</td>
                                <td>{{ $question->type === 'multiple_choice' ? ($question->correct_answer ?: '-') : '-' }}</td>
                                <td>{{ optional($question->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50"
                                            data-open-modal="edit-question-modal"
                                            data-question='@json($questionPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.assignments.questions.destroy', [$assignment, $question]) }}" data-confirm-delete="true" data-confirm-message="Xóa câu hỏi này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm rounded-sm border-0 bg-rose-600 text-white shadow-none hover:bg-rose-700">
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

    <dialog id="create-question-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Thêm câu hỏi</h2>
            </div>
            <form method="POST" action="{{ route('admin.assignments.questions.store', $assignment) }}" id="create-question-form">
                @csrf
                <div class="px-5 py-5">
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

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="create-question-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
                        Tạo câu hỏi
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    <dialog id="edit-question-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Sửa câu hỏi</h2>
            </div>
            <form method="POST" action="" id="edit-question-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5">
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

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="edit-question-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
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
                row.className = 'flex items-center gap-2 rounded-sm border border-slate-200 bg-white p-2';
                row.innerHTML = `
                    <span data-mcq-label class="w-7 text-center text-xs font-semibold text-slate-500">A</span>
                    <input type="radio" data-mcq-radio name="${prefix}_correct_choice" class="radio radio-xs border-slate-300" />
                    <input type="text" data-mcq-input class="input input-sm !h-9 min-h-9 flex-1 rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none" placeholder="Nhập đáp án" />
                    <button type="button" data-remove-mcq-option class="btn btn-ghost btn-xs rounded-sm border border-slate-200 bg-white text-rose-600 hover:bg-rose-50">Xóa</button>
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
