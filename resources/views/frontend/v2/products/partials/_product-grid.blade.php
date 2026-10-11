@php
    $view = $view ?? 'grid';
    $currentLocale = $currentLocale ?? app()->getLocale();
@endphp

<div
    id="products-container"
    class="{{ $view === 'grid' ? 'product-grid' : ($view === 'list' ? 'product-list' : '') }}"
    role="list"
    aria-label="Products"
>
    @foreach($products as $product)
        @php
            $productName = $product->getLocalizedName($currentLocale);
            $shortDesc = $product->getLocalizedShortDescription($currentLocale);
            $inCompare = \App\Facades\Session::has('aczet_compare') && in_array($product->id, array_keys(\App\Facades\Session::get('aczet_compare', [])));
            $inCart = \App\Facades\Session::has('aczet_quote_cart') && in_array($product->id, array_keys(\App\Facades\Session::get('aczet_quote_cart', [])));
        @endphp
        <article
            class="product-card bg-white border border-[var(--color-border-light)] rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-[var(--color-border-medium)] hover:-translate-y-1 {{ $view === 'list' ? 'flex' : '' }}"
            role="listitem"
        >
            <!-- Product Image -->
            <div class="card-stage relative aspect-[4/3] bg-[var(--color-bg-tertiary)] overflow-hidden {{ $view === 'list' ? 'w-48 h-48 flex-shrink-0' : 'w-full' }}">
                @if($product->main_image)
                    <img
                        src="{{ $product->main_image }}"
                        alt="{{ $productName }}"
                        class="w-full h-full object-contain p-4 transition-transform duration-300 hover:scale-105"
                        loading="lazy"
                    >
                @else
                    <div class="w-full h-full flex items-center justify-center text-[var(--color-text-muted)]">
                        <x-ui.icon name="image" class="h-12 w-12" />
                    </div>
                @endif

                <!-- Badges -->
                <div class="absolute top-3 left-3 right-3 flex justify-between">
                    <span class="px-2 py-1 bg-[var(--color-text-primary)] text-white text-xs font-bold uppercase tracking-wider rounded-full">
                        {{ $product->model_number }}
                    </span>
                    <span class="px-2 py-1 bg-white text-orange-600 border border-orange-200 text-xs font-bold uppercase tracking-wider rounded-full">
                        {{ $product->category?->getLocalizedName($currentLocale) ?? 'Instrument' }}
                    </span>
                </div>

                <!-- Quick Actions -->
                <div class="absolute bottom-3 left-3 right-3 flex gap-2 opacity-0 transition-opacity duration-200">
                    <button
                        type="button"
                        class="flex-1 px-3 py-2 bg-white/90 backdrop-blur-sm text-[var(--color-text-primary)] text-sm font-semibold rounded-lg hover:bg-white transition-colors focus-ring"
                        onclick="openQuoteModal('Catalog Card - {{ $productName }}', {{ $product->id }}, '{{ addslashes($productName) }}')"
                        aria-label="Get quote for {{ $productName }}"
                    >
                        <x-ui.icon name="send" class="h-4 w-4 mr-1 inline" /> Quote
                    </button>
                    <button
                        type="button"
                        class="px-3 py-2 bg-white/90 backdrop-blur-sm text-[var(--color-text-primary)] text-sm font-semibold rounded-lg hover:bg-white transition-colors focus-ring"
                        onclick="Alpine.store('compare').add({{ json_encode(['id' => $product->id, 'name' => $productName, 'model' => $product->model_number, 'image' => $product->main_image]) }})"
                        aria-label="Add {{ $productName }} to comparison"
                    >
                        <x-ui.icon name="code-compare" class="h-4 w-4" />
                    </button>
                    <button
                        type="button"
                        class="px-3 py-2 bg-white/90 backdrop-blur-sm text-[var(--color-text-primary)] text-sm font-semibold rounded-lg hover:bg-white transition-colors focus-ring"
                        onclick="Alpine.store('cart').add({{ json_encode(['id' => $product->id, 'name' => $productName, 'model' => $product->model_number, 'image' => $product->main_image]) }})"
                        aria-label="Add {{ $productName }} to quote cart"
                    >
                        <x-ui.icon name="cart" class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-5 {{ $view === 'list' ? 'flex-1 flex flex-col justify-between' : 'flex-1 flex flex-col' }}">
                <div>
                    <h3 class="font-bold text-[var(--color-text-primary)] text-lg mb-1 line-clamp-1">
                        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-orange-600 transition-colors">{{ $productName }}</a>
                    </h3>
                    <p class="text-sm text-[var(--color-text-secondary)] line-clamp-2 mb-3">{{ $product->getLocalizedShortDescription($currentLocale) }}</p>
                </div>

                <!-- Quick Specs -->
                @if($product->specifications->count() > 0)
                    <div class="flex flex-wrap gap-2 mb-4" role="list" aria-label="Key specifications">
                        @foreach($product->specifications->take(3) as $spec)
                            <span class="px-2 py-1 bg-[var(--color-bg-tertiary)] text-[var(--color-text-secondary)] text-xs rounded-full" role="listitem">
                                <span class="font-medium text-[var(--color-text-primary)]">{{ $spec->spec_key }}: </span>{{ $spec->spec_value }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Price -->
                @if($product->price)
                    <div class="mb-4">
                        <span class="text-xl font-extrabold text-orange-600">${{ number_format($product->price, 2) }}</span>
                        <span class="text-[var(--color-text-muted)] ml-1">USD</span>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="px-5 pb-5 pt-0 {{ $view === 'list' ? 'flex-shrink-0 w-48' : '' }} flex flex-col gap-2">
                <a
                    href="{{ route('products.show', $product->slug) }}"
                    class="w-full px-4 py-2.5 bg-[var(--color-text-primary)] text-white text-sm font-semibold rounded-xl hover:bg-[var(--color-navy)] transition-colors focus-ring text-center"
                >
                    View Details
                    <x-ui.icon name="arrow-right" class="h-4 w-4 ml-1 inline" />
                </a>
                <div class="flex gap-2">
                    <button
                        type="button"
                        class="flex-1 px-3 py-2 border border-[var(--color-border-medium)] text-[var(--color-text-primary)] text-sm font-semibold rounded-xl hover:bg-[var(--color-bg-tertiary)] transition-colors focus-ring"
                        onclick="openQuoteModal('Catalog Card - {{ $productName }}', {{ $product->id }}, '{{ addslashes($productName) }}')"
                        aria-label="Get quote for {{ $productName }}"
                    >
                        <x-ui.icon name="send" class="h-4 w-4 mr-1 inline" /> Quote
                    </button>
                    <button
                        type="button"
                        class="px-3 py-2 border border-[var(--color-border-medium)] text-[var(--color-text-primary)] text-sm font-semibold rounded-xl hover:bg-[var(--color-bg-tertiary)] transition-colors focus-ring"
                        onclick="Alpine.store('compare').add({{ json_encode(['id' => $product->id, 'name' => $productName, 'model' => $product->model_number, 'image' => $product->main_image]) }})"
                        aria-label="Add {{ $productName }} to comparison"
                        :class="{ 'bg-orange-100 border-orange-300 text-orange-700': Alpine.store('compare').has({{ $product->id }) } }"
                    >
                        <x-ui.icon name="code-compare" class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </article>
    @endforeach
</div>

@if($products->count() === 0)
    <div class="text-center py-16">
        <x-ui.icon name="search" class="h-16 w-16 text-[var(--color-text-muted)] mx-auto mb-4" />
        <h3 class="text-xl font-bold text-[var(--color-text-primary)] mb-2">No products found</h3>
        <p class="text-[var(--color-text-secondary)]">Try adjusting your filters or search terms</p>
        <x-ui.button variant="outline" size="md" class="mt-4" onclick="clearFilters()">
            <x-ui.icon name="filter" class="h-4 w-4 mr-2" /> Clear Filters
        </x-ui.button>
    </div>
@endif