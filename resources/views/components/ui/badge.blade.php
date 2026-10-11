@props([
    'variant' => 'default',
    'size' => 'md',
    'dot' => false,
    'dotColor' => null,
    'class' => '',
])

@php
    $variants = [
        'default' => 'badge-base bg-[var(--color-bg-tertiary)] text-[var(--color-text-secondary)] border border-[var(--color-border-light)]',
        'primary' => 'badge-primary',
        'secondary' => 'badge-base bg-[var(--color-slate)] text-white',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'error' => 'badge-error',
        'info' => 'badge-info',
        'outline' => 'badge-outline',
        'glass' => 'badge-glass',
    ];

    $sizes = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-1 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
    ];

    $classes = "{$variants[$variant]} {$sizes[$size]} {$class}";
@endphp

<span class="{{ $classes }}" {{ $attributes->merge(['class' => '']) }}>
    @if($dot)
        <span
            class="h-1.5 w-1.5 rounded-full flex-shrink-0"
            style="background-color: {{ $dotColor ?? 'currentColor' }}"
            aria-hidden="true"
        ></span>
    @endif
    {{ $slot }}
</span>