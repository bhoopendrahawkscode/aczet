@props([
    'trigger' => null,
    'align' => 'left',
    'width' => 'auto',
    'class' => '',
])

@php
    $alignClasses = [
        'left' => 'left-0',
        'right' => 'right-0',
        'center' => 'left-1/2 -translate-x-1/2',
    ];
    $widthClasses = [
        'auto' => 'min-w-[200px] w-auto',
        'sm' => 'w-48',
        'md' => 'w-64',
        'lg' => 'w-80',
        'xl' => 'w-96',
        'full' => 'w-full',
    ];
@endphp

<div
    x-data="dropdown()"
    class="relative inline-block"
    {{ $attributes->merge(['class' => '']) }}
>
    <!-- Trigger -->
    <div
        x-ref="trigger"
        @click="toggle"
        @keydown.space.prevent="toggle"
        @keydown.enter.prevent="toggle"
        @keydown.escape="close"
        class="{{ $trigger['class'] ?? 'inline-flex items-center gap-2' }}"
        {{ $trigger['attributes'] ?? '' }}
    >
        {{ $trigger['content'] ?? $slot }}
    </div>

    <!-- Dropdown Content -->
    <div
        x-ref="content"
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute z-[var(--z-dropdown)] mt-2 rounded-xl bg-white border border-[var(--color-border-light)] shadow-lg {{ $widthClasses[$width] }} {{ $alignClasses[$align] }} {{ $class }}"
        role="menu"
        aria-orientation="vertical"
        style="min-width: 200px;"
    >
        <div class="py-1.5" {{ $attributes->merge(['class' => '']) }}>
            {{ $slot }}
        </div>
    </div>
</div>

<script>
    // Register dropdown component if not already registered
    if (!Alpine.stores.dropdown) {
        Alpine.data('dropdown', () => ({
            open: false,
            toggle() { this.open = !this.open; },
            close() { this.open = false; },
            handleClickOutside(e) {
                if (this.$refs.trigger && !this.$refs.trigger.contains(e.target) &&
                    this.$refs.content && !this.$refs.content.contains(e.target)) {
                    this.close();
                }
            },
            handleKeydown(e) {
                if (e.key === 'Escape') this.close();
                if (e.key === 'Tab' && this.$refs.content) {
                    const focusable = this.$refs.content.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                    const first = focusable[0];
                    const last = focusable[focusable.length - 1];
                    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
                }
            },
            init() {
                this.$watch('open', (value) => {
                    if (value) {
                        document.addEventListener('click', this.handleClickOutside.bind(this));
                        document.addEventListener('keydown', this.handleKeydown.bind(this));
                    } else {
                        document.removeEventListener('click', this.handleClickOutside.bind(this));
                        document.removeEventListener('keydown', this.handleKeydown.bind(this));
                    }
                });
            }
        }));
    }
</script>