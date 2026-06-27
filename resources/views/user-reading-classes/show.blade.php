@extends('layouts.library')

@section('title', 'Chi tiết Nhiệm vụ đọc hiểu: ' . $class->name)

@section('breadcrumbs')
    <nav class="mb-4 text-sm text-slate-600">
        <ol class="flex flex-wrap items-center gap-2">
            <li><a href="{{ route('home') }}" class="hover:text-slate-900">Trang chủ</a></li>
            <li aria-hidden="true">&gt;</li>
            <li><a href="{{ route('user.reading-classes.index') }}" class="hover:text-slate-900">Nhiệm vụ đọc hiểu của tôi</a></li>
            <li aria-hidden="true">&gt;</li>
            <li class="font-medium text-slate-900">{{ $class->name }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <section class="space-y-4">
        {{-- Div thao tác trên cùng --}}
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Left side: Tabs vertically centered -->
                <div class="flex items-center gap-1">
                    <div class="tabs flex gap-1 -mb-px">
                        <button
                            type="button"
                            data-tab-trigger="texts"
                            class="tab-btn px-4 py-2.5 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-colors focus:outline-none"
                        >
                            Văn bản
                        </button>
                        <button
                            type="button"
                            data-tab-trigger="assignments"
                            class="tab-btn px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                        >
                            Bộ câu hỏi
                        </button>
                    </div>
                </div>

                <!-- Right side: Text, dropdown input, and button matching view mẫu style -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-slate-700">
                        Nhóm: <span class="font-semibold text-slate-900">{{ $class->name }}</span>
                    </span>
                    
                    <div id="topic-filter-wrapper" class="w-64 shrink-0">
                        <select
                            id="topic-filter"
                            class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none focus:outline-none"
                        >
                            <option value="all">Tất cả loại văn bản</option>
                            @foreach ($class->texts->pluck('textTopic')->filter()->unique('id') as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <a
                        href="{{ route('user.reading-classes.index') }}"
                        class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50 text-sm"
                    >
                        Quay lại
                    </a>
                </div>
            </div>
        </div>

        {{-- TAB: Văn bản --}}
        <div id="tab-content-texts" class="tab-panel overflow-hidden rounded-sm border border-slate-200 bg-white shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-600 text-sm">
                        <tr>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Tên văn bản</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Tác giả</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Chủ đề</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Độ khó</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($class->texts as $text)
                            @php
                                $diffLabel = match ($text->difficulty) {
                                    'easy' => 'Dễ',
                                    'medium' => 'Trung bình',
                                    'hard' => 'Khó',
                                    default => 'Chưa rõ',
                                };
                                $diffClass = match ($text->difficulty) {
                                    'easy' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'medium' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'hard' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-slate-50 text-slate-600 border-slate-200',
                                };
                            @endphp
                            <tr
                                data-text-row="1"
                                data-topic-id="{{ $text->text_topic_id ?? 'none' }}"
                                class="hover:bg-slate-50/60 transition-colors"
                            >
                                <td class="py-3.5 px-5 font-medium text-slate-900 text-sm">
                                    <a href="{{ route('texts.content.show', $text) }}" class="hover:text-blue-600 transition-colors">
                                        {{ $text->name }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 text-sm">
                                    {{ $text->author ?: 'Chưa rõ' }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 text-sm">
                                    {{ $text->textTopic?->name ?: '—' }}
                                </td>
                                <td class="py-3.5 px-5 text-sm">
                                    <span class="badge badge-sm border {{ $diffClass }} rounded-sm px-2 py-0.5 font-medium">
                                        {{ $diffLabel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <a
                                        href="{{ route('texts.content.show', $text) }}"
                                        class="btn btn-ghost btn-sm !h-9 min-h-9 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50 text-xs"
                                    >
                                        Đọc bài
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500 text-sm font-medium">
                                    Nhiệm vụ đọc hiểu này chưa có văn bản nào.
                                </td>
                            </tr>
                        @endforelse
                        
                        {{-- Empty filter state --}}
                        <tr id="filter-empty-state" class="hidden">
                            <td colspan="5" class="py-12 text-center text-slate-500 text-sm font-medium">
                                Không tìm thấy văn bản phù hợp.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB: Bộ câu hỏi --}}
        <div id="tab-content-assignments" class="tab-panel hidden overflow-hidden rounded-sm border border-slate-200 bg-white shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-600 text-sm">
                        <tr>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Tiêu đề bộ câu hỏi</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Số câu hỏi</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Hạn nộp</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Trạng thái bộ câu hỏi</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Trạng thái làm bài</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-left">Điểm số</th>
                            <th class="font-semibold text-slate-700 py-3.5 px-5 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($class->assignments as $assignment)
                            @php
                                $latestSubmission = $assignment->submissions->first();
                                $isOpen = $assignment->is_published
                                    && ($assignment->open_at === null || $now->gte($assignment->open_at))
                                    && ($assignment->due_at === null || $now->lte($assignment->due_at));
                                $isUpcoming = $assignment->open_at !== null && $now->lt($assignment->open_at);

                                if ($isOpen) {
                                    $statusLabel = 'Đang mở';
                                    $statusClass = 'border-emerald-200 bg-emerald-50 text-emerald-700';
                                } elseif ($isUpcoming) {
                                    $statusLabel = 'Sắp mở';
                                    $statusClass = 'border-amber-200 bg-amber-50 text-amber-700';
                                } else {
                                    $statusLabel = 'Đã đóng';
                                    $statusClass = 'border-rose-200 bg-rose-50 text-rose-700';
                                }

                                $submissionLabel = null;
                                $submissionClass = '';
                                if ($latestSubmission) {
                                    [$submissionLabel, $submissionClass] = match ($latestSubmission->status) {
                                        'draft' => ['Đang làm dở', 'border-sky-200 bg-sky-50 text-sky-700'],
                                        'submitted' => ['Đã nộp', 'border-teal-200 bg-teal-50 text-teal-700'],
                                        'graded' => ['Đã chấm', 'border-indigo-200 bg-indigo-50 text-indigo-700'],
                                        default => [null, ''],
                                    };
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-5 font-medium text-slate-900 text-sm">
                                    {{ $assignment->title }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 text-sm">
                                    {{ $assignment->questions->count() }} câu hỏi
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 text-sm">
                                    {{ $assignment->due_at?->format('H:i d/m/Y') ?? 'Không giới hạn' }}
                                </td>
                                <td class="py-3.5 px-5 text-sm">
                                    <span class="badge badge-sm border {{ $statusClass }} rounded-sm px-2 py-0.5 font-medium">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-sm">
                                    @if ($submissionLabel)
                                        <span class="badge badge-sm border {{ $submissionClass }} rounded-sm px-2 py-0.5 font-medium">
                                            {{ $submissionLabel }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-sm font-semibold text-blue-600">
                                    @if ($latestSubmission && $latestSubmission->status === 'graded')
                                        {{ rtrim(rtrim((string) $latestSubmission->total_score, '0'), '.') }} / {{ rtrim(rtrim((string) $assignment->questions->sum('max_score'), '0'), '.') }}
                                    @else
                                        <span class="text-slate-400 font-normal">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    @if ($isOpen || $latestSubmission)
                                        @if ($latestSubmission && $latestSubmission->status === 'graded')
                                            <a
                                                href="{{ route('user.reading-classes.assignments.take', ['readingClass' => $class->id, 'assignment' => $assignment->id]) }}"
                                                class="btn btn-ghost btn-sm !h-9 min-h-9 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50 text-xs"
                                            >
                                                Xem kết quả
                                            </a>
                                        @elseif ($latestSubmission && $latestSubmission->status === 'submitted')
                                            <a
                                                href="{{ route('user.reading-classes.assignments.take', ['readingClass' => $class->id, 'assignment' => $assignment->id]) }}"
                                                class="btn btn-ghost btn-sm !h-9 min-h-9 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50 text-xs"
                                            >
                                                Xem bài làm
                                            </a>
                                        @else
                                            <a
                                                href="{{ route('user.reading-classes.assignments.take', ['readingClass' => $class->id, 'assignment' => $assignment->id]) }}"
                                                class="btn btn-sm !h-9 min-h-9 rounded-sm border-0 bg-blue-600 px-4 text-white shadow-none hover:bg-blue-700 text-xs"
                                            >
                                                {{ $latestSubmission && $latestSubmission->status === 'draft' ? 'Làm tiếp' : 'Làm bài' }}
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400">Đã đóng</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-500 text-sm font-medium">
                                    Nhiệm vụ đọc hiểu này chưa có nhiệm vụ học tập nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('[data-tab-trigger]');
            const panels = document.querySelectorAll('.tab-panel');
            const topicFilterWrapper = document.getElementById('topic-filter-wrapper');
            const topicFilter = document.getElementById('topic-filter');
            const textRows = document.querySelectorAll('[data-text-row]');
            const filterEmptyState = document.getElementById('filter-empty-state');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.tabTrigger;

                    tabs.forEach(t => {
                        if (t === tab) {
                            t.classList.remove('border-transparent', 'text-slate-400', 'hover:text-slate-600');
                            t.classList.add('border-blue-600', 'text-blue-600');
                        } else {
                            t.classList.remove('border-blue-600', 'text-blue-600');
                            t.classList.add('border-transparent', 'text-slate-400', 'hover:text-slate-600');
                        }
                    });

                    panels.forEach(p => {
                        p.classList.toggle('hidden', p.id !== `tab-content-${target}`);
                    });

                    if (target === 'texts') {
                        topicFilterWrapper.classList.remove('hidden');
                        topicFilterWrapper.classList.add('flex');
                    } else {
                        topicFilterWrapper.classList.add('hidden');
                        topicFilterWrapper.classList.remove('flex');
                    }
                });
            });

            if (topicFilter) {
                topicFilter.addEventListener('change', () => {
                    const val = topicFilter.value;
                    let visible = 0;

                    textRows.forEach(row => {
                        const show = val === 'all' || row.dataset.topicId === val;
                        row.style.display = show ? '' : 'none';
                        if (show) visible++;
                    });

                    filterEmptyState.classList.toggle('hidden', textRows.length === 0 || visible > 0);
                });
            }
        });
    </script>
@endpush
