@extends('layouts.app')

@section('title', $industry['name'] . ' - Solutions & Equipment - Aczet Metrology')

@section('styles')
    <link rel="stylesheet" href="{{ vasset('css/industries.css') }}">
@endsection

@section('content')
<!-- Hero Section -->
<section class="ind-hero-section">
    <div class="ind-container">
        <div class="ind-hero-grid">
            <div class="ind-hero-content">
                <nav class="ind-breadcrumb-strip" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
                    <i class="fa-solid fa-chevron-right ind-sep"></i>
                    <a href="{{ route('industries.index') }}">Industries</a>
                    <i class="fa-solid fa-chevron-right ind-sep"></i>
                    <span class="current">{{ $industry['name'] }}</span>
                </nav>
                <span class="ind-hero-eyebrow">Industry Solutions &amp; Compliance</span>
                <h1 class="ind-hero-title">{{ $industry['name'] }}</h1>
                <p class="ind-hero-subtitle">{{ $industry['hero_subtitle'] }}</p>
                <div class="ind-hero-actions">
                    <a href="#industry-solutions" class="btn-hero-primary">
                        <i class="fa-solid fa-layer-group"></i> Explore Solutions
                    </a>
                    <button type="button" 
                            onclick="openQuoteModal('Industry Consultation - {{ addslashes($industry['name']) }}', null, '{{ addslashes($industry['name']) }}')" 
                            class="btn-hero-secondary">
                        <i class="fa-regular fa-envelope"></i> Request Consultation
                    </button>
                </div>
            </div>
            <div class="ind-hero-visual">
                <img src="{{ vasset($industry['image'] ?? 'images/banner-industry.jpg') }}" 
                     alt="{{ $industry['name'] }}" 
                     onerror="this.onerror=null;this.src='{{ asset('images/products/banner-balance.png') }}';">
            </div>
        </div>
    </div>
</section>

<!-- Main Detail & Challenges Section -->
<section class="ind-detail-section" id="industry-solutions">
    <div class="ind-container">
        <div class="ind-detail-grid">
            <!-- Left Main Column -->
            <div class="ind-main-col">
                <div class="ind-content-block">
                    <h2>Industry Overview &amp; Compliance Demands</h2>
                    <p>{{ $industry['description'] }}</p>
                </div>

                <div class="ind-pillars-grid">
                    <div class="ind-pillar-card challenges">
                        <h3><i class="fa-solid fa-triangle-exclamation"></i> Key Operational &amp; Regulatory Challenges</h3>
                        <ul class="ind-challenges-list">
                            @foreach($industry['challenges'] as $challenge)
                                <li>
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $challenge }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="ind-pillar-card solutions">
                        <h3><i class="fa-solid fa-circle-check"></i> Aczet Engineered Solutions</h3>
                        <ul class="ind-solutions-list">
                            @foreach($industry['solutions'] as $sol)
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    <span>{{ $sol }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="ind-content-block ind-content-block-spaced">
                    <h2>Standards &amp; Regulatory Compliance</h2>
                    <p>Instruments deployed in this sector adhere to international traceable metrology standards:</p>
                    <div class="ind-standards-tags">
                        @foreach($industry['standards'] as $std)
                            <span class="ind-standard-tag">
                                <i class="fa-solid fa-certificate"></i>
                                <span>{{ $std }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column -->
            <aside class="ind-sidebar-col">
                <div class="ind-sidebar-nav">
                    <h3 class="ind-sidebar-title">
                        <i class="fa-solid fa-industry"></i> All Industry Sectors
                    </h3>
                    <div class="ind-sidebar-links">
                        @foreach($industries as $otherSlug => $otherInd)
                            <a href="{{ route('industries.show', $otherSlug) }}" 
                               class="ind-sidebar-link {{ $otherSlug === $industry['slug'] ? 'active' : '' }}">
                                <span><i class="{{ $otherInd['icon'] }}"></i> {{ $otherInd['short_name'] }}</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="ind-sidebar-card">
                    <h3>Technical Consultation</h3>
                    <p>
                        Speak directly with an Aczet application engineer regarding product specifications, custom software integrations, or compliance validation.
                    </p>
                    <button type="button" 
                            onclick="openQuoteModal('Industry Callback - {{ addslashes($industry['name']) }}', null, '{{ addslashes($industry['name']) }}')" 
                            class="btn-ind-cta">
                        Request Specialist Callback
                    </button>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Recommended Equipment Section -->
@if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
<section class="ind-products-section">
    <div class="ind-container">
        <div class="ind-section-header">
            <div class="aczet-accent-bar mt-green-bar"></div>
            <h2>Recommended Equipment for {{ $industry['short_name'] }}</h2>
            <p>High-reliability instruments and systems engineered to meet the operational demands of this sector.</p>
        </div>

        <div class="ind-products-grid">
            @foreach($recommendedProducts as $prod)
                <div class="ind-product-card">
                    <div class="ind-product-img">
                        <img src="{{ $prod->main_image }}" 
                             alt="{{ $prod->name }}"
                             onerror="this.onerror=null; this.src='{{ asset('images/products/noimage.jpeg') }}';">
                    </div>
                    <h3 class="ind-product-name">{{ $prod->name }}</h3>
                    <p class="ind-product-desc">{{ Str::limit($prod->short_description ?: 'High precision measurement instrument engineered for industrial and laboratory reliability.', 110) }}</p>
                    <div class="ind-product-actions">
                        <a href="{{ route('products.show', $prod->slug) }}" class="btn-ind-details">
                            View Details &rarr;
                        </a>
                        <form action="{{ route('quote_cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $prod->id }}">
                            <button type="submit" class="btn-ind-quote">
                                <i class="fa-solid fa-file-invoice"></i> Request Quote
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Callout Banner Section -->
<section class="ind-consult-section">
    <div class="ind-container">
        <div class="ind-consult-card">
            <div class="ind-consult-content">
                <span class="ind-consult-pill">Tailored System Engineering</span>
                <h2>Looking for Certified Metrology in {{ $industry['short_name'] }}?</h2>
                <p>
                    Aczet provides turn-key solutions including IQ/OQ/PQ validation documentation, factory acceptance testing, and on-site ISO 17025 traceable calibration.
                </p>
            </div>
            <div class="ind-consult-actions">
                <a href="{{ route('contact') }}" class="btn-hero-primary btn-hero-center">
                    Contact Engineering Support &rarr;
                </a>
                <a href="{{ route('quote_cart.index') }}" class="btn-ind-secondary">
                    Review Quote Cart
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
