@props([
    'name' => null,
    'class' => 'h-5 w-5',
    'ariaHidden' => true,
])

@php
    // Check if the icon exists in our sprite
    $iconPath = base_path("resources/icons/{$name}.svg");
    $iconExists = file_exists($iconPath);
    
    if (!$iconExists) {
        $name = 'help-circle';
    }
    
    $ariaAttr = $ariaHidden ? 'aria-hidden="true"' : '';
@endphp

<svg
    class="{{ $class }}"
    {{ $ariaAttr }}
    {{ $attributes->merge(['class' => '']) }}
    role="img"
    aria-label="{{ $name }}"
>
    <use href="{{ asset('build/sprite.svg#icon-' . $name) }}" />
</svg>