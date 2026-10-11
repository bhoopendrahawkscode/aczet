@props([
    'variant' => 'line',
    'tabs' => [],
    'active' => null,
    'sticky' => false,
    'class' => '',
])

@php
    $variants = [
        'line' => 'border-b border-[var(--color-border-light)]',
        'pill' => 'bg-[var(--color-bg-tertiary)] p-1 rounded-xl',
        'card' => 'border-b border-[var(--color-border-light)]',
    ];

    $activeTab = $active ?? ($tabs[0]['key'] ?? null);
@endphp

<div
    x-data="{ activeTab: '{{ $activeTab }}' }"
    x-init="
        @if($sticky)
        const nav = $refs.tabsNav;
        const observer = new IntersectionObserver(([e]) => nav.classList.toggle('sticky', !e.isIntersecting), { threshold: [1], rootMargin: '-1px 0px 0px 0px' });
        observer.observe($refs.sentinel);
        @endif
    "
    class="{{ $class }}"
    {{ $attributes->merge(['class' => '']) }}
>
    @if($sticky)
        <div x-ref="sentinel" class="h-px w-full -mt-px" aria-hidden="true"></div>
    @endif

    <nav
        x-ref="tabsNav"
        class="{{ $variants[$variant] }} {{ $sticky ? 'sticky top-0 z-[var(--z-sticky)] bg-white/95 backdrop-blur-sm' : '' }}"
        role="tablist"
        aria-label="Tabs"
    >
        <div class="flex {{ $variant === 'pill' ? '' : 'overflow-x-auto scrollbar-hide' }} {{ $variant === 'pill' ? 'gap-1' : 'gap-1' }} px-4 py-{{ $variant === 'pill' ? '1' : '0' }}">
            @foreach($tabs as $tab)
                <button
                    role="tab"
                    :aria-selected="activeTab === '{{ $tab['key'] }}'"
                    :class="
                        activeTab === '{{ $tab['key'] }}'
                            ? '{{ $variant === 'line' ? 'text-orange-600 border-b-2 border-orange-600' : ($variant === 'pill' ? 'bg-white text-orange-600 shadow-sm' : 'text-orange-600 border-b-2 border-orange-600') }}'
                            : 'text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] {{ $variant === 'pill' ? 'hover:bg-white/50' : '' }}'
                    "
                    @click="activeTab = '{{ $tab['key'] }}'"
                    class="
                        {{ $variant === 'line' ? 'px-4 py-3' : ($variant === 'pill' ? 'px-4 py-2' : 'px-4 py-3') }}
                        font-semibold text-sm whitespace-nowrap transition-all duration-200
                        focus-ring
                        relative
                    "
                >
                    @if(isset($tab['icon']))
                        <x-ui.icon :name="{{ $tab['icon'] }}" class="h-4 w-4 mr-2" />
                    @endif
                    {{ $tab['label'] }}
                    @if(isset($tab['badge']))
                        <x-ui.badge variant="primary" size="xs" class="ml-2">{{ $tab['badge'] }}</x-ui.badge>
                    @endif
                </button>
            @endforeach
        </div>
    </nav>

    <div class="mt-4" role="tabpanel">
        @foreach($tabs as $tab)
            <div
                x-show="activeTab === '{{ $tab['key'] }}'"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-1"
                role="tabpanel"
                aria-labelledby="{{ $tab['key'] }}-tab"
                hidden
            >
                {{ $tab['content'] ?? '' }}
            </div>
        @endforeach
    </div>
</div>