@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'class' => '',
    'icon' => null,
    'iconPosition' => 'left',
    'autocomplete' => 'off',
])

@php
    $id = $id ?? $name ?? 'input-' . uniqid();
    $inputClasses = 'input-base px-4 py-3';

    if ($error) {
        $inputClasses .= ' input-error';
    } elseif ($hasErrors = session()->has("errors.{$name}")) {
        $inputClasses .= ' input-error';
        $error = session("errors.{$name}")[0];
    }
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-[var(--color-text-primary)] mb-2">
            {{ $label }}
            @if($required)
                <span class="text-rose-500 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[var(--color-text-muted)]">
                <x-ui.icon :name="$icon" class="h-5 w-5" />
            </div>
        @endif

        @if($type === 'textarea')
            <textarea
                id="{{ $id }}"
                name="{{ $name }}"
                class="{{ $inputClasses }} {{ $icon && $iconPosition === 'left' ? 'pl-10' : '' }} {{ $icon && $iconPosition === 'right' ? 'pr-10' : '' }} resize-y min-h-[100px] font-sans"
                placeholder="{{ $placeholder }}"
                value="{{ old($name, $value) }}"
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
                @if($required) required @endif
                autocomplete="{{ $autocomplete }}"
                {{ $attributes->merge(['class' => '']) }}
            >{{ old($name, $value) }}</textarea>
        @elseif($type === 'select')
            <select
                id="{{ $id }}"
                name="{{ $name }}"
                class="{{ $inputClasses }} {{ $icon && $iconPosition === 'left' ? 'pl-10' : '' }} {{ $icon && $iconPosition === 'right' ? 'pr-10' : '' }} appearance-none bg-no-repeat bg-right-3 bg-[length:20px_20px] bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2364748B%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] pr-10"
                @if($disabled) disabled @endif
                @if($required) required @endif
                autocomplete="{{ $autocomplete }}"
                {{ $attributes->merge(['class' => '']) }}
            >
                @if($placeholder)
                    <option value="" disabled selected hidden>{{ $placeholder }}</option>
                @endif
                {{ $slot }}
            </select>
        @else
            <input
                type="{{ $type }}"
                id="{{ $id }}"
                name="{{ $name }}"
                class="{{ $inputClasses }} {{ $icon && $iconPosition === 'left' ? 'pl-10' : '' }} {{ $icon && $iconPosition === 'right' ? 'pr-10' : '' }}"
                placeholder="{{ $placeholder }}"
                value="{{ old($name, $value) }}"
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
                @if($required) required @endif
                autocomplete="{{ $autocomplete }}"
                {{ $attributes->merge(['class' => '']) }}
            />
        @endif

        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[var(--color-text-muted)]">
                <x-ui.icon :name="$icon" class="h-5 w-5" />
            </div>
        @endif
    </div>

    @if($error)
        <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400" role="alert">{{ $error }}</p>
    @elseif($hint)
        <p class="mt-1.5 text-sm text-[var(--color-text-muted)]">{{ $hint }}</p>
    @endif
</div>