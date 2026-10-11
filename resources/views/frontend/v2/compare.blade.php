@extends('layouts.app')

@php
    $currentLocale = $currentLocale ?? app()->getLocale();
    $currentRegion = $currentRegion ?? session('aczet_region', 'India');
    $productIds = $productIds ?? explode(',', request()->query('ids', ''));
    $products = \App\Models\Product::with(['category', 'specifications', 'faqs'])
        ->whereIn('id', array_filter($productIds))
        ->get();
    $allProducts = \App\Models\Product::where('is_active', true)->get(['id', 'name', 'model_number']);
    $shareUrl = request()->fullUrl();
@endphp

@section('title', 'Product Comparison | Aczet Metrology')
@section('meta_description', 'Compare Aczet precision instruments side-by-side. View specifications, features, and pricing. Export to PDF or CSV.')

@section('styles')
<style>
    /* Comparison v2 Styles */
    .compare-page {
        background: var(--color-bg-primary);
    }
    .compare-header {
        @apply sticky top-0 z-[var(--z-sticky)] bg-white/95 backdrop-blur-sm border-b border-[var(--color-border-light)];
    }
    .compare-table-container {
        @apply overflow-x-auto;
    }
    .compare-table {
        @apply w-full min-w-max border-collapse;
    }
    .compare-table th,
    .compare-table td {
        @apply px-4 py-3 text-sm border border-[var(--color-border-light)];
    }
    .compare-table th {
        @apply bg-[var(--color-bg-tertiary)] font-semibold text-[var(--color-text-primary)] text-left sticky top-0 z-10;
    }
    .compare-table th:first-child,
    .compare-table td:first-child {
        @apply sticky left-0 z-20 bg-white min-w-[200px] max-w-[250px];
    }
    .compare-table th:first-child {
        @apply z-30 bg-[var(--color-bg-tertiary)];
    }
    .compare-table tbody tr:nth-child(even) td {
        @apply bg-[var(--color-bg-primary)];
    }
    .compare-table tbody tr:hover td {
        @apply bg-orange-50;
    }
    .spec-name {
        @apply font-medium text-[var(--color-text-secondary)] whitespace-nowrap;
    }
    .spec-value {
        @apply font-medium text-[var(--color-text-primary)];
    }
    .spec-value.different {
        @apply bg-amber-50 text-orange-700 font-bold;
    }
    .product-header {
        @apply text-center p-4;
    }
    .product-image {
        @apply w-full h-32 object-contain mx-auto mb-3;
    }
    .product-title {
        @apply font-bold text-[var(--color-text-primary)] mb-1 line-clamp-1;
    }
    .product-model {
        @apply text-sm font-semibold text-orange-600 mb-2;
    }
    .product-category {
        @apply text-xs text-[var(--color-text-muted)] uppercase tracking-wider mb-3;
    }
    .compare-actions {
        @apply sticky bottom-0 z-[var(--z-sticky)] bg-white/95 backdrop-blur-sm border-t border-[var(--color-border-light)] p-4 flex flex-col sm:flex-row gap-3;
    }
    .diff-badge {
        @apply inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800;
    }
    .empty-state {
        @apply text-center py-16;
    }
    .empty-state-icon {
        @apply h-16 w-16 text-[var(--color-border-medium)] mx-auto mb-4;
    }
    .share-dialog {
        @apply fixed inset-0 bg-[var(--color-void)]/50 z-[var(--z-modal-backdrop)] flex items-center justify-center hidden;
    }
    .share-dialog.open {
        @apply flex;
    }
    .share-dialog-content {
        @apply bg-white rounded-2xl p-6 max-w-md w-full mx-4;
    }
    .toast {
        @apply fixed bottom-4 right-4 z-[var(--z-toast)] px-4 py-3 rounded-xl shadow-xl flex items-center gap-3 animate-slide-up;
    }
    .toast.success {
        @apply bg-emerald-600 text-white;
    }
    .toast.error {
        @apply bg-rose-600 text-white;
    }
</style>
@endsection

