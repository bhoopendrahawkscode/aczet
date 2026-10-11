@extends('layouts.app')

@php
    $currentLocale = $currentLocale ?? app()->getLocale();
    $currentRegion = $currentRegion ?? session('aczet_region', 'India');
    $categories = $categories ?? \App\Models\Category::withCount('products')->get();
    $featuredProducts = $featuredProducts ?? \App\Models\Product::with(['category', 'regionVisibilities'])
        ->where('is_active', true)
        ->where('is_featured', true)
        ->whereHas('regionVisibilities', function($q) use ($currentRegion) {
            $q->where('region', $currentRegion)->where('is_visible', true);
        })
        ->take(8)
        ->get();
    $upcomingExhibitions = $upcomingExhibitions ?? \App\Models\Exhibition::where('is_upcoming', true)->orderBy('event_date', 'asc')->take(3)->get();
    $latestBlogs = $latestBlogs ?? \App\Models\Blog::orderBy('published_at', 'desc')->take(3)->get();
    $banners = $banners ?? \App\Models\Banner::where('is_active', true)->orderBy('sort_order')->orderByDesc('created_at')->get();
@endphp

@section('title', 'Aczet | Flagship Precision Weighing & Laboratory Metrology Instruments')
@section('meta_description', 'Discover Aczet analytical balances, moisture analyzers, micro balances, and industrial weighing systems backed by ISO 17025 calibration, USP 41 compliance, and global engineering support.')

