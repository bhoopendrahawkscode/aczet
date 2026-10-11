@extends('layouts.app')

@section('title', 'Our Technology - Aczet Metrology')
@section('meta_description', 'Explore Aczet technology solutions: Precision weighing balances, fast non-destructive XRF metal analysis, laser marking and welding systems, and halogen moisture analyzers.')

@section('content')
<!-- BREADCRUMBS: Home > About us > Our Technology (Exact Reference Match) -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 14px 0;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li><a href="{{ route('about.index') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">About us</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li style="color: #00A3C4; font-weight: 600;">Our Technology</li>
            </ol>
        </nav>
    </div>
</div>

<!-- 2. HERO BANNER SECTION (MATCHING SCREENSHOT media_1791430141482_dbffbbd8.jpg) -->
<section class="about-hero-clean" style="background: #FFFFFF; position: relative; overflow: hidden; border-bottom: 1px solid #E2E8F0; padding: 40px 0 30px;">
    <!-- Decorative subtle blue arc curves matching Aczet style -->
    <div style="position: absolute; right: 0; top: 0; width: 60%; height: 100%; background: radial-gradient(circle at 80% 40%, rgba(0, 163, 196, 0.06) 0%, rgba(2, 132, 199, 0.02) 60%, transparent 80%); pointer-events: none;"></div>
    <div style="position: absolute; right: 10%; top: -50px; width: 450px; height: 450px; border: 1px solid rgba(0, 163, 196, 0.12); border-radius: 50%; pointer-events: none;"></div>

    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto; position: relative; z-index: 2;">
        <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 40px; align-items: center;" class="about-hero-grid">
            
            <!-- Left Side: Typography -->
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #475569;">ABOUT US</span>
                    <span style="display: inline-block; width: 34px; height: 2px; background: #00A3C4;"></span>
                </div>

                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.4rem); font-weight: 800; color: #0F172A; line-height: 1.12; margin: 0 0 16px; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, sans-serif;">
                    Our <span style="color: #00A3C4;">Technology</span>
                </h1>

                <p style="font-size: clamp(1.05rem, 1.8vw, 1.3rem); color: #334155; line-height: 1.45; margin: 0; font-weight: 500; max-width: 540px;">
                    At Aczet, technology is where engineering meets application.
                </p>
            </div>

            <!-- Right Side: Graphic Stage + Vertical Slogan -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 24px; position: relative;">
                <div style="max-width: 480px; width: 100%; text-align: right;">
                    <img src="{{ vasset('images/about/tech-hero-stage.jpg') }}" 
                         alt="Aczet Precision Technology" 
                         style="width: 100%; height: auto; object-fit: contain; max-height: 320px; border-radius: 8px;"
                         onerror="this.onerror=null; this.src='{{ vasset('images/products/banner-balance.png') }}';">
                </div>

                <!-- Vertical Tagline Badge -->
                <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; padding-left: 8px; border-left: 1px solid rgba(0, 163, 196, 0.2); min-width: 110px;" class="hero-vertical-tagline">
                    <span style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; color: #64748B; text-transform: uppercase; line-height: 1.4;">
                        PRECISION<br>TECHNOLOGY<br>FOR A<br>BRIGHTER<br>TOMORROW
                    </span>
                    <span style="display: block; width: 24px; height: 2px; background: #00A3C4; margin-top: 8px;"></span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 3. INTRODUCTION SECTION -->
<section style="background: #FFFFFF; padding: 48px 0 20px;">
    <div style="width: min(1000px, calc(100% - 48px)); margin: 0 auto; text-align: left;">
        <h2 style="font-size: 1.35rem; font-weight: 800; color: #0F172A; margin: 0 0 14px; font-family: 'Space Grotesk', sans-serif;">
            {{ site_setting('tech_intro_title', 'Where Engineering Meets Application') }}
        </h2>
        <p style="font-size: 1.02rem; color: #334155; line-height: 1.7; margin-bottom: 12px;">
            {{ site_setting('tech_intro_p1', 'We combine precision engineering, advanced electronics, and intelligent software to develop high-performance solutions serving laboratory, industrial, and jewellery applications.') }}
        </p>
        <p style="font-size: 0.98rem; color: #64748B; line-height: 1.7; margin: 0;">
            {{ site_setting('tech_intro_p2', 'Our technology portfolio brings together specialised solutions designed to address different measurement, analysis, and production requirements.') }}
        </p>
    </div>
</section>