@section('content')
<div class="compare-page">
    <!-- Page Header -->
    <header class="compare-header">
        <div class="container-responsive py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <nav class="flex items-center gap-2 text-sm text-[var(--color-text-muted)] mb-2" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}" class="hover:text-orange-600 transition-colors">Home</a>
                        <x-ui.icon name="chevron-right" class="h-4 w-4" />
                        <a href="{{ route('products.index') }}" class="hover:text-orange-600 transition-colors">Products</a>
                        <x-ui.icon name="chevron-right" class="h-4 w-4" />
                        <span class="text-[var(--color-text-primary)] font-medium">Comparison</span>
                    </nav>
                    <h1 class="text-3xl font-extrabold text-[var(--color-text-primary)]">Product Comparison</h1>
                    <p class="text-[var(--color-text-secondary)] mt-1">Compare specifications side-by-side. Export to PDF or CSV.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <x-ui.button variant="outline" size="md" onclick="addProductToCompare()">
                        <x-ui.icon name="plus" class="h-4 w-4 mr-2" /> Add Product
                    </x-ui.button>
                    <x-ui.button variant="secondary" size="md" onclick="exportCSV()">
                        <x-ui.icon name="download" class="h-4 w-4 mr-2" /> Export CSV
                    </x-ui.button>
                    <x-ui.button variant="primary" size="md" onclick="exportPDF()">
                        <x-ui.icon name="file-text" class="h-4 w-4 mr-2" /> Export PDF
                    </x-ui.button>
                    <x-ui.button variant="ghost" size="md" onclick="openShareDialog()">
                        <x-ui.icon name="share" class="h-4 w-4 mr-2" /> Share
                    </x-ui.button>
                </div>
            </div>
        </div>
    </header>

    <!-- Product Selector Bar -->
    <div class="bg-white border-b border-[var(--color-border-light)] px-4 py-3 sticky top-[var(--header-height)] z-[var(--z-sticky)-1]">
        <div class="container-responsive">
            <form id="add-product-form" class="flex items-center gap-3" onsubmit="event.preventDefault(); addProductToCompare(document.getElementById('add-product-select').value);">
                <label for="add-product-select" class="sr-only">Add product to comparison</label>
                <select
                    id="add-product-select"
                    class="flex-1 max-w-md px-4 py-2 border border-[var(--color-border-medium)] rounded-lg text-sm focus-ring"
                    aria-label="Select product to add"
                >
                    <option value="">+ Add Product to Comparison...</option>
                    @foreach($allProducts as $p)
                        <option value="{{ $p->id }}" {{ in_array($p->id, $productIds) ? 'disabled' : '' }}>
                            {{ $p->getLocalizedName($currentLocale) }} ({{ $p->model_number }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary whitespace-nowrap" :disabled="document.getElementById('add-product-select').value === ''">
                    <x-ui.icon name="plus" class="h-4 w-4 mr-2" /> Add
                </button>
                <a href="{{ route('compare') }}?ids={{ implode(',', $productIds) }}" class="btn-outline whitespace-nowrap" title="Copy link">
                    <x-ui.icon name="link" class="h-4 w-4 mr-2" /> Share
                </a>
                @if(count($productIds) > 0)
                    <a href="{{ route('compare') }}" class="btn-ghost whitespace-nowrap">
                        <x-ui.icon name="x" class="h-4 w-4 mr-2" /> Clear All
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="container-responsive py-8">
        @if($products->count() > 0)
            <div class="compare-table-container">
                <table class="compare-table" role="table" aria-label="Product comparison">
                    <thead>
                        <tr>
                            <th scope="col" class="w-[200px]">Specification</th>
                            @foreach($products as $product)
                                <th scope="col" class="w-[280px] product-header">
                                    <img src="{{ $product->main_image }}" alt="{{ $product->getLocalizedName($currentLocale) }}" class="product-image" loading="lazy">
                                    <h3 class="product-title">{{ $product->getLocalizedName($currentLocale) }}</h3>
                                    <div class="product-model">{{ $product->model_number }}</div>
                                    <div class="product-category">{{ $product->category?->getLocalizedName($currentLocale) }}</div>
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn-outline text-xs px-3 py-1.5">
                                            <x-ui.icon name="eye" class="h-3 w-3 mr-1" /> Details
                                        </a>
                                        <button class="btn-primary text-xs px-3 py-1.5" onclick="openQuoteModal('Comparison - {{ $product->getLocalizedName($currentLocale) }}', {{ $product->id }}, '{{ addslashes($product->getLocalizedName($currentLocale)) }}')">
                                            <x-ui.icon name="send" class="h-3 w-3 mr-1" /> Quote
                                        </button>
                                        <button class="btn-ghost text-xs p-2" onclick="removeFromComparison({{ $product->id }})" aria-label="Remove {{ $product->getLocalizedName($currentLocale) }}">
                                            <x-ui.icon name="x" class="h-4 w-4" />
                                        </button>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Category Row -->
                        <tr>
                            <th class="spec-name">Category</th>
                            @foreach($products as $product)
                                <td class="spec-value">{{ $product->category?->getLocalizedName($currentLocale) }}</td>
                            @endforeach
                        </tr>

                        <!-- Overview Row -->
                        <tr>
                            <th class="spec-name">Overview</th>
                            @foreach($products as $product)
                                <td class="spec-value text-sm">{{ Str::limit($product->getLocalizedShortDescription($currentLocale), 150) }}</td>
                            @endforeach
                        </tr>

                        <!-- Specifications -->
                        @php
                            $allSpecKeys = [];
                            foreach($products as $p) {
                                foreach($p->specifications as $s) {
                                    $allSpecKeys[$s->spec_key] = true;
                                }
                            }
                            ksort($allSpecKeys);
                        @endphp

                        @foreach(array_keys($allSpecKeys) as $specKey)
                            @php
                                $values = [];
                                foreach($products as $p) {
                                    $spec = $p->specifications->where('spec_key', $specKey)->first();
                                    $values[$p->id] = $spec ? $spec->spec_value : null;
                                }
                                $uniqueValues = array_unique(array_filter($values));
                                $isDifferent = count($uniqueValues) > 1;
                            @endphp
                            <tr class="{{ $isDifferent ? 'bg-amber-50/50' : '' }}">
                                <th class="spec-name {{ $isDifferent ? 'font-bold text-orange-700' : '' }}">
                                    {{ $specKey }}
                                    @if($isDifferent)
                                        <span class="diff-badge ml-2">Differs</span>
                                    @endif
                                </th>
                                @foreach($products as $product)
                                    <td class="spec-value {{ $isDifferent ? 'different' : '' }}">
                                        {{ $values[$product->id] ?? '<span class="text-[var(--color-text-muted)]">—</span>' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        <!-- Compliance Section -->
                        <tr class="bg-[var(--color-bg-tertiary)]">
                            <th class="spec-name font-bold text-[var(--color-text-primary)]" colspan="{{ 1 + count($products) }}">Compliance & Certifications</th>
                        </tr>
                        <tr>
                            <th class="spec-name">ISO 9001</th>
                            @foreach($products as $product)
                                <td class="spec-value"><x-ui.icon name="check-circle" class="h-5 w-5 text-emerald-600 inline" /> Yes</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="spec-name">ISO 17025 Calibration</th>
                            @foreach($products as $product)
                                <td class="spec-value"><x-ui.icon name="check-circle" class="h-5 w-5 text-emerald-600 inline" /> Available</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="spec-name">USP Chapter 41</th>
                            @foreach($products as $product)
                                <td class="spec-value">
                                    @if(str_contains(strtolower($product->name), 'analytical') || str_contains(strtolower($product->name), 'micro'))
                                        <x-ui.icon name="check-circle" class="h-5 w-5 text-emerald-600 inline" /> Compliant
                                    @else
                                        <x-ui.icon name="x-circle" class="h-5 w-5 text-rose-500 inline" /> N/A
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="spec-name">CE Marked</th>
                            @foreach($products as $product)
                                <td class="spec-value"><x-ui.icon name="check-circle" class="h-5 w-5 text-emerald-600 inline" /> Yes</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="spec-name">RoHS Compliant</th>
                            @foreach($products as $product)
                                <td class="spec-value"><x-ui.icon name="check-circle" class="h-5 w-5 text-emerald-600 inline" /> Yes</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Sticky Actions Bar -->
            <div class="compare-actions">
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <x-ui.button variant="outline" size="md" class="flex-1" onclick="exportCSV()">
                        <x-ui.icon name="download" class="h-4 w-4 mr-2" /> Export CSV
                    </x-ui.button>
                    <x-ui.button variant="primary" size="md" class="flex-1" onclick="exportPDF()">
                        <x-ui.icon name="file-text" class="h-4 w-4 mr-2" /> Export PDF
                    </x-ui.button>
                    <x-ui.button variant="ghost" size="md" onclick="openShareDialog()">
                        <x-ui.icon name="share" class="h-4 w-4 mr-2" /> Share
                    </x-ui.button>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <x-ui.icon name="code-compare" class="empty-state-icon" />
                <h2 class="text-2xl font-bold text-[var(--color-text-primary)] mb-2">No products selected</h2>
                <p class="text-[var(--color-text-secondary)] mb-6 max-w-md mx-auto">Select products from the catalog or dropdown above to start comparing.</p>
                <a href="{{ route('products.index') }}" class="btn-primary">
                    <x-ui.icon name="grid" class="h-4 w-4 mr-2" /> Browse Catalog
                </a>
            </div>
        @endif
    </div>

    <!-- Share Dialog -->
    <div id="share-dialog" class="share-dialog" role="dialog" aria-modal="true" aria-labelledby="share-title">
        <div class="share-dialog-content">
            <h3 id="share-title" class="text-xl font-bold text-[var(--color-text-primary)] mb-4">Share Comparison</h3>
            <p class="text-[var(--color-text-secondary)] mb-4">Copy this link to share the current comparison with colleagues.</p>
            <div class="flex gap-2 mb-4">
                <input type="text" id="share-url" value="{{ $shareUrl }}" readonly class="flex-1 px-4 py-2 border border-[var(--color-border-medium)] rounded-lg text-sm bg-[var(--color-bg-tertiary)]" aria-label="Shareable URL">
                <button onclick="copyShareUrl()" class="btn-primary whitespace-nowrap">
                    <x-ui.icon name="copy" class="h-4 w-4 mr-2" /> Copy
                </button>
            </div>
            <p class="text-xs text-[var(--color-text-muted)] mb-4">The link includes all selected products and will open this exact comparison.</p>
            <button onclick="closeShareDialog()" class="btn-outline w-full">Close</button>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-[var(--z-toast)] flex flex-col gap-2" aria-live="polite"></div>
</div>
@endsection

@section('scripts')
<script>
    function addProductToCompare(productId = null) {
        const select = document.getElementById('add-product-select');
        const id = productId || select.value;
        if (!id) return;

        const url = new URL(window.location.href);
        const currentIds = url.searchParams.get('ids') ? url.searchParams.get('ids').split(',') : [];

        if (!currentIds.includes(id)) {
            if (currentIds.length >= 4) {
                showToast('Maximum 4 products can be compared', 'error');
                return;
            }
            currentIds.push(id);
            url.searchParams.set('ids', currentIds.join(','));
            window.location.href = url.toString();
        }
    }

    function removeFromComparison(productId) {
        const url = new URL(window.location.href);
        const currentIds = url.searchParams.get('ids') ? url.searchParams.get('ids').split(',') : [];
        const filtered = currentIds.filter(id => id !== productId.toString());
        if (filtered.length > 0) {
            url.searchParams.set('ids', filtered.join(','));
        } else {
            url.searchParams.delete('ids');
        }
        window.location.href = url.toString();
    }

    function exportCSV() {
        if (document.getElementById('products-container')?.querySelector('table')) {
            const table = document.querySelector('.compare-table');
            const rows = table.querySelectorAll('tr');
            let csv = [];

            rows.forEach(row => {
                const cols = row.querySelectorAll('th, td');
                const rowData = [];
                cols.forEach(col => {
                    let text = col.innerText.trim().replace(/"/g, '""');
                    rowData.push(`"${text}"`);
                });
                csv.push(rowData.join(','));
            });

            const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `aczet_comparison_${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
            showToast('CSV exported successfully!', 'success');
        }
    }

    async function exportPDF() {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<x-ui.icon name="loader" class="h-4 w-4 animate-spin mr-2" />Generating...';
        btn.disabled = true;

        try {
            const response = await fetch('{{ route('compare.pdf') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ ids: {{ json_encode($productIds) }} })
            });

            if (response.ok) {
                const blob = await response.blob();
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `aczet_comparison_${new Date().toISOString().split('T')[0]}.pdf`;
                link.click();
                showToast('PDF generated successfully!', 'success');
            } else {
                throw new Error('PDF generation failed');
            }
        } catch (e) {
            console.error(e);
            showToast('Failed to generate PDF', 'error');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    function openShareDialog() {
        document.getElementById('share-dialog').classList.add('open');
        document.getElementById('share-url').select();
    }

    function closeShareDialog() {
        document.getElementById('share-dialog').classList.remove('open');
    }

    function copyShareUrl() {
        const input = document.getElementById('share-url');
        input.select();
        document.execCommand('copy');
        showToast('Link copied to clipboard!', 'success');
    }

    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <x-ui.icon name="${type === 'success' ? 'circle-check' : 'circle-x'}" class="h-5 w-5" />
            <span>${message}</span>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
</script>