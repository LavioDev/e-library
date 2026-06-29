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
    <section class="space-y-6">
        {{-- Div thao tác trên cùng --}}
        <div class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Left side: Tabs vertically centered -->
                <div class="flex items-center gap-1">
                    <div class="tabs flex gap-1 -mb-px">
                        <button
                            type="button"
                            data-tab-trigger="texts"
                            class="tab-btn px-4 py-2.5 text-sm font-bold border-b-2 transition-colors focus:outline-none"
                            style="border-color: oklch(44% 0.064 54); color: oklch(44% 0.064 54);"
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
                    <span class="text-sm font-medium" style="color: oklch(34% 0.025 64);">
                        Nhóm: <span class="font-bold" style="color: oklch(18% 0.020 58);">{{ $class->name }}</span>
                    </span>
                    
                    <div id="topic-filter-wrapper" class="w-64 shrink-0">
                        <select
                            id="topic-filter"
                            class="select select-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        >
                            <option value="all">Tất cả loại văn bản</option>
                            @foreach ($class->texts->pluck('textTopic')->filter()->unique('id') as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <a
                        href="{{ route('user.reading-classes.index') }}"
                        class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                        style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                        onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                    >
                        Quay lại
                    </a>
                </div>
            </div>
        </div>

        {{-- TAB: Văn bản --}}
        <div id="tab-content-texts" class="tab-panel overflow-hidden rounded-2xl border shadow-sm" style="border-color: oklch(89% 0.018 72); background: white;">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead style="background: oklch(97% 0.010 76); color: oklch(30% 0.022 60); border-bottom: 1px solid oklch(89% 0.018 72);">
                        <tr>
                            <th class="font-bold py-3.5 px-5 text-left">Tên văn bản</th>
                            <th class="font-bold py-3.5 px-5 text-left">Tác giả</th>
                            <th class="font-bold py-3.5 px-5 text-left">Chủ đề</th>
                            <th class="font-bold py-3.5 px-5 text-left">Độ khó</th>
                            <th class="font-bold py-3.5 px-5 text-right">Thao tác</th>
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
                                $diffStyle = match ($text->difficulty) {
                                    'easy' => 'background: oklch(52% 0.090 155 / 0.15); color: oklch(30% 0.070 155);',
                                    'medium' => 'background: oklch(62% 0.090 240 / 0.15); color: oklch(35% 0.080 240);',
                                    'hard' => 'background: oklch(72% 0.090 42 / 0.15); color: oklch(38% 0.080 42);',
                                    default => 'background: oklch(80% 0.010 70 / 0.2); color: oklch(46% 0.018 58);',
                                };
                            @endphp
                            <tr
                                data-text-row="1"
                                data-topic-id="{{ $text->text_topic_id ?? 'none' }}"
                                class="hover:bg-slate-50/60 transition-colors"
                                style="border-bottom: 1px solid oklch(92% 0.016 74);"
                            >
                                <td class="py-3.5 px-5 font-semibold text-sm">
                                    <a href="{{ route('texts.content.show', $text) }}" class="hover:opacity-80 transition" style="color: oklch(18% 0.020 58);">
                                        {{ $text->name }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-5 text-sm font-medium" style="color: oklch(34% 0.025 64);">
                                    {{ $text->author ?: 'Chưa rõ' }}
                                </td>
                                <td class="py-3.5 px-5 text-sm" style="color: oklch(34% 0.025 64);">
                                    {{ $text->textTopic?->name ?: '—' }}
                                </td>
                                <td class="py-3.5 px-5 text-sm">
                                    <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block" style="{{ $diffStyle }}">
                                        {{ $diffLabel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <a
                                        href="{{ route('texts.content.show', $text) }}"
                                        class="btn btn-ghost btn-sm !h-9 min-h-9 rounded-xl border px-4 shadow-none transition"
                                        style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                        onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                    >
                                        Đọc bài
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm font-medium" style="color: oklch(46% 0.018 58);">
                                    Nhiệm vụ đọc hiểu này chưa có văn bản nào.
                                </td>
                            </tr>
                        @endforelse
                        
                        {{-- Empty filter state --}}
                        <tr id="filter-empty-state" class="hidden">
                            <td colspan="5" class="py-12 text-center text-sm font-medium" style="color: oklch(46% 0.018 58);">
                                Không tìm thấy văn bản phù hợp.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB: Bộ câu hỏi --}}
        <div id="tab-content-assignments" class="tab-panel hidden overflow-hidden rounded-2xl border shadow-sm" style="border-color: oklch(89% 0.018 72); background: white;">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead style="background: oklch(97% 0.010 76); color: oklch(30% 0.022 60); border-bottom: 1px solid oklch(89% 0.018 72);">
                        <tr>
                            <th class="font-bold py-3.5 px-5 text-left">Tiêu đề bộ câu hỏi</th>
                            <th class="font-bold py-3.5 px-5 text-left">Số câu hỏi</th>
                            <th class="font-bold py-3.5 px-5 text-left">Hạn nộp</th>
                            <th class="font-bold py-3.5 px-5 text-left">Trạng thái bộ câu hỏi</th>
                            <th class="font-bold py-3.5 px-5 text-left">Trạng thái làm bài</th>
                            <th class="font-bold py-3.5 px-5 text-left">Điểm số</th>
                            <th class="font-bold py-3.5 px-5 text-right">Thao tác</th>
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
                                    $statusStyle = 'background: oklch(52% 0.090 155 / 0.15); color: oklch(30% 0.070 155);';
                                } elseif ($isUpcoming) {
                                    $statusLabel = 'Sắp mở';
                                    $statusStyle = 'background: oklch(72% 0.090 42 / 0.15); color: oklch(38% 0.080 42);';
                                } else {
                                    $statusLabel = 'Đã đóng';
                                    $statusStyle = 'background: oklch(80% 0.010 70 / 0.2); color: oklch(46% 0.018 58);';
                                }

                                $submissionLabel = null;
                                $submissionStyle = '';
                                if ($latestSubmission) {
                                    [$submissionLabel, $submissionStyle] = match ($latestSubmission->status) {
                                        'draft' => ['Đang làm dở', 'background: oklch(62% 0.090 240 / 0.15); color: oklch(35% 0.080 240);'],
                                        'submitted' => ['Đã nộp', 'background: oklch(52% 0.090 155 / 0.15); color: oklch(30% 0.070 155);'],
                                        'graded' => ['Đã chấm', 'background: oklch(62% 0.090 270 / 0.15); color: oklch(35% 0.080 270);'],
                                        default => [null, ''],
                                    };
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors" style="border-bottom: 1px solid oklch(92% 0.016 74);">
                                <td class="py-3.5 px-5 font-semibold text-sm" style="color: oklch(18% 0.020 58);">
                                    {{ $assignment->title }}
                                </td>
                                <td class="py-3.5 px-5 text-sm" style="color: oklch(34% 0.025 64);">
                                    {{ $assignment->questions->count() }} câu hỏi
                                </td>
                                <td class="py-3.5 px-5 text-sm" style="color: oklch(34% 0.025 64);">
                                    {{ $assignment->due_at?->format('H:i d/m/Y') ?? 'Không giới hạn' }}
                                </td>
                                <td class="py-3.5 px-5 text-sm">
                                    <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block" style="{{ $statusStyle }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-sm">
                                    @if ($submissionLabel)
                                        <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block" style="{{ $submissionStyle }}">
                                            {{ $submissionLabel }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-sm font-bold" style="color: oklch(40% 0.068 54);">
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
                                                class="btn btn-ghost btn-sm !h-9 min-h-9 rounded-xl border px-4 shadow-none transition"
                                                style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                                onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                            >
                                                Xem kết quả
                                            </a>
                                        @elseif ($latestSubmission && $latestSubmission->status === 'submitted')
                                            <a
                                                href="{{ route('user.reading-classes.assignments.take', ['readingClass' => $class->id, 'assignment' => $assignment->id]) }}"
                                                class="btn btn-ghost btn-sm !h-9 min-h-9 rounded-xl border px-4 shadow-none transition"
                                                style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                                onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                            >
                                                Xem bài làm
                                            </a>
                                        @else
                                            <a
                                                href="{{ route('user.reading-classes.assignments.take', ['readingClass' => $class->id, 'assignment' => $assignment->id]) }}"
                                                class="btn btn-sm !h-9 min-h-9 rounded-xl border-0 px-4 text-white shadow-none transition"
                                                style="background: var(--g-primary);"
                                                onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                                            >
                                                {{ $latestSubmission && $latestSubmission->status === 'draft' ? 'Làm tiếp' : 'Làm bài' }}
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-xs font-semibold" style="color: oklch(46% 0.018 58);">Đã đóng</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-sm font-medium" style="color: oklch(46% 0.018 58);">
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
                            t.classList.add('text-blue-600');
                            t.style.borderColor = 'oklch(44% 0.064 54)';
                            t.style.color = 'oklch(44% 0.064 54)';
                        } else {
                            t.classList.add('border-transparent', 'text-slate-400', 'hover:text-slate-600');
                            t.classList.remove('text-blue-600');
                            t.style.borderColor = 'transparent';
                            t.style.color = '';
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
