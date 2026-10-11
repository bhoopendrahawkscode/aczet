@props([
    'variant' => 'default',
    'class' => '',
    'hover' => false,
    'padding' => 'lg',
])

@php
    $variants = [
        'default' => 'card-base',
        'product' => 'card-product',
        'feature' => 'card-feature',
        'stats' => 'card-stats',
        'glass' => 'card-glass',
    ];

    $paddings = [
        'none' => '',
        'sm' => 'p-3',
        'md' => 'p-5',
        'lg' => 'p-6',
        'xl' => 'p-8',
    ];

    $hoverClass = $hover ? 'card-hover' : '';
    $classes = "{$variants[$variant]} {$hoverClass} {$paddings[$padding]} {$class}";
@endphp

<div class="{{ $classes }}" {{ $attributes->merge(['class' => '']) }}>
    {{ $slot }}
</div>