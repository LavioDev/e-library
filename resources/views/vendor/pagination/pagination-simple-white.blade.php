@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center rounded-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-400">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center rounded-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-slate-300 hover:text-blue-700">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center rounded-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-slate-300 hover:text-blue-700">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center rounded-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-400">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
