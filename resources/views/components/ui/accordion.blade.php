@props([
    'items' => [],
    'allowMultiple' => false,
    'class' => '',
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'border border-[var(--color-border-light)] rounded-xl overflow-hidden',
        'flush' => 'border-t border-[var(--color-border-light)]',
        'separated' => 'space-y-3',
    ];
@endphp

<div class="{{ $variants[$variant] }} {{ $class }}" {{ $attributes->merge(['class' => '']) }}>
    @foreach($items as $index => $item)
        @php
            $isOpen = $item['open'] ?? false;
            $itemId = "accordion-{$index}-" . uniqid();
        @endphp

        @if($variant === 'separated')
            <div
                x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }"
                class="border border-[var(--color-border-light)] rounded-xl overflow-hidden"
            >
        @elseif($variant === 'flush')
            <div
                x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }"
                class="border-b border-[var(--color-border-light)] last:border-b-0"
            >
        @else
            <div
                x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }"
            >
        @endif

            <button
                @click="open = !open"
                :aria-expanded="open"
                class="w-full px-6 py-4 text-left font-semibold text-[var(--color-text-primary)] bg-white hover:bg-[var(--color-bg-primary)] transition-colors focus-ring flex items-center justify-between gap-4"
            >
                <span>{{ $item['title'] }}</span>
                <x-ui.icon
                    :name="open ? 'chevron-up' : 'chevron-down'"
                    class="h-5 w-5 text-[var(--color-text-muted)] transition-transform duration-200"
                    aria-hidden="true"
                />
            </button>

            <div
                x-show="open"
                x-collapse
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 max-h-0"
                x-transition:enter-end="opacity-100 max-h-[500px]"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 max-h-[500px]"
                x-transition:leave-end="opacity-0 max-h-0"
                class="overflow-hidden"
                role="region"
                aria-labelledby="{{ $itemId }}-trigger"
            >
                <div class="px-6 pb-6 text-[var(--color-text-secondary)]">
                    {{ $item['content'] }}
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    // Register x-collapse directive if not already
    if (!Alpine.directive('collapse')) {
        Alpine.directive('collapse', (el, { modifiers }, { cleanup }) => {
            const updateHeight = () => {
                if (el._x_doShow) {
                    el.style.maxHeight = el.scrollHeight + 'px';
                } else {
                    el.style.maxHeight = '0px';
                }
            };
            const observer = new MutationObserver(updateHeight);
            observer.observe(el, { childList: true, subtree: true, characterData: true });
            cleanup(() => observer.disconnect());
        });
    }
</script>