@section('styles')
<style>
    /* Homepage v2 Styles */
    .home-page {
        background: var(--color-bg-primary);
    }
    .hero-section {
        position: relative;
        overflow: hidden;
    }
    .hero-carousel {
        position: relative;
    }
    .hero-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.6s ease, visibility 0.6s ease;
    }
    .hero-slide.active {
        opacity: 1;
        visibility: visible;
        position: relative;
    }
    .hero-content {
        @apply container-responsive relative z-10 py-20 md:py-32;
    }
    .hero-badge {
        @apply inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold mb-6;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(2, 132, 199,0.4);
        color: #FF9B60;
    }
    .hero-title {
        @apply font-display text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight mb-6;
    }
    .hero-title .highlight {
        background: linear-gradient(135deg, #FF9B60 0%, #0284C7 50%, #FFA834 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero-text {
        @apply text-lg md:text-xl text-slate-300 max-w-2xl mb-8 leading-relaxed;
    }
    .hero-cta-group {
        @apply flex flex-wrap gap-4 mb-10;
    }
    .hero-stats {
        @apply flex flex-wrap gap-8 pt-8 border-t border-white/10;
    }
    .hero-stat {
        @apply flex items-center gap-3;
    }
    .hero-stat-value {
        @apply font-display text-2xl md:text-3xl font-extrabold text-white;
    }
    .hero-stat-label {
        @apply text-sm text-slate-400;
    }

    /* Carousel Controls */
    .carousel-nav {
        @apply absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-3 z-20;
    }
    .carousel-btn {
        @apply w-12 h-12 rounded-full bg-white/10 border border-white/20 text-white flex items-center justify-center hover:bg-white/20 transition-all duration-200 focus-ring;
    }
    .carousel-dots {
        @apply flex gap-2;
    }
    .carousel-dot {
        @apply w-2.5 h-2.5 rounded-full transition-all duration-200;
        background: rgba(255,255,255,0.4);
    }
    .carousel-dot.active {
        @apply w-8 bg-orange-500;
        background: #0284C7;
    }

    /* Stats Section */
    .stats-section {
        @apply py-16 md:py-24 bg-white border-y border-[var(--color-border-light)];
    }
    .stat-card {
        @apply text-center p-6;
    }
    .stat-icon {
        @apply w-14 h-14 rounded-xl mx-auto mb-4 flex items-center justify-center;
    }
    .stat-value {
        @apply font-display text-4xl md:text-5xl font-extrabold text-[var(--color-text-primary)] mb-1;
    }
    .stat-label {
        @apply text-[var(--color-text-secondary)] font-medium;
    }

    /* Categories Section */
    .categories-section {
        @apply py-16 md:py-24 bg-[var(--color-bg-primary)];
    }
    .category-card {
        @apply bg-white border border-[var(--color-border-light)] rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-[var(--color-border-medium)] hover:-translate-y-1;
    }
    .category-image {
        @apply aspect-square bg-[var(--color-bg-tertiary)] p-6 overflow-hidden;
    }
    .category-image img {
        @apply w-full h-full object-contain transition-transform duration-300;
    }
    .category-card:hover .category-image img {
        @apply scale-105;
    }
    .category-content {
        @apply p-6;
    }
    .category-title {
        @apply font-display text-xl font-bold text-[var(--color-text-primary)] mb-2;
    }
    .category-desc {
        @apply text-[var(--color-text-secondary)] text-sm mb-4 line-clamp-2;
    }
    .category-count {
        @apply text-xs font-semibold text-orange-600 uppercase tracking-wider;
    }

    /* Featured Products */
    .featured-section {
        @apply py-16 md:py-24 bg-white border-y border-[var(--color-border-light)];
    }
    .section-header {
        @apply flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8;
    }
    .section-title {
        @apply font-display text-3xl md:text-4xl font-extrabold text-[var(--color-text-primary)];
    }
    .section-subtitle {
        @apply text-[var(--color-text-secondary)] max-w-2xl;
    }

    /* Product Finder Wizard */
    .wizard-section {
        @apply py-16 md:py-24 bg-[var(--color-bg-primary)];
    }
    .wizard-card {
        @apply bg-white border border-[var(--color-border-light)] rounded-2xl p-6 md:p-8 max-w-4xl mx-auto;
    }
    .wizard-steps {
        @apply flex items-center justify-between mb-8 relative;
    }
    .wizard-steps::before {
        content: '';
        @apply absolute top-1/2 left-0 right-0 h-0.5 -translate-y-1/2;
        background: var(--color-border-light);
        z-index: 0;
    }
    .wizard-step {
        @apply relative z-10 flex flex-col items-center gap-2;
    }
    .wizard-step-circle {
        @apply w-12 h-12 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300;
        background: var(--color-border-light);
        color: var(--color-text-muted);
    }
    .wizard-step.active .wizard-step-circle {
        @apply bg-orange-500 text-white;
    }
    .wizard-step.completed .wizard-step-circle {
        @apply bg-emerald-500 text-white;
    }
    .wizard-step-label {
        @apply text-xs font-medium text-[var(--color-text-secondary)] text-center max-w-[80px];
    }
    .wizard-step.active .wizard-step-label {
        @apply text-[var(--color-text-primary)];
    }
    .wizard-form {
        @apply space-y-6;
    }
    .wizard-panel {
        @apply hidden;
    }
    .wizard-panel.active {
        @apply block animate-fade-in;
    }
    .wizard-actions {
        @apply flex justify-end gap-3 mt-8 pt-6 border-t border-[var(--color-border-light)];
    }

    /* Exhibitions & Blogs */
    .content-grid {
        @apply grid md:grid-cols-2 lg:grid-cols-3 gap-6;
    }
    .content-card {
        @apply bg-white border border-[var(--color-border-light)] rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-[var(--color-border-medium)] hover:-translate-y-1;
    }
    .content-image {
        @apply aspect-video bg-[var(--color-border-light)] overflow-hidden;
    }
    .content-image img {
        @apply w-full h-full object-cover transition-transform duration-300;
    }
    .content-card:hover .content-image img {
        @apply scale-105;
    }
    .content-body {
        @apply p-5;
    }
    .content-meta {
        @apply flex items-center gap-2 text-xs text-[var(--color-text-muted)] mb-2;
    }
    .content-title {
        @apply font-bold text-[var(--color-text-primary)] mb-2 line-clamp-1;
    }
    .content-excerpt {
        @apply text-[var(--color-text-secondary)] text-sm line-clamp-2 mb-4;
    }

    /* CTA Section */
    .cta-section {
        @apply py-16 md:py-24 bg-[var(--color-void)] border-t border-white/10;
    }
    .cta-card {
        @apply max-w-3xl mx-auto text-center;
    }
    .cta-title {
        @apply font-display text-3xl md:text-4xl font-extrabold text-white mb-4;
    }
    .cta-text {
        @apply text-slate-300 text-lg mb-8 max-w-xl mx-auto;
    }
    .cta-buttons {
        @apply flex flex-col sm:flex-row gap-4 justify-center;
    }

    /* Skeleton Loaders */
    .skeleton {
        @apply animate-pulse bg-[var(--color-bg-tertiary)];
    }
    .skeleton-title {
        @apply h-6 w-3/4 skeleton rounded mb-2;
    }
    .skeleton-text {
        @apply h-4 w-full skeleton rounded mb-2;
    }
    .skeleton-text.short {
        @apply w-3/4;
    }
    .skeleton-image {
        @apply aspect-video skeleton rounded-t-2xl;
    }
    .skeleton-card {
        @apply bg-white border border-[var(--color-border-light)] rounded-2xl overflow-hidden skeleton;
    }
</style>
@endsection

@section('content')
<div class="home-page">
    <!-- Hero Carousel -->
    <section class="hero-section" aria-label="Hero carousel">
        <div class="hero-carousel" id="hero-carousel" role="region" aria-label="Featured products">
            @foreach($banners as $index => $banner)
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" role="group" aria-roledescription="slide" aria-label="{{ $banner->title ?? 'Slide ' . ($index + 1) }}">
                    @if($banner->image)
                        <img src="{{ $banner->image }}" alt="" class="absolute inset-0 w-full h-full object-cover" aria-hidden="true">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-[var(--color-void)] via-[var(--color-navy)] to-[var(--color-slate)]" aria-hidden="true"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-r from-[var(--color-void)]/95 via-[var(--color-void)]/80 to-transparent" aria-hidden="true"></div>

                    <div class="hero-content">
                        <div class="max-w-3xl">
                            <div class="hero-badge">
                                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                                <span>{{ $banner->eyebrow ?? 'New Arrival' }}</span>
                            </div>
                            <h1 class="hero-title">
                                {{ $banner->title }}
                                @if($banner->description)
                                    <br><span class="text-2xl md:text-3xl font-semibold text-orange-400 highlight">{{ $banner->description }}</span>
                                @endif
                            </h1>
                            @if($banner->description && !$banner->description)
                                <p class="hero-text">{{ $banner->description }}</p>
                            @endif
                            <div class="hero-cta-group">
                                @if($banner->cta_url && $banner->cta_label)
                                    <a href="{{ $banner->cta_url }}" class="btn-super-primary">
                                        {{ $banner->cta_label }}
                                        <x-ui.icon name="arrow-right" class="h-5 w-5" />
                                    </a>
                                @endif
                                <a href="{{ route('products.index') }}" class="btn-glass-secondary">
                                    <x-ui.icon name="grid" class="h-5 w-5" />
                                    Explore Catalog
                                </a>
                            </div>

                            <div class="hero-stats">
                                <div class="hero-stat">
                                    <span class="hero-stat-value">30+</span>
                                    <span class="hero-stat-label">Years Excellence</span>
                                </div>
                                <div class="hero-stat">
                                    <span class="hero-stat-value">85+</span>
                                    <span class="hero-stat-label">Export Countries</span>
                                </div>
                                <div class="hero-stat">
                                    <span class="hero-stat-value">500+</span>
                                    <span class="hero-stat-label">Models</span>
                                </div>
                                <div class="hero-stat">
                                    <span class="hero-stat-value">ISO 9001</span>
                                    <span class="hero-stat-label">Certified</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Carousel Navigation -->
        <nav class="carousel-nav" aria-label="Carousel navigation">
            <button class="carousel-btn" onclick="prevSlide()" aria-label="Previous slide">
                <x-ui.icon name="chevron-left" class="h-6 w-6" />
            </button>
            <div class="carousel-dots" id="carousel-dots" role="tablist" aria-label="Select slide">
                @foreach($banners as $index => $banner)
                    <button
                        class="carousel-dot {{ $index === 0 ? 'active' : '' }}"
                        onclick="goToSlide({{ $index }})"
                        role="tab"
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-label="Go to slide {{ $index + 1 }}"
                    ></button>
                @endforeach
            </div>
            <button class="carousel-btn" onclick="nextSlide()" aria-label="Next slide">
                <x-ui.icon name="chevron-right" class="h-6 w-6" />
            </button>
        </nav>
    </section>

    <!-- Stats Bar -->
    <section class="stats-section" aria-label="Key statistics">
        <div class="container-responsive">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="stat-card">
                    <div class="stat-icon bg-orange-100 text-orange-600">
                        <x-ui.icon name="award" class="h-8 w-8" />
                    </div>
                    <div class="stat-value">30+</div>
                    <div class="stat-label">Years of Metrology Excellence</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-blue-100 text-blue-600">
                        <x-ui.icon name="globe" class="h-8 w-8" />
                    </div>
                    <div class="stat-value">85+</div>
                    <div class="stat-label">Countries Served Worldwide</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-emerald-100 text-emerald-600">
                        <x-ui.icon name="cog" class="h-8 w-8" />
                    </div>
                    <div class="stat-value">500+</div>
                    <div class="stat-label">Precision Instruments</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-cyan-100 text-cyan-600">
                        <x-ui.icon name="shield" class="h-8 w-8" />
                    </div>
                    <div class="stat-value">ISO 9001</div>
                    <div class="stat-label">Quality Certified</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories-section" aria-label="Product categories">
        <div class="container-responsive">
            <div class="section-header">
                <h2 class="section-title">Explore by Category</h2>
                <p class="section-subtitle">Find the perfect precision instrument for your application</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($categories as $category)
                    <article class="category-card" onclick="window.location.href='{{ route('products.index', ['category' => $category->slug]) }}'" style="cursor: pointer;">
                        <div class="category-image">
                            @if($category->image)
                                <img src="{{ $category->image }}" alt="{{ $category->getLocalizedName($currentLocale) }}" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-ui.icon name="cog" class="h-16 w-16 text-[var(--color-border-medium)]" />
                                </div>
                            @endif
                        </div>
                        <div class="category-content">
                            <h3 class="category-title">{{ $category->getLocalizedName($currentLocale) }}</h3>
                            <p class="category-desc">{{ Str::limit($category->description ?? 'Precision instruments for professional applications', 100) }}</p>
                            <span class="category-count">{{ $category->products_count ?? 0 }} products</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-section" aria-label="Featured products">
        <div class="container-responsive">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Featured Products</h2>
                    <p class="section-subtitle">Our most popular precision instruments, hand-picked for you</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn-outline self-start">
                    <x-ui.icon name="arrow-right" class="h-4 w-4 mr-2" /> View All Products
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="featured-products">
                @foreach($featuredProducts as $product)
                    @php
                        $productName = $product->getLocalizedName($currentLocale);
                        $shortDesc = $product->getLocalizedShortDescription($currentLocale);
                    @endphp
                    <article class="bg-white border border-[var(--color-border-light)] rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-[var(--color-border-medium)] hover:-translate-y-1">
                        <div class="aspect-[4/3] bg-[var(--color-bg-tertiary)] relative overflow-hidden">
                            @if($product->main_image)
                                <img src="{{ $product->main_image }}" alt="{{ $productName }}" class="w-full h-full object-contain p-4 transition-transform duration-300 hover:scale-105" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[var(--color-text-muted)]">
                                    <x-ui.icon name="image" class="h-12 w-12" />
                                </div>
                            @endif>
                            <div class="absolute top-3 left-3 right-3 flex justify-between">
                                <span class="px-2 py-1 bg-[var(--color-text-primary)] text-white text-xs font-bold uppercase tracking-wider rounded-full">{{ $product->model_number }}</span>
                                <span class="px-2 py-1 bg-white text-orange-600 border border-orange-200 text-xs font-bold uppercase tracking-wider rounded-full">{{ $product->category?->getLocalizedName($currentLocale) ?? 'Instrument' }}</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-[var(--color-text-primary)] mb-1 line-clamp-1"><a href="{{ route('products.show', $product->slug) }}" class="hover:text-orange-600 transition-colors">{{ $product->getLocalizedName($currentLocale) }}</a></h3>
                            <p class="text-sm text-[var(--color-text-secondary)] line-clamp-2 mb-3">{{ $shortDesc }}</p>
                            <div class="flex items-center justify-between">
                                @if($product->price)
                                    <span class="text-xl font-extrabold text-orange-600">${{ number_format($product->price, 2) }}</span>
                                @endif
                                <a href="{{ route('products.show', $product->slug) }}" class="px-3 py-1.5 bg-[var(--color-text-primary)] text-white text-sm font-semibold rounded-lg hover:bg-[var(--color-navy)] transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Product Finder Wizard -->
    <section class="wizard-section" aria-label="Product finder wizard">
        <div class="container-responsive">
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl md:text-4xl font-extrabold text-[var(--color-text-primary)] mb-4">Find Your Perfect Instrument</h2>
                <p class="text-[var(--color-text-secondary)] max-w-2xl mx-auto">Answer a few questions and we'll recommend the best precision instrument for your needs.</p>
            </div>

            <div class="wizard-card" x-data="productFinder()">
                <!-- Steps Indicator -->
                <div class="wizard-steps">
                    <div class="wizard-step" :class="{ active: currentStep === 0, completed: currentStep > 0 }">
                        <div class="wizard-step-circle" x-text="currentStep > 0 ? '✓' : '1'"></div>
                        <span class="wizard-step-label">Industry</span>
                    </div>
                    <div class="wizard-step" :class="{ active: currentStep === 1, completed: currentStep > 1 }">
                        <div class="wizard-step-circle" x-text="currentStep > 1 ? '✓' : '2'"></div>
                        <span class="wizard-step-label">Application</span>
                    </div>
                    <div class="wizard-step" :class="{ active: currentStep === 2, completed: currentStep > 2 }">
                        <div class="wizard-step-circle" x-text="currentStep > 2 ? '✓' : '3'"></div>
                        <span class="wizard-step-label">Capacity</span>
                    </div>
                    <div class="wizard-step" :class="{ active: currentStep === 3 }">
                        <div class="wizard-step-circle" x-text="4"></div>
                        <span class="wizard-step-label">Results</span>
                    </div>
                </div>

                <!-- Wizard Panels -->
                <div class="wizard-form">
                    <!-- Step 1: Industry -->
                    <div class="wizard-panel" x-show="currentStep === 0" x-transition>
                        <h3 class="text-xl font-bold text-[var(--color-text-primary)] mb-2">What industry are you in?</h3>
                        <p class="text-[var(--color-text-secondary)] mb-6">Select your primary industry to see relevant instruments</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach([
                                ['label' => 'Pharmaceutical', 'icon' => 'flask', 'value' => 'pharma'],
                                ['label' => 'Food & Beverage', 'icon' => 'beaker', 'value' => 'food'],
                                ['label' => 'Chemical', 'icon' => 'test-tube', 'value' => 'chemical'],
                                ['label' => 'Research & Education', 'icon' => 'microscope', 'value' => 'research'],
                                ['label' => 'Jewelry & Precious Metals', 'icon' => 'gem', 'value' => 'jewelry'],
                                ['label' => 'Industrial Manufacturing', 'icon' => 'cog', 'value' => 'industrial'],
                            ] as $industry)
                                <button
                                    type="button"
                                    class="p-4 border border-[var(--color-border-light)] rounded-xl hover:border-orange-500 hover:bg-orange-50 transition-all duration-200 text-left focus-ring"
                                    onclick="selectIndustry('{{ $industry['value'] }}')"
                                    :class="{ 'border-orange-500 bg-orange-50': selectedIndustry === '{{ $industry['value'] }}' }"
                                >
                                    <x-ui.icon :name="{{ $industry['icon'] }}" class="h-8 w-8 text-orange-600 mb-2" />
                                    <p class="font-medium text-[var(--color-text-primary)]">{{ $industry['label'] }}</p>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 2: Application -->
                    <div class="wizard-panel" x-show="currentStep === 1" x-transition>
                        <h3 class="text-xl font-bold text-[var(--color-text-primary)] mb-2">What's your primary application?</h3>
                        <p class="text-[var(--color-text-secondary)] mb-6">Choose the main use case for your instrument</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach([
                                ['label' => 'Quality Control', 'icon' => 'shield', 'value' => 'qc'],
                                ['label' => 'R&D / Formulation', 'icon' => 'flask', 'value' => 'rd'],
                                ['label' => 'Production Weighing', 'icon' => 'cog', 'value' => 'production'],
                                ['label' => 'Analytical Testing', 'icon' => 'microscope', 'value' => 'analytical'],
                                ['label' => 'Regulatory Compliance', 'icon' => 'shield', 'value' => 'compliance'],
                                ['label' => 'General Laboratory', 'icon' => 'beaker', 'value' => 'general'],
                            ] as $app)
                                <button
                                    type="button"
                                    class="p-4 border border-[var(--color-border-light)] rounded-xl hover:border-orange-500 hover:bg-orange-50 transition-all duration-200 text-left focus-ring"
                                    onclick="selectApplication('{{ $app['value'] }}')"
                                    :class="{ 'border-orange-500 bg-orange-50': selectedApplication === '{{ $app['value'] }}' }"
                                >
                                    <x-ui.icon :name="{{ $app['icon'] }}" class="h-8 w-8 text-orange-600 mb-2" />
                                    <p class="font-medium text-[var(--color-text-primary)]">{{ $app['label'] }}</p>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 3: Capacity -->
                    <div class="wizard-panel" x-show="currentStep === 2" x-transition>
                        <h3 class="text-xl font-bold text-[var(--color-text-primary)] mb-2">What capacity range do you need?</h3>
                        <p class="text-[var(--color-text-secondary)] mb-6">Select the maximum weight you'll be measuring</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach([
                                ['label' => 'Up to 100g', 'value' => '0-100'],
                                ['label' => '100g - 500g', 'value' => '100-500'],
                                ['label' => '500g - 2kg', 'value' => '500-2000'],
                                ['label' => '2kg - 10kg', 'value' => '2000-10000'],
                                ['label' => '10kg - 50kg', 'value' => '10000-50000'],
                                ['label' => '50kg+', 'value' => '50000+'],
                            ] as $cap)
                                <button
                                    type="button"
                                    class="p-4 border border-[var(--color-border-light)] rounded-xl hover:border-orange-500 hover:bg-orange-50 transition-all duration-200 text-left focus-ring"
                                    onclick="selectCapacity('{{ $cap['value'] }}')"
                                    :class="{ 'border-orange-500 bg-orange-50': selectedCapacity === '{{ $cap['value'] }}' }"
                                >
                                    <x-ui.icon name="scale" class="h-8 w-8 text-orange-600 mb-2" />
                                    <p class="font-medium text-[var(--color-text-primary)]">{{ $cap['label'] }}</p>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 4: Results -->
                    <div class="wizard-panel" x-show="currentStep === 3" x-transition>
                        <h3 class="text-xl font-bold text-[var(--color-text-primary)] mb-2">Recommended for You</h3>
                        <p class="text-[var(--color-text-secondary)] mb-6">Based on your selections, here are our top recommendations</p>
                        <div id="wizard-results" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="text-center py-12 text-[var(--color-text-muted)]">
                                <x-ui.icon name="search" class="h-12 w-12 mx-auto mb-4 text-[var(--color-border-medium)]" />
                                <p>Select your preferences to see recommendations</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="wizard-actions">
                    <button type="button" class="btn-secondary" x-show="currentStep > 0" @click="prevStep()">
                        <x-ui.icon name="chevron-left" class="h-4 w-4 mr-2" /> Back
                    </button>
                    <button type="button" class="btn-primary" x-show="currentStep < 3" @click="nextStep()">
                        Next <x-ui.icon name="chevron-right" class="h-4 w-4 ml-2" />
                    </button>
                    <a href="{{ route('products.index') }}" class="btn-primary" x-show="currentStep === 3">
                        <x-ui.icon name="grid" class="h-4 w-4 mr-2" /> View All Products
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Exhibitions -->
    @if($upcomingExhibitions->count() > 0)
    <section class="py-16 md:py-24 bg-white border-y border-[var(--color-border-light)]" aria-label="Upcoming exhibitions">
        <div class="container-responsive">
            <div class="section-header">
                <h2 class="section-title">Upcoming Exhibitions</h2>
                <p class="section-subtitle">Meet Aczet at global trade shows and exhibitions</p>
            </div>
            <div class="content-grid">
                @foreach($upcomingExhibitions as $exhibition)
                    <article class="content-card">
                        @if($exhibition->image)
                            <div class="content-image">
                                <img src="{{ $exhibition->image }}" alt="{{ $exhibition->title }}" loading="lazy">
                            </div>
                        @endif
                        <div class="content-body">
                            <div class="content-meta">
                                <x-ui.icon name="calendar" class="h-4 w-4" />
                                <time>{{ $exhibition->event_date->format('M d, Y') }}</time>
                                <span class="mx-1">•</span>
                                <x-ui.icon name="map-pin" class="h-4 w-4" />
                                <span>{{ $exhibition->location }}</span>
                            </div>
                            <h3 class="content-title">{{ $exhibition->title }}</h3>
                            <p class="content-excerpt">{{ Str::limit($exhibition->description ?? '', 120) }}</p>
                            @if($exhibition->hall_booth)
                                <div class="flex items-center gap-2 text-sm text-orange-600 font-medium">
                                    <x-ui.icon name="map-pin" class="h-4 w-4" />
                                    <span>Hall/Booth: {{ $exhibition->hall_booth }}</span>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Blogs -->
    @if($latestBlogs->count() > 0)
    <section class="py-16 md:py-24 bg-[var(--color-bg-primary)]" aria-label="Latest blog posts">
        <div class="container-responsive">
            <div class="section-header">
                <h2 class="section-title">Latest Insights</h2>
                <p class="section-subtitle">Technical articles, industry news, and metrology expertise</p>
            </div>
            <div class="content-grid">
                @foreach($latestBlogs as $blog)
                    <article class="content-card">
                        @if($blog->image)
                            <div class="content-image">
                                <img src="{{ $blog->image }}" alt="{{ $blog->title }}" loading="lazy">
                            </div>
                        @endif
                        <div class="content-body">
                            <div class="content-meta">
                                <x-ui.icon name="calendar" class="h-4 w-4" />
                                <time>{{ $blog->published_at->format('M d, Y') }}</time>
                                <span class="mx-1">•</span>
                                <x-ui.icon name="user" class="h-4 w-4" />
                                <span>{{ $blog->author }}</span>
                            </div>
                            <h3 class="content-title"><a href="{{ route('blogs.show', $blog->slug) }}" class="hover:text-orange-600 transition-colors">{{ $blog->title }}</a></h3>
                            <p class="content-excerpt">{{ Str::limit($blog->summary ?? $blog->content, 120) }}</p>
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-orange-600 hover:text-orange-700">
                                Read More <x-ui.icon name="arrow-right" class="h-4 w-4" />
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('blogs.index') }}" class="btn-outline">
                    <x-ui.icon name="newspaper" class="h-4 w-4 mr-2" /> View All Articles
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="cta-section" aria-label="Call to action">
        <div class="container-responsive">
            <div class="cta-card">
                <h2 class="cta-title">Ready to Find Your Perfect Instrument?</h2>
                <p class="cta-text">Our metrology experts are ready to help you select the right precision weighing solution for your application. Get a personalized quote today.</p>
                <div class="cta-buttons">
                    <a href="{{ route('contact') }}" class="btn-super-primary">
                        <x-ui.icon name="send" class="h-5 w-5" /> Get a Quote
                    </a>
                    <a href="https://wa.me/912228474800?text=Hello%20Aczet%2C%20I%20need%20help%20selecting%20a%20precision%20instrument" target="_blank" rel="noopener" class="btn-glass-secondary" style="background: rgba(37, 211, 102, 0.2); border-color: rgba(37, 211, 102, 0.4); color: #25D366;">
                        <x-ui.icon name="whatsapp" class="h-5 w-5" /> WhatsApp Chat
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    // Hero Carousel
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    let slideInterval;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
            dot.setAttribute('aria-selected', i === index);
        });
        currentSlide = index;
    }

    function nextSlide() {
        showSlide((currentSlide + 1) % slides.length);
    }

    function prevSlide() {
        showSlide((currentSlide - 1 + slides.length) % slides.length);
    }

    function goToSlide(index) {
        showSlide(index);
        resetInterval();
    }

    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 6000);
    }

    // Auto-play
    slideInterval = setInterval(nextSlide, 6000);

    // Pause on hover
    const carousel = document.getElementById('hero-carousel');
    carousel?.addEventListener('mouseenter', () => clearInterval(slideInterval));
    carousel?.addEventListener('mouseleave', resetInterval);

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') prevSlide();
        if (e.key === 'ArrowRight') nextSlide();
    });

    // Product Finder Wizard
    document.addEventListener('alpine:init', () => {
        Alpine.data('productFinder', () => ({
            currentStep: 0,
            selectedIndustry: '',
            selectedApplication: '',
            selectedCapacity: '',

            selectIndustry(value) {
                this.selectedIndustry = value;
            },

            selectApplication(value) {
                this.selectedApplication = value;
            },

            selectCapacity(value) {
                this.selectedCapacity = value;
            },

            nextStep() {
                if (this.currentStep === 0 && !this.selectedIndustry) return;
                if (this.currentStep === 1 && !this.selectedApplication) return;
                if (this.currentStep === 2 && !this.selectedCapacity) return;
                this.currentStep++;
            },

            prevStep() {
                this.currentStep--;
            },
        }));
    });
</script>
@endsection