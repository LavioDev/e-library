@php
    $routeName = request()->route()?->getName();

    $items = [
        ['label' => 'Trang chủ', 'url' => route('home')],
    ];

    if (request()->routeIs('admin.texts.writer.*')) {
        $items[] = ['label' => 'Văn bản', 'url' => route('admin.texts.index')];
        $items[] = ['label' => 'Soạn thảo văn bản', 'url' => null];
    } elseif (request()->routeIs('admin.users.*')) {
        $items[] = ['label' => 'Người dùng', 'url' => null];
    } elseif (request()->routeIs('admin.text-topics.*')) {
        $items[] = ['label' => 'Loại văn bản', 'url' => null];
    } elseif (request()->routeIs('admin.reading-classes.*')) {
        $items[] = ['label' => 'Nhiệm vụ đọc hiểu', 'url' => null];
    } elseif (request()->routeIs('admin.assignments.questions.*')) {
        $items[] = ['label' => 'Nhiệm vụ đọc hiểu', 'url' => route('admin.reading-classes.index')];
        $items[] = ['label' => 'Bộ câu hỏi', 'url' => route('admin.assignments.index')];
        $items[] = ['label' => 'Câu hỏi', 'url' => null];
    } elseif (request()->routeIs('admin.assignments.*')) {
        $items[] = ['label' => 'Nhiệm vụ đọc hiểu', 'url' => route('admin.reading-classes.index')];
        $items[] = ['label' => 'Bộ câu hỏi', 'url' => null];
    } elseif (request()->routeIs('admin.texts.*')) {
        $items[] = ['label' => 'Văn bản', 'url' => null];
    } elseif (request()->routeIs('user.reading-classes.*')) {
        $items[] = ['label' => 'Nhiệm vụ đọc hiểu của tôi', 'url' => null];
    } elseif (request()->routeIs('account.*')) {
        $items[] = ['label' => 'Tài khoản của tôi', 'url' => null];
    } elseif ($routeName) {
        $items[] = ['label' => 'Trang hiện tại', 'url' => null];
    }
@endphp

<nav class="mb-4 overflow-x-auto text-sm" aria-label="Breadcrumb">
    <div class="text-slate-600">
        <ul class="flex items-center gap-2 whitespace-nowrap">
            @foreach ($items as $item)
                <li class="inline-flex items-center gap-2">
                    @if (!$loop->first)
                        <span class="text-slate-400">&gt;</span>
                    @endif

                    @if ($loop->last || empty($item['url']))
                        <span class="font-medium text-slate-900">{{ $item['label'] }}</span>
                    @else
                        <a href="{{ $item['url'] }}" class="hover:text-blue-700">{{ $item['label'] }}</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</nav>
