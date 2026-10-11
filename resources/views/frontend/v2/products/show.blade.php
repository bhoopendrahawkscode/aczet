@extends('layouts.app')

@php
    $currentLocale = $currentLocale ?? app()->getLocale();
    $currentRegion = $currentRegion ?? session('aczet_region', 'India');
    $categoryName = $product->category ? $product->category->getLocalizedName($currentLocale) : 'Precision Instruments';
    $productName = $product->getLocalizedName($currentLocale);
    $shortDesc = $product->getLocalizedShortDescription($currentLocale);
    $fullDesc = $product->getLocalizedFullDescription($currentLocale);
    $specs = $product->specifications->groupBy('group_name');
    $faqs = $product->faqs;
    $galleryImages = collect($product->gallery ?? [])->filter()->values();
    if ($product->main_image) {
        $galleryImages->prepend($product->main_image);
    }
    $downloads = collect([
        ['label' => 'Datasheet', 'icon' => 'file-text', 'url' => $product->pdf_datasheet, 'type' => 'PDF'],
        ['label' => 'User Manual', 'icon' => 'file-text', 'url' => $product->pdf_manual, 'type' => 'PDF'],
        ['label' => 'Brochure', 'icon' => 'file-text', 'url' => $product->pdf_brochure, 'type' => 'PDF'],
    ])->filter(fn($d) => $d['url']);
@endphp

@section('title', $productName . ' (' . $product->model_number . ') | Aczet Metrology')
@section('meta_description', Str::limit($shortDesc ?: 'Discover specifications, certifications, and pricing for Aczet ' . $productName . ' (' . $product->model_number . '). ISO 17025 calibrated with global engineering support.', 160))

@section('styles')
<style>
    /* Product Detail v2 Specific Styles */
    .product-page-wrapper {
        background: var(--color-bg-primary);
        padding-bottom: 80px;
    }

    .spec-table th {
        @apply px-4 py-3 text-left text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider bg-[var(--color-bg-tertiary)] sticky top-0;
    }
    .spec-table td {
        @apply px-4 py-3 text-sm text-[var(--color-text-primary)] border-t border-[var(--color-border-light)];
    }
    .spec-table tr:hover td {
        @apply bg-[var(--color-bg-tertiary)];
    }
    .spec-group-header {
        @apply px-4 py-3 text-sm font-semibold text-[var(--color-text-primary)] bg-white border-b border-[var(--color-border-light)];
    }
    .download-card {
        @apply flex items-center gap-3 p-4 bg-white border border-[var(--color-border-light)] rounded-xl hover:border-orange-300 hover:shadow-md transition-all duration-200;
    }
    .download-card:hover {
        @apply bg-[var(--color-bg-tertiary)];
    }
    .compliance-card {
        @apply p-5 bg-white border border-[var(--color-border-light)] rounded-xl hover:border-orange-200 hover:shadow-lg transition-all duration-200 h-full;
    }
    .sticky-cta-bar {
        @apply fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-sm border-t border-[var(--color-border-light)] shadow-xl z-[var(--z-sticky)] p-4 md:p-6;
    }
    @media (min-width: 1024px) {
        .sticky-cta-bar {
            display: none;
        }
    }
    .product-gallery-thumb {
        @apply w-20 h-20 rounded-lg border-2 border-transparent cursor-pointer transition-all duration-200 overflow-hidden;
    }
    .product-gallery-thumb.active {
        @apply border-orange-500;
    }
    .product-gallery-thumb img {
        @apply w-full h-full object-cover;
    }
    .tabs-nav {
        @apply border-b border-[var(--color-border-light)];
    }
    .tab-btn {
        @apply px-4 py-3 text-sm font-semibold text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] border-b-2 border-transparent transition-all duration-200 whitespace-nowrap;
    }
    .tab-btn.active {
        @apply text-orange-600 border-orange-600;
    }
    .faq-item {
        @apply border border-[var(--color-border-light)] rounded-xl overflow-hidden;
    }
    .faq-question {
        @apply w-full px-6 py-4 text-left font-semibold text-[var(--color-text-primary)] bg-white hover:bg-[var(--color-bg-primary)] transition-colors flex items-center justify-between gap-4 focus-ring;
    }
    .faq-answer {
        @apply px-6 pb-6 text-[var(--color-text-secondary)] leading-relaxed;
    }
