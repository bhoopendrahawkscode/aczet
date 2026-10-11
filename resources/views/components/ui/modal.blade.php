@props([
    'name' => null,
    'title' => null,
    'size' => 'md',
    'closeable' => true,
    'persistent' => false,
    'class' => '',
])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-[90vw]',
        'fullscreen' => 'max-w-full w-full h-full max-h-full',
    ];
@endphp

@if($name)
    <div
        x-data="{ open: false }"
        x-init="
            $watch('$store.modal.active', (v) => { open = v === '{{ $name }}'; });
            $watch('open', (v) => { if (v) { $store.modal.active = '{{ $name }}'; } else if ($store.modal.active === '{{ $name }}') { $store.modal.close(); } });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && open && !{{ $persistent ? 'true' : 'false' }}) { open = false; $store.modal.close(); } });
        "
    >
        <!-- Backdrop -->
        <div
            x-show="open"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-[var(--color-void)]/80 backdrop-blur-sm z-[var(--z-modal-backdrop)]"
            @click="{{ $persistent ? '' : 'open = false; $store.modal.close()' }}"
            aria-hidden="true"
        ></div>

        <!-- Modal -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="fixed inset-0 z-[var(--z-modal)] flex items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="{{ $name }}-title"
        >
            <div
                class="w-full {{ $sizes[$size] }} bg-white rounded-2xl shadow-xl {{ $class }}"
                @click.outside="{{ $persistent ? '' : 'open = false; $store.modal.close()' }}"
            >
                @if($title || $closeable)
                    <div class="flex items-start justify-between p-6 border-b border-[var(--color-border-light)]">
                        @if($title)
                            <h2 id="{{ $name }}-title" class="text-xl font-bold text-[var(--color-text-primary)]">{{ $title }}</h2>
                        @endif
                        @if($closeable)
                            <button
                                @click="open = false; $store.modal.close()"
                                class="p-2 rounded-xl text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:bg-[var(--color-bg-tertiary)] transition-colors focus-ring"
                                aria-label="Close modal"
                            >
                                <x-ui.icon name="x" class="h-5 w-5" />
                            </button>
                        @endif
                    </div>
                @endif

                <div class="p-6 {{ $size === 'fullscreen' ? 'h-full overflow-auto' : '' }}">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Inline modal (for use without store) -->
    <div
        {{ $attributes->merge(['class' => '']) }}
        class="fixed inset-0 z-[var(--z-modal)] flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        @if($title) aria-labelledby="modal-title" @endif
    >
        <div class="fixed inset-0 bg-[var(--color-void)]/80 backdrop-blur-sm" @click="$dispatch('close-modal')"></div>
        <div
            class="relative w-full {{ $sizes[$size] }} bg-white rounded-2xl shadow-xl {{ $class }}"
        >
            @if($title || $closeable)
                <div class="flex items-start justify-between p-6 border-b border-[var(--color-border-light)]">
                    @if($title)
                        <h2 id="modal-title" class="text-xl font-bold text-[var(--color-text-primary)]">{{ $title }}</h2>
                    @endif
                    @if($closeable)
                        <button @click="$dispatch('close-modal')" class="p-2 rounded-xl text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:bg-[var(--color-bg-tertiary)] transition-colors focus-ring" aria-label="Close modal">
                            <x-ui.icon name="x" class="h-5 w-5" />
                        </button>
                    @endif
                </div>
            @endif
            <div class="p-6 {{ $size === 'fullscreen' ? 'h-full overflow-auto' : '' }}">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif