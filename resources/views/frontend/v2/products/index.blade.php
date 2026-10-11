@extends('layouts.app')

@php
    $currentLocale = $currentLocale ?? app()->getLocale();
    $currentRegion = $currentRegion ?? session('aczet_region', 'India');
    $categories = $categories ?? \App\Models\Category::all();
@endphp

@section('title', 'Product Catalog | Aczet Metrology')
@section('meta_description', 'Browse Aczet\'s complete range of precision weighing instruments. Filter by category, region, capacity, and readability. Compare models side-by-side.')

@section('styles')
<style>
    /* Catalog v2 Styles */
    .catalog-page {
        background: var(--color-bg-primary);
    }
    .catalog-sidebar {
        @apply w-full lg:w-72 flex-shrink-0;
    }
    .catalog-main {
        @apply flex-1 min-w-0;
    }
    .filter-section {
        @apply bg-white border border-[var(--color-border-light)] rounded-xl p-5 mb-5;
    }
    .filter-title {
        @apply font-bold text-[var(--color-text-primary)] mb-3 flex items-center justify-between;
    }
    .filter-option {
        @apply flex items-center gap-2 py-2 px-3 rounded-lg cursor-pointer hover:bg-[var(--color-bg-tertiary)] transition-colors;
    }
    .filter-option input[type="checkbox"] {
        @apply w-4 h-4 text-orange-600 border-[var(--color-border-medium)] rounded focus:ring-orange-500;
    }
    .product-grid {
        @apply grid gap-6;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
    .product-list {
        @apply space-y-4;
    }
    .product-list .product-card {
        @apply flex gap-4;
    }
    .product-list .product-card .card-stage {
        @apply w-48 h-48 flex-shrink-0;
    }
    .product-table {
        @apply w-full;
    }
    .product-table th {
        @apply px-4 py-3 text-left text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider bg-[var(--color-bg-tertiary)] sticky top-0;
    }
    .product-table td {
        @apply px-4 py-3 text-sm text-[var(--color-text-primary)] border-t border-[var(--color-border-light)];
    }
    .view-toggle {
        @apply flex gap-1 bg-[var(--color-bg-tertiary)] rounded-lg p-1;
    }
    .view-toggle button {
        @apply px-3 py-2 rounded-md text-sm font-medium text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:bg-white transition-colors;
    }
    .view-toggle button.active {
        @apply bg-white text-[var(--color-text-primary)] shadow-sm;
    }
    .comparison-drawer {
        @apply fixed inset-y-0 right-0 z-[var(--z-modal)] w-full lg:w-96 bg-white shadow-2xl transform transition-transform duration-300 lg:relative lg:shadow-none lg:transform-none;
    }
    .comparison-drawer.open {
        @apply translate-x-0;
    }
    .comparison-drawer.closed {
        @apply translate-x-full lg:translate-x-0;
    }
    .comparison-item {
        @apply flex items-center gap-3 p-3 bg-[var(--color-bg-tertiary)] rounded-lg;
    }
    .load-more-btn {
        @apply w-full py-4 px-6 text-center font-semibold text-[var(--color-text-primary)] bg-white border border-[var(--color-border-light)] rounded-xl hover:bg-[var(--color-bg-tertiary)] transition-colors focus-ring;
    }
    .skeleton-card {
        @apply bg-white border border-[var(--color-border-light)] rounded-2xl overflow-hidden animate-pulse;
    }
    .skeleton-image {
        @apply aspect-[4/3] bg-[var(--color-bg-tertiary)];
    }
    .skeleton-text {
        @apply h-4 bg-[var(--color-bg-tertiary)] rounded mx-4 mb-2;
    }
    .skeleton-text.short {
        @apply w-3/4;
    }
    .skeleton-text.long {
        @apply w-full;
    }
    .skeleton-button {
        @apply h-10 bg-[var(--color-bg-tertiary)] rounded mx-4 mb-4;
    }
</style>
@endsection

@section('content')
<div class="catalog-page">
    <!-- Page Header -->
    <div class="bg-white border-b border-[var(--color-border-light)] sticky top-0 z-[var(--z-sticky)]">
        <div class="container-responsive py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <nav class="flex items-center gap-2 text-sm text-[var(--color-text-muted)] mb-2" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}" class="hover:text-orange-600 transition-colors">Home</a>
                        <x-ui.icon name="chevron-right" class="h-4 w-4" />
                        <a href="{{ route('products.index') }}" class="hover:text-orange-600 transition-colors">Products</a>
                        <x-ui.icon name="chevron-right" class="h-4 w-4" />
                        <span class="text-[var(--color-text-primary)] font-medium">Catalog</span>
                    </nav>
                    <h1 class="text-3xl font-extrabold text-[var(--color-text-primary)]">Product Catalog</h1>
                    <p class="text-[var(--color-text-secondary)] mt-1">Explore our complete range of precision weighing instruments</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="view-toggle" role="group" aria-label="View mode">
                        <button onclick="setView('grid')" id="view-grid" class="active" aria-label="Grid view">
                            <x-ui.icon name="grid" class="h-5 w-5" />
                        </button>
                        <button onclick="setView('list')" id="view-list" aria-label="List view">
                            <x-ui.icon name="list" class="h-5 w-5" />
                        </button>
                        <button onclick="setView('table')" id="view-table" aria-label="Table view">
                            <x-ui.icon name="columns" class="h-5 w-5" />
                        </button>
                    </div>
                    <x-ui.button variant="primary" size="md" class="hidden lg:inline-flex" onclick="openComparisonDrawer()">
                        <x-ui.icon name="code-compare" class="h-5 w-5" />
                        Compare (<span id="compare-count">{{ Alpine.store('compare').count }}</span>/4)
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Comparison Button -->
    <div class="lg:hidden fixed bottom-4 right-4 z-[var(--z-sticky)]">
        <x-ui.button variant="primary" size="lg" class="shadow-xl" onclick="openComparisonDrawer()">
            <x-ui.icon name="code-compare" class="h-5 w-5" />
            Compare (<span id="mobile-compare-count">{{ Alpine.store('compare').count }}</span>/4)
        </x-ui.button>
    </div>

    <div class="container-responsive py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar: Filters -->
            <aside class="catalog-sidebar" aria-label="Product filters">
                <div class="sticky top-24 space-y-5">
                    <!-- Category Filter -->
                    <div class="filter-section">
                        <h3 class="filter-title">Category</h3>
                        <div class="space-y-2">
                            <label class="filter-option">
                                <input type="checkbox" value="" x-model="filters.category" @change="applyFilters()" class="hidden peer">
                                <span class="peer-checked:text-orange-600 peer-checked:font-medium">All Categories</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="filter-option">
                                    <input type="checkbox" value="{{ $cat->slug }}" x-model="filters.category" @change="applyFilters()">
                                    <span>{{ $cat->getLocalizedName($currentLocale) }}</span>
                                    <span class="text-[var(--color-text-muted)] text-sm ml-auto">({{ $cat->products_count ?? 0 }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Region Filter -->
                    <div class="filter-section">
                        <h3 class="filter-title">Region</h3>
                        <div class="space-y-2">
                            <label class="filter-option">
                                <input type="checkbox" value="" x-model="filters.region" @change="applyFilters()" class="hidden peer">
                                <span class="peer-checked:text-orange-600 peer-checked:font-medium">All Regions</span>
                            </label>
                            @foreach(['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'] as $region)
                                <label class="filter-option">
                                    <input type="checkbox" value="{{ $region }}" x-model="filters.region" @change="applyFilters()">
                                    <span>{{ $region }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Capacity Filter -->
                    <div class="filter-section">
                        <h3 class="filter-title">Capacity Range</h3>
                        <div class="space-y-2">
                            <label class="filter-option">
                                <input type="checkbox" value="0-100" x-model="filters.capacity" @change="applyFilters()">
                                <span>Up to 100g</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" value="100-500" x-model="filters.capacity" @change="applyFilters()">
                                <span>100g - 500g</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" value="500-2000" x-model="filters.capacity" @change="applyFilters()">
                                <span>500g - 2kg</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" value="2000+" x-model="filters.capacity" @change="applyFilters()">
                                <span>2kg+</span>
                            </label>
                        </div>
                    </div>

                    <!-- Readability Filter -->
                    <div class="filter-section">
                        <h3 class="filter-title">Readability</h3>
                        <div class="space-y-2">
                            <label class="filter-option">
                                <input type="checkbox" value="1ug" x-model="filters.readability" @change="applyFilters()">
                                <span>1 µg (0.001 mg)</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" value="0.01mg" x-model="filters.readability" @change="applyFilters()">
                                <span>0.01 mg</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" value="0.1mg" x-model="filters.readability" @change="applyFilters()">
                                <span>0.1 mg</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" value="1mg" x-model="filters.readability" @change="applyFilters()">
                                <span>1 mg</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" value="10mg+" x-model="filters.readability" @change="applyFilters()">
                                <span>10 mg+</span>
                            </label>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    @if(array_filter($filters)->count() > 0)
                        <x-ui.button variant="outline" size="md" class="w-full" onclick="clearFilters()">
                            <x-ui.icon name="filter" class="h-4 w-4 mr-2" /> Clear All Filters
                        </x-ui.button>
                    @endif
                </div>
            </aside>

            <!-- Main Content -->
            <main class="catalog-main">
                <!-- Results Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <span class="text-[var(--color-text-secondary)]" id="results-count">
                            {{ $products->total() }} products found
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-ui.input
                            type="search"
                            placeholder="Search products..."
                            class="max-w-xs sm:max-w-md"
                            x-model="searchQuery"
                            @input.debounce.300ms="applyFilters()"
                            id="catalog-search"
                        />
                    </div>
                </div>

                <!-- Products Container -->
                <div id="products-container">
                    @include('frontend.v2.products.partials._product-grid', ['products' => $products])
                </div>

                <!-- Load More / Pagination -->
                <div id="load-more-container" class="mt-8">
                    @if($products->hasMorePages())
                        <button
                            class="load-more-btn"
                            onclick="loadMoreProducts()"
                            id="load-more-btn"
                        >
                            Load More Products
                        </button>
                    @else
                        <p class="text-center text-[var(--color-text-muted)] py-8">All products loaded</p>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Comparison Drawer -->
    <div
        id="comparison-drawer"
        class="comparison-drawer closed"
        role="dialog"
        aria-label="Product comparison"
        aria-hidden="true"
    >
        <div class="flex flex-col h-full">
            <div class="p-4 border-b border-[var(--color-border-light)] flex items-center justify-between sticky top-0 bg-white z-10">
                <h2 class="text-xl font-bold text-[var(--color-text-primary)]">Comparison (<span id="drawer-compare-count">{{ Alpine.store('compare').count }}</span>/4)</h2>
                <button onclick="closeComparisonDrawer()" class="p-2 rounded-lg text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:bg-[var(--color-bg-tertiary)] transition-colors" aria-label="Close comparison">
                    <x-ui.icon name="x" class="h-5 w-5" />
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="comparison-items">
                <p class="text-center text-[var(--color-text-muted)] py-8" id="empty-comparison">Add products to compare</p>
            </div>
            <div class="p-4 border-t border-[var(--color-border-light)] sticky bottom-0 bg-white">
                <div class="flex gap-3">
                    <x-ui.button variant="outline" size="md" class="flex-1" onclick="clearComparison()">
                        <x-ui.icon name="trash" class="h-4 w-4 mr-2" /> Clear All
                    </x-ui.button>
                    <x-ui.button variant="primary" size="md" class="flex-1" onclick="goToComparisonPage()">
                        View Comparison
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    <!-- Drawer Overlay -->
    <div
        id="drawer-overlay"
        class="fixed inset-0 bg-[var(--color-void)]/50 z-[var(--z-modal-backdrop)] hidden lg:hidden"
        onclick="closeComparisonDrawer()"
        aria-hidden="true"
    ></div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize Alpine component for catalog
    document.addEventListener('alpine:init', () => {
        Alpine.data('catalog', () => ({
            view: 'grid',
            searchQuery: '',
            filters: {
                category: [],
                region: [],
                capacity: [],
                readability: [],
            },
            currentPage: 1,
            loading: false,
            hasMore: true,

            init() {
                this.view = localStorage.getItem('catalog_view') || 'grid';
                this.updateViewButtons();
                this.syncUrl();
                this.updateComparisonUI();

                // Listen for comparison store changes
                this.$watch('$store.compare.items', () => {
                    this.updateComparisonUI();
                });
            },

            setView(view) {
                this.view = view;
                localStorage.setItem('catalog_view', view);
                this.updateViewButtons();
            },

            updateViewButtons() {
                document.querySelectorAll('.view-toggle button').forEach(btn => {
                    btn.classList.remove('active');
                });
                document.getElementById('view-' + this.view)?.classList.add('active');
            },

            async applyFilters() {
                this.currentPage = 1;
                this.loading = true;
                const btn = document.getElementById('load-more-btn');
                if (btn) btn.disabled = true;

                const params = new URLSearchParams();
                params.set('page', 1);
                if (this.searchQuery) params.set('q', this.searchQuery);
                if (this.filters.category.length) params.set('category', this.filters.category.join(','));
                if (this.filters.region.length) params.set('region', this.filters.region.join(','));
                if (this.filters.capacity.length) params.set('capacity', this.filters.capacity.join(','));
                if (this.filters.readability.length) params.set('readability', this.filters.readability.join(','));
                params.set('view', this.view);

                try {
                    const response = await fetch('{{ route('products.index') }}?' + params.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
                    });
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('products-container')?.innerHTML;
                    if (newContent) {
                        document.getElementById('products-container').innerHTML = newContent;
                    }
                    this.hasMore = doc.getElementById('load-more-container')?.querySelector('.load-more-btn') !== null;
                    this.currentPage = 1;
                    window.history.replaceState({}, '', '{{ route('products.index') }}?' + params.toString());
                } catch (e) {
                    console.error('Filter error:', e);
                } finally {
                    this.loading = false;
                    if (btn) btn.disabled = false;
                }
            },

            async loadMoreProducts() {
                if (this.loading || !this.hasMore) return;
                this.loading = true;
                const btn = document.getElementById('load-more-btn');
                if (btn) btn.disabled = true;
                btn.innerHTML = '<x-ui.icon name="loader" class="h-5 w-5 animate-spin mr-2" />Loading...';

                this.currentPage++;
                const params = new URLSearchParams(window.location.search);
                params.set('page', this.currentPage);

                try {
                    const response = await fetch('{{ route('products.index') }}?' + params.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
                    });
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('products-container')?.innerHTML;
                    if (newContent) {
                        document.getElementById('products-container').insertAdjacentHTML('beforeend', newContent);
                    }
                    this.hasMore = doc.getElementById('load-more-container')?.querySelector('.load-more-btn') !== null;
                } catch (e) {
                    console.error('Load more error:', e);
                } finally {
                    this.loading = false;
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = 'Load More Products';
                    }
                }
            },

            clearFilters() {
                this.filters = { category: [], region: [], capacity: [], readability: [] };
                this.searchQuery = '';
                this.applyFilters();
            },

            syncUrl() {
                const params = new URLSearchParams(window.location.search);
                if (params.has('category')) this.filters.category = params.get('category').split(',');
                if (params.has('region')) this.filters.region = params.get('region').split(',');
                if (params.has('capacity')) this.filters.capacity = params.get('capacity').split(',');
                if (params.has('readability')) this.filters.readability = params.get('readability').split(',');
                if (params.has('q')) this.searchQuery = params.get('q');
                if (params.has('view')) this.view = params.get('view');
            },

            openComparisonDrawer() {
                document.getElementById('comparison-drawer').classList.remove('closed');
                document.getElementById('comparison-drawer').classList.add('open');
                document.getElementById('comparison-drawer').setAttribute('aria-hidden', 'false');
                document.getElementById('drawer-overlay')?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                this.updateComparisonItems();
            },

            closeComparisonDrawer() {
                document.getElementById('comparison-drawer').classList.remove('open');
                document.getElementById('comparison-drawer').classList.add('closed');
                document.getElementById('comparison-drawer').setAttribute('aria-hidden', 'true');
                document.getElementById('drawer-overlay')?.classList.add('hidden');
                document.body.style.overflow = '';
            },

            updateComparisonUI() {
                const count = Alpine.store('compare').count;
                document.getElementById('compare-count')?.textContent = count;
                document.getElementById('mobile-compare-count')?.textContent = count;
                document.getElementById('drawer-compare-count')?.textContent = count;
                this.updateComparisonItems();
            },

            updateComparisonItems() {
                const container = document.getElementById('comparison-items');
                const emptyMsg = document.getElementById('empty-comparison');
                const items = Alpine.store('compare').items;

                if (items.length === 0) {
                    container.innerHTML = '<p class="text-center text-[var(--color-text-muted)] py-8" id="empty-comparison">Add products to compare</p>';
                    return;
                }

                if (emptyMsg) emptyMsg.remove();

                container.innerHTML = items.map(item => `
                    <div class="comparison-item" data-id="${item.id}">
                        <img src="${item.image}" alt="${item.name}" class="w-12 h-12 rounded-lg object-cover">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-[var(--color-text-primary)] truncate">${item.name}</p>
                            <p class="text-sm text-[var(--color-text-muted)]">${item.model}</p>
                        </div>
                        <button onclick="Alpine.store('compare').remove(${item.id})" class="p-1 text-[var(--color-text-muted)] hover:text-rose-500 transition-colors" aria-label="Remove ${item.name}">
                            <x-ui.icon name="x" class="h-5 w-5" />
                        </button>
                    </div>
                `).join('');
            },

            clearComparison() {
                Alpine.store('compare').clear();
                this.updateComparisonUI();
            },

            goToComparisonPage() {
                window.location.href = '{{ route('compare') }}?ids=' + Alpine.store('compare').items.map(i => i.id).join(',');
            },
        }));
    });
</script>