</style>
@endsection

@section('content')
<div class="product-page-wrapper">
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-[var(--color-border-light)]">
        <div class="container-responsive py-4">
            <x-ui.breadcrumb :items="[
                ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
                ['label' => 'Products', 'url' => route('products.index')],
                ['label' => $categoryName, 'url' => route('products.index', ['category' => $product->category->slug ?? ''])],
                ['label' => $productName, 'url' => null],
            ]" />
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-responsive py-8">
        <div class="grid lg:grid-cols-12 gap-8">
            <!-- Left Column: Gallery & Info -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Product Header -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <x-ui.badge variant="primary" size="sm">{{ $categoryName }}</x-ui.badge>
                        <span class="px-3 py-1 bg-[var(--color-text-primary)] text-white text-xs font-bold uppercase tracking-wider rounded-full">{{ $product->model_number }}</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-[var(--color-text-primary)] leading-tight mb-3">{{ $productName }}</h1>
                    <p class="text-lg text-[var(--color-text-secondary)] leading-relaxed max-w-2xl">{{ $shortDesc }}</p>
                </div>

                <!-- Image Gallery -->
                <div class="bg-white rounded-2xl border border-[var(--color-border-light)] overflow-hidden">
                    <div class="aspect-[4/3] relative bg-[var(--color-bg-tertiary)]">
                        @if($galleryImages->count() > 0)
                            <img
                                id="mainProductImage"
                                src="{{ $galleryImages->first() }}"
                                alt="{{ $productName }}"
                                class="w-full h-full object-contain p-8"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[var(--color-text-muted)]">
                                <x-ui.icon name="image" class="h-12 w-12" />
                            </div>
                        @endif
                    </div>

                    @if($galleryImages->count() > 1)
                        <div class="p-4 border-t border-[var(--color-border-light)]">
                            <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2" role="list" aria-label="Product images">
                                @foreach($galleryImages as $index => $image)
                                    <button
                                        type="button"
                                        class="product-gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                                        onclick="document.getElementById('mainProductImage').src='{{ $image }}'; document.querySelectorAll('.product-gallery-thumb').forEach(el => el.classList.remove('active')); this.classList.add('active');"
                                        aria-label="View image {{ $index + 1 }}"
                                        role="listitem"
                                    >
                                        <img src="{{ $image }}" alt="{{ $productName }} - Image {{ $index + 1 }}" loading="lazy">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Tabs -->
                <div class="bg-white rounded-2xl border border-[var(--color-border-light)] overflow-hidden">
                    <nav class="tabs-nav overflow-x-auto scrollbar-hide" role="tablist" aria-label="Product details">
                        <div class="flex min-w-max">
                            <button
                                role="tab"
                                aria-selected="true"
                                class="tab-btn active"
                                onclick="switchTab('overview')"
                                id="tab-overview"
                            >
                                <x-ui.icon name="file-text" class="h-4 w-4 mr-2" /> Overview
                            </button>
                            <button
                                role="tab"
                                aria-selected="false"
                                class="tab-btn"
                                onclick="switchTab('specs')"
                                id="tab-specs"
                            >
                                <x-ui.icon name="list" class="h-4 w-4 mr-2" /> Specifications
                            </button>
                            <button
                                role="tab"
                                aria-selected="false"
                                class="tab-btn"
                                onclick="switchTab('compliance')"
                                id="tab-compliance"
                            >
                                <x-ui.icon name="shield" class="h-4 w-4 mr-2" /> Compliance
                            </button>
                            <button
                                role="tab"
                                aria-selected="false"
                                class="tab-btn"
                                onclick="switchTab('downloads')"
                                id="tab-downloads"
                            >
                                <x-ui.icon name="download" class="h-4 w-4 mr-2" /> Downloads
                            </button>
                            @if($faqs->count() > 0)
                                <button
                                    role="tab"
                                    aria-selected="false"
                                    class="tab-btn"
                                    onclick="switchTab('faq')"
                                    id="tab-faq"
                                >
                                    <x-ui.icon name="help-circle" class="h-4 w-4 mr-2" /> FAQ
                                    <x-ui.badge variant="primary" size="xs">{{ $faqs->count() }}</x-ui.badge>
                                </button>
                            @endif
                        </div>
                    </nav>

                    <!-- Tab Panels -->
                    <div class="p-6 md:p-8">
                        <!-- Overview Tab -->
                        <div id="panel-overview" role="tabpanel" aria-labelledby="tab-overview">
                            <div class="prose prose-slate max-w-none">
                                <p class="text-[var(--color-text-secondary)] leading-relaxed mb-6">{{ $fullDesc }}</p>

                                @if($product->price)
                                    <div class="flex items-baseline gap-3 mb-6">
                                        <span class="text-3xl font-extrabold text-orange-600">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-[var(--color-text-muted)]">USD</span>
                                    </div>
                                @endif

                                @if($specs->count() > 0)
                                    <h3 class="text-lg font-bold text-[var(--color-text-primary)] mb-4">Key Specifications</h3>
                                    <dl class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                                        @foreach($specs->take(6) as $group => $groupSpecs)
                                            @foreach($groupSpecs->take(2) as $spec)
                                                <div class="bg-[var(--color-bg-tertiary)] rounded-xl p-4">
                                                    <dt class="text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1">{{ $spec->spec_key }}</dt>
                                                    <dd class="text-base font-medium text-[var(--color-text-primary)]">{{ $spec->spec_value }}</dd>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </dl>
                                @endif

                                <div class="flex flex-wrap gap-3">
                                    <x-ui.button variant="primary" size="lg" onclick="openQuoteModal('Product Detail - {{ $productName }}', {{ $product->id }}, '{{ addslashes($productName) }}')">
                                        <x-ui.icon name="send" class="h-5 w-5" /> Get Quote
                                    </x-ui.button>
                                    <x-ui.button variant="outline" size="lg" onclick="Alpine.store('compare').add({{ json_encode(['id' => $product->id, 'name' => $productName, 'model' => $product->model_number, 'image' => $product->main_image]) }})">
                                        <x-ui.icon name="code-compare" class="h-5 w-5" /> Compare
                                    </x-ui.button>
                                    <a href="https://wa.me/912228474800?text=Inquiry%20about%20{{ urlencode($productName) }}%20({{ $product->model_number }})" target="_blank" rel="noopener" class="btn-whatsapp-inquiry inline-flex items-center gap-2 px-6 py-3 text-base font-semibold text-white rounded-xl" style="background: #25D366;">
                                        <x-ui.icon name="whatsapp" class="h-5 w-5" /> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Specifications Tab -->
                        <div id="panel-specs" role="tabpanel" aria-labelledby="tab-specs" hidden>
                            <div class="mb-4">
                                <x-ui.input
                                    type="search"
                                    placeholder="Search specifications..."
                                    class="max-w-xs"
                                    wire:model.live="specSearch"
                                />
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full spec-table">
                                    @foreach($specs as $groupName => $groupSpecs)
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="spec-group-header">{{ $groupName }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupSpecs as $spec)
                                                <tr>
                                                    <td class="font-medium text-[var(--color-text-secondary)] w-1/3">{{ $spec->spec_key }}</td>
                                                    <td>{{ $spec->spec_value }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <!-- Compliance Tab -->
                        <div id="panel-compliance" role="tabpanel" aria-labelledby="tab-compliance" hidden>
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="compliance-card">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                            <x-ui.icon name="check-circle" class="h-6 w-6" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[var(--color-text-primary)] mb-1">ISO 9001 Certified</h4>
                                            <p class="text-sm text-[var(--color-text-secondary)]">Quality management system certified for consistent product quality.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="compliance-card">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                            <x-ui.icon name="award" class="h-6 w-6" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[var(--color-text-primary)] mb-1">ISO 17025 Calibration</h4>
                                            <p class="text-sm text-[var(--color-text-secondary)]">Accredited calibration lab ensuring measurement traceability.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="compliance-card">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                                            <x-ui.icon name="book-open" class="h-6 w-6" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[var(--color-text-primary)] mb-1">USP Chapter 41</h4>
                                            <p class="text-sm text-[var(--color-text-secondary)]">Compliant with USP 41 for analytical balance qualification.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="compliance-card">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center flex-shrink-0">
                                            <x-ui.icon name="globe" class="h-6 w-6" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[var(--color-text-primary)] mb-1">CE Marked</h4>
                                            <p class="text-sm text-[var(--color-text-secondary)]">Conforms to European health, safety, and environmental standards.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="compliance-card">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                                            <x-ui.icon name="lock" class="h-6 w-6" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[var(--color-text-primary)] mb-1">FDA 21 CFR Part 11</h4>
                                            <p class="text-sm text-[var(--color-text-secondary)]">Audit trail and electronic records compliance for pharma.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="compliance-card">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                                            <x-ui.icon name="globe" class="h-6 w-6" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[var(--color-text-primary)] mb-1">RoHS Compliant</h4>
                                            <p class="text-sm text-[var(--color-text-secondary)]">Restriction of hazardous substances in electrical equipment.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Downloads Tab -->
                        <div id="panel-downloads" role="tabpanel" aria-labelledby="tab-downloads" hidden>
                            <div class="space-y-3">
                                @foreach($downloads as $download)
                                    <a href="{{ $download['url'] }}" target="_blank" rel="noopener" class="download-card">
                                        <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                                            <x-ui.icon :name="{{ $download['icon'] }}" class="h-5 w-5" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-[var(--color-text-primary)]">{{ $download['label'] }}</p>
                                            <p class="text-sm text-[var(--color-text-muted)]">{{ $download['type'] }} document</p>
                                        </div>
                                        <x-ui.icon name="arrow-up-right" class="h-5 w-5 text-[var(--color-text-muted)]" />
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- FAQ Tab -->
                        <div id="panel-faq" role="tabpanel" aria-labelledby="tab-faq" hidden>
                            <x-ui.accordion :items="[
                                @foreach($faqs as $faq)
                                    ['title' => '{{ $faq->question }}', 'content' => '{{ $faq->answer }}', 'open' => false],
                                @endforeach
                            ]" variant="separated" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sticky CTA & Quick Specs -->
            <div class="lg:col-span-5">
                <div class="sticky top-24 space-y-6">
                    <!-- Quick Specs Card -->
                    <x-ui.card variant="feature" class="p-6">
                        <h3 class="font-bold text-[var(--color-text-primary)] mb-4">Quick Specifications</h3>
                        <dl class="space-y-4">
                            @foreach($specs->take(4) as $group => $groupSpecs)
                                @foreach($groupSpecs->take(1) as $spec)
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                                            <x-ui.icon name="list" class="h-5 w-5" />
                                        </div>
                                        <div>
                                            <dt class="text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider">{{ $spec->spec_key }}</dt>
                                            <dd class="text-sm font-medium text-[var(--color-text-primary)]">{{ $spec->spec_value }}</dd>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </dl>
                    </x-ui.card>

                    <!-- CTA Card -->
                    <x-ui.card variant="feature" class="p-6">
                        <h3 class="font-bold text-[var(--color-text-primary)] mb-4">Need Help Choosing?</h3>
                        <p class="text-[var(--color-text-secondary)] mb-4">Our metrology experts can help you select the right instrument for your application.</p>
                        <div class="space-y-3">
                            <x-ui.button variant="primary" size="lg" class="w-full" onclick="openQuoteModal('Expert Consultation - {{ $productName }}', {{ $product->id }}, '{{ addslashes($productName) }}')">
                                <x-ui.icon name="send" class="h-5 w-5" /> Request Expert Quote
                            </x-ui.button>
                            <x-ui.button variant="secondary" size="lg" class="w-full" onclick="Alpine.store('compare').add({{ json_encode(['id' => $product->id, 'name' => $productName, 'model' => $product->model_number, 'image' => $product->main_image]) }})">
                                <x-ui.icon name="code-compare" class="h-5 w-5" /> Add to Comparison
                            </x-ui.button>
                            <a href="https://wa.me/912228474800?text=Inquiry%20about%20{{ urlencode($productName) }}%20({{ $product->model_number }})" target="_blank" rel="noopener" class="btn-whatsapp-inquiry w-full block text-center">
                                <x-ui.icon name="whatsapp" class="h-5 w-5 inline mr-2" /> WhatsApp Inquiry
                            </a>
                        </div>
                    </x-ui.card>

                    <!-- Region Availability -->
                    <x-ui.card variant="feature" class="p-6">
                        <h3 class="font-bold text-[var(--color-text-primary)] mb-3">Regional Availability</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'] as $region)
                                @php
                                    $visible = $product->isVisibleInRegion($region);
                                @endphp
                                <x-ui.badge
                                    variant="{{ $visible ? 'success' : 'outline' }}"
                                    size="sm"
                                    class="{{ $visible ? '' : 'opacity-50' }}"
                                >
                                    {{ $region }}
                                </x-ui.badge>
                            @endforeach
                        </div>
                    </x-ui.card>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sticky CTA Bar -->
    <div class="sticky-cta-bar lg:hidden">
        <div class="flex flex-col sm:flex-row gap-3">
            <x-ui.button variant="primary" size="lg" class="flex-1" onclick="openQuoteModal('Mobile CTA - {{ $productName }}', {{ $product->id }}, '{{ addslashes($productName) }}')">
                <x-ui.icon name="send" class="h-5 w-5" /> Get Quote
            </x-ui.button>
            <x-ui.button variant="outline" size="lg" class="flex-1" onclick="Alpine.store('compare').add({{ json_encode(['id' => $product->id, 'name' => $productName, 'model' => $product->model_number, 'image' => $product->main_image]) }})">
                <x-ui.icon name="code-compare" class="h-5 w-5" /> Compare
            </x-ui.button>
            <a href="https://wa.me/912228474800?text=Inquiry%20about%20{{ urlencode($productName) }}%20({{ $product->model_number }})" target="_blank" rel="noopener" class="btn-whatsapp-inquiry flex-1 flex items-center justify-center gap-2">
                <x-ui.icon name="whatsapp" class="h-5 w-5" /> WhatsApp
            </a>
        </div>
    </div>
