@props([
    'items' => [],
    'class' => '',
    'schema' => false,
])

@php
    $schemaItems = [];
    $position = 1;
@endphp

<nav
    class="flex items-center gap-2 text-sm {{ $class }}"
    aria-label="Breadcrumb"
    {{ $attributes->merge(['class' => '']) }}
>
    @if($schema)
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                @foreach($items as $index => $item)
                    @php
                        $isLast = $index === array_key_last($items);
                        $schemaItems[] = [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $item['label'],
                            'item' => $isLast ? null : ($item['url'] ?? '#'),
                        ];
                    @endphp
                @endforeach
            ]
        }
        </script>
    @endif

    <ol class="flex items-center gap-2" @if($schema) itemscope itemtype="https://schema.org/BreadcrumbList" @endif>
        @foreach($items as $index => $item)
            @php
                $isLast = $index === array_key_last($items);
            @endphp
            <li class="flex items-center gap-2" @if($schema) itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" @endif>
                @if(!$isLast && isset($item['url']))
                    <a
                        href="{{ $item['url'] }}"
                        class="text-[var(--color-text-muted)] hover:text-orange-600 transition-colors flex items-center gap-1"
                        @if($schema) itemprop="item" @endif
                    >
                        @if(isset($item['icon']))
                            <x-ui.icon :name="{{ $item['icon'] }}" class="h-4 w-4" aria-hidden="true" />
                        @endif
                        <span @if($schema) itemprop="name" @endif>{{ $item['label'] }}</span>
                    </a>
                    @if($schema)
                        <meta itemprop="position" content="{{ $index + 1 }}" />
                    @endif
                @else
                    <span
                        class="text-[var(--color-text-primary)] font-medium flex items-center gap-1"
                        aria-current="page"
                        @if($schema) itemprop="item" @endif
                    >
                        @if(isset($item['icon']))
                            <x-ui.icon :name="{{ $item['icon'] }}" class="h-4 w-4" aria-hidden="true" />
                        @endif
                        <span @if($schema) itemprop="name" @endif>{{ $item['label'] }}</span>
                    </span>
                    @if($schema)
                        <meta itemprop="position" content="{{ $index + 1 }}" />
                    @endif
                @endif

                @if(!$isLast)
                    <x-ui.icon name="chevron-right" class="h-4 w-4 text-[var(--color-text-muted)] flex-shrink-0" aria-hidden="true" />
                @endif
            </li>
        @endforeach
    </ol>
</nav>