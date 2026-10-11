@props([
    'paginator' => null,
    'class' => '',
    'showFirstLast' => true,
    'showPrevNext' => true,
    'onEachSide' => 3,
])

@php
    if (!$paginator) {
        $paginator = request()->has('page') ? new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15) : null;
    }
@endphp

@if($paginator && $paginator->hasPages())
    <nav
        class="flex items-center justify-center gap-1 {{ $class }}"
        aria-label="Pagination"
        {{ $attributes->merge(['class' => '']) }}
    >
        @if($showFirstLast && !$paginator->onFirstPage())
            <a
                href="{{ $paginator->url(1) }}"
                class="pagination-link"
                aria-label="First page"
                title="First page"
            >
                <x-ui.icon name="chevrons-left" class="h-5 w-5" />
            </a>
        @endif

        @if($showPrevNext && !$paginator->onFirstPage())
            <a
                href="{{ $paginator->previousPageUrl() }}"
                class="pagination-link"
                aria-label="Previous page"
                title="Previous page"
            >
                <x-ui.icon name="chevron-left" class="h-5 w-5" />
            </a>
        @endif

        @foreach($paginator->getUrlRange($onEachSide) as $page => $url)
            @if($page === '...')
                <span class="px-3 py-1.5 text-sm text-[var(--color-text-muted)]" aria-hidden="true">...</span>
            @elseif($page === $paginator->currentPage())
                <span
                    class="px-3 py-1.5 text-sm font-semibold text-white bg-orange-600 rounded-lg"
                    aria-current="page"
                    aria-label="Page {{ $page }, current page}"
                >
                    {{ $page }}
                </span>
            @else
                <a
                    href="{{ $url }}"
                    class="pagination-link"
                    aria-label="Page {{ $page }}"
                >
                    {{ $page }}
                </a>
            @endif
        @endforeach

        @if($showPrevNext && $paginator->hasMorePages())
            <a
                href="{{ $paginator->nextPageUrl() }}"
                class="pagination-link"
                aria-label="Next page"
                title="Next page"
            >
                <x-ui.icon name="chevron-right" class="h-5 w-5" />
            </a>
        @endif

        @if($showFirstLast && $paginator->hasMorePages())
            <a
                href="{{ $paginator->url($paginator->lastPage()) }}"
                class="pagination-link"
                aria-label="Last page"
                title="Last page"
            >
                <x-ui.icon name="chevrons-right" class="h-5 w-5" />
            </a>
        @endif
    </nav>
@endif

<style>
    .pagination-link {
        @apply px-3 py-1.5 text-sm font-medium text-[var(--color-text-primary)] bg-white border border-[var(--color-border-light)] rounded-lg hover:bg-[var(--color-bg-tertiary)] hover:border-orange-300 transition-all duration-200 focus-ring;
    }
</style>