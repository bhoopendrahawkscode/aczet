@extends('layouts.app')

@section('title', 'Industry Solutions & Sector Applications - Aczet Metrology')

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
                    <a href="{{ route('home') }}"><i class="fa-solid fa-house" style="font-size:11px;"></i> Home</a>
                    <i class="fa-solid fa-chevron-right ind-sep"></i>
                    <span class="current">Industries &amp; Market Sectors</span>
                </nav>
                <span class="ind-hero-eyebrow">Metrology &amp; Inspection Systems</span>
                <h1 class="ind-hero-title">Industry Solutions &amp; Metrology Systems</h1>
                <p class="ind-hero-subtitle">
                    Engineered precision weighing, non-destructive material analysis, and automated product inspection systems tailored to meet stringent regulatory, quality, and throughput standards across global manufacturing and research sectors.
                </p>
                <div class="ind-hero-actions">
                    <a href="#sectors-grid" class="btn-hero-primary">
                        <i class="fa-solid fa-layer-group"></i> Browse All 8 Sectors
                    </a>
                    <a href="{{ route('contact') }}" class="btn-hero-secondary">
                        <i class="fa-regular fa-envelope"></i> Speak to Specialist
                    </a>
                </div>
            </div>
            <div class="ind-hero-visual">
                <img src="{{ vasset('images/products/banner-balance.png') }}" 
                     alt="Aczet Industrial & Laboratory Solutions" 
                     onerror="this.style.display='none';">
            </div>
        </div>
    </div>
</section>

<!-- Industries Grid Section -->
<section class="ind-grid-section" id="sectors-grid">
    <div class="ind-container">
        <div class="ind-section-header">
            <div class="aczet-accent-bar mt-green-bar"></div>
            <h2>Key Industries We Serve</h2>
            <p>
                From GLP/GMP-compliant pharmaceutical research to heavy-duty metals recycling and precision jewelry manufacturing, discover how Aczet measurement technology ensures operational excellence.
            </p>
        </div>

        <div class="ind-cards-grid">
            @foreach($industries as $slug => $ind)
                <a href="{{ route('industries.show', $slug) }}" class="ind-card">
                    <div class="ind-card-header">
                        <div class="ind-card-icon-box">
                            <i class="{{ $ind['icon'] }}"></i>
                        </div>
                        <div class="ind-card-title-group">
                            <h3 class="ind-card-title">{{ $ind['name'] }}</h3>
                            <p class="ind-card-tagline">{{ $ind['tagline'] }}</p>
                        </div>
                    </div>
                    <div class="ind-card-body">
                        <p class="ind-card-desc">{{ Str::limit($ind['description'], 140) }}</p>
                        <div class="ind-standards-row">
                            @foreach(array_slice($ind['standards'], 0, 3) as $std)
                                <span class="ind-standard-badge">
                                    <i class="fa-solid fa-check"></i> {{ $std }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="ind-card-footer">
                        <span>Explore Sector Solutions</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Callout Banner Section -->
<section class="ind-consult-section">
    <div class="ind-container">
        <div class="ind-consult-card">
            <div class="ind-consult-content">
                <span class="ind-consult-pill">Custom Metrology Engineering</span>
                <h2>Need a Tailored Weighing or Inspection System for Your Facility?</h2>
                <p>
                    Our application metrologists and industry engineers collaborate with your technical team to design custom weighing platforms, PLC integrations, and certified analytical setups tailored to your exact regulatory compliance needs.
                </p>
            </div>
            <div class="ind-consult-actions">
                <a href="{{ route('contact') }}" class="btn-hero-primary" style="text-align:center;">
                    Consult an Industry Specialist &rarr;
                </a>
                <a href="{{ route('quote_cart.index') }}" class="btn-ind-secondary">
                    View Quote Cart
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
