@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'class' => '',
    'href' => null,
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'outline' => 'btn-outline',
        'ghost' => 'btn-ghost',
        'glass' => 'btn-glass',
        'destructive' => 'btn-destructive',
        'success' => 'btn-success',
    ];

    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-5 py-2.5 text-base',
        'lg' => 'px-7 py-3.5 text-lg',
        'xl' => 'px-9 py-4 text-xl',
    ];

    $classes = "btn-base {$variants[$variant]} {$sizes[$size]} {$class}";
@endphp

@if($href)
    <a
        href="{{ $href }}"
        class="{{ $classes }}"
        {{ $attributes->merge(['class' => '']) }}
        @if($disabled) tabindex="-1" aria-disabled="true" @endif
    >
        @if($loading)
            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        class="{{ $classes }}"
        {{ $attributes->merge(['class' => '']) }}
        @if($disabled) disabled @endif
    >
        @if($loading)
            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
        @endif
        {{ $slot }}
    </button>
@endif