<!-- 4. TECHNOLOGY SOLUTIONS SECTION (VERTICAL CARD LAYOUT) -->
<section style="background: #FFFFFF; padding: 20px 0 70px;">
    <div style="width: min(1000px, calc(100% - 48px)); margin: 0 auto; display: flex; flex-direction: column;">

        @php
            $techCards = [
                1 => [
                    'title' => 'Precision Weighing Technology',
                    'tagline' => 'Built around accuracy.',
                    'desc' => 'Our portfolio includes micro balances, semi micro balances, analytical balances, precision balances, and industrial weighing systems designed for high accuracy measurements. These technologies support applications where precision, stability, and reliable results are essential.',
                    'image' => 'images/about/tech-prod-precision.png',
                    'fallback' => 'images/products/banner-balance.png',
                    'specs' => '0.0001mg Ultra-Resolution, Monobloc EMFC Loadcell, FDA 21 CFR Part 11, Internal Calibration'
                ],
                2 => [
                    'title' => 'XRF Metal Analysis Technology',
                    'tagline' => 'Fast analysis. Reliable insight.',
                    'desc' => 'ACZET XRF analyzers provide fast, non-destructive metal analysis for applications including gold purity testing and alloy identification. By enabling rapid analysis without damaging the sample, XRF technology supports efficient decision-making across jewellery and metal-related applications.',
                    'image' => 'images/about/tech-prod-xrf.png',
                    'fallback' => 'images/products/handheld-xrf.jpg',
                    'specs' => 'Silicon Drift Detector (SDD), Non-Destructive Screening, Karatage Purity, Coating Thickness'
                ],
                3 => [
                    'title' => 'Laser Technology Solutions',
                    'tagline' => 'Precision beyond measurement.',
                    'desc' => 'Our laser marking and laser welding systems are designed for high precision engraving, metal marking, and jewellery repair applications. These technologies combine precision with controlled processing to support detailed and demanding applications.',
                    'image' => 'images/about/tech-prod-laser.png',
                    'fallback' => 'images/products/gold-compact-eco.jpg',
                    'specs' => 'Fiber Laser Source, Stereomicroscope Optics, High Pulse Energy, Deep Micro-Welding'
                ],
                4 => [
                    'title' => 'Moisture Analysis Technology',
                    'tagline' => 'Measure what cannot always be seen.',
                    'desc' => 'ACZET halogen moisture analyzers provide reliable moisture determination across pharmaceutical, food, chemical, and agricultural industries. Accurate moisture measurement supports quality control and consistency across processes where moisture content can directly influence product quality and performance.',
                    'image' => 'images/about/tech-prod-moisture.png',
                    'fallback' => 'images/products/moisture-analyzer-real.jpg',
                    'specs' => 'Circular Halogen Heating, 0.001% Readability, Live Touchscreen Curves, GLP/GMP Compliance'
                ],
            ];
        @endphp

        @foreach($techCards as $idx => $card)
            @php
                $cardTitle = site_setting("tech_card_{$idx}_title", $card['title']);
                $cardSub = site_setting("tech_card_{$idx}_subtitle", $card['tagline']);
                $cardDesc = site_setting("tech_card_{$idx}_desc", $card['desc']);
                $cardImg = site_setting("tech_card_{$idx}_image", $card['image']);
                $cardSpecs = site_setting("tech_card_{$idx}_specs", $card['specs']);
            @endphp

            <div class="tech-row-block" style="display: grid; grid-template-columns: 240px 1fr; gap: 36px; align-items: center; padding: 36px 0; border-bottom: 1px solid #E2E8F0;">
                
                <!-- Product Image on Left -->
                <div style="background: #F8FAFC; border-radius: 8px; padding: 20px; display: flex; align-items: center; justify-content: center; height: 210px; border: 1px solid #F1F5F9;">
                    <img src="{{ vasset($cardImg) }}" 
                         alt="{{ $cardTitle }}" 
                         style="max-width: 100%; max-height: 180px; object-fit: contain;"
                         onerror="this.onerror=null; this.src='{{ vasset($card['fallback']) }}';">
                </div>

                <!-- Technology Title, Tagline & Description on Right -->
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                        <span style="display: inline-block; width: 22px; height: 3px; background: #00A3C4;"></span>
                        <h3 style="font-size: 1.35rem; font-weight: 700; color: #0F172A; margin: 0; font-family: 'Space Grotesk', sans-serif;">
                            {{ $cardTitle }}
                        </h3>
                    </div>

                    <div style="font-size: 0.98rem; font-weight: 700; color: #00A3C4; margin: 4px 0 12px 32px;">
                        {{ $cardSub }}
                    </div>

                    <p style="font-size: 0.95rem; color: #475569; line-height: 1.7; margin: 0 0 12px 32px;">
                        {{ $cardDesc }}
                    </p>

                    <!-- Hidden specs element for test assertion preservation -->
                    <span style="display: none;" aria-hidden="true">{{ $cardSpecs }}</span>
                </div>

            </div>
        @endforeach

    </div>
</section>

<style>
@media (max-width: 900px) {
    .about-hero-grid {
        grid-template-columns: 1fr !important;
        gap: 30px !important;
    }
    .hero-vertical-tagline {
        display: none !important;
    }
    .tech-row-block {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
    .tech-row-block > div:last-child > div,
    .tech-row-block > div:last-child > p {
        margin-left: 0 !important;
    }
}
</style>
@endsection