</div>

<!-- Schema.org Product Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $productName }}",
    "model": "{{ $product->model_number }}",
    "description": "{{ Str::limit($shortDesc, 500) }}",
    "brand": {
        "@type": "Brand",
        "name": "Aczet"
    },
    "category": "{{ $categoryName }}",
    @if($product->price)
        "offers": {
            "@type": "Offer",
            "price": "{{ $product->price }}",
            "priceCurrency": "USD",
            "availability": "https://schema.org/InStock"
        },
    @endif
    "image": "{{ $product->main_image ? asset($product->main_image) : asset('images/logo.png') }}",
    "sku": "{{ $product->model_number }}",
    "mpn": "{{ $product->model_number }}"
}
</script>

@if($faqs->count() > 0)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach($faqs as $index => $faq)
            {
                "@type": "Question",
                "name": "{{ $faq->question }}",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "{{ $faq->answer }}"
                }
            }@if($index < $faqs->count() - 1),@endif
        @endforeach
    ]
}
</script>
@endif

@section('scripts')
<script>
    function switchTab(tabName) {
        document.querySelectorAll('[role="tabpanel"]').forEach(panel => panel.hidden = true);
        document.querySelectorAll('[role="tab"]').forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
        });
        document.getElementById('panel-' + tabName).hidden = false;
        document.getElementById('tab-' + tabName).classList.add('active');
        document.getElementById('tab-' + tabName).setAttribute('aria-selected', 'true');
    }

    function openQuoteModal(source, productId, productName) {
        if (window.Alpine && Alpine.store('modal')) {
            Alpine.store('modal').open('quote', { source, productId, productName });
        }
    }
</script>
@endsection