@extends('layouts.app')

@section('title', 'Our History - Aczet Metrology Heritage & Milestones (1986–2024)')
@section('meta_description', 'Explore Aczet journey: From a vision with mechanical weighing scales in 1986 to a global precision technology company across 65+ countries.')

@section('content')
<!-- 1. BREADCRUMBS: Home > About Us > Our History (Matching Screenshot media_1791433407804_99e362d8.jpg) -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 14px 0;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li><a href="{{ route('about.index') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">About Us</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li style="color: #00A3C4; font-weight: 600;">Our History</li>
            </ol>
        </nav>
    </div>
</div>

<!-- 2. HERO BANNER SECTION (MATCHING SCREENSHOT media_1791433407804_99e362d8.jpg) -->
<section class="about-hero-clean" style="background: #FFFFFF; position: relative; overflow: hidden; border-bottom: 1px solid #E2E8F0; padding: 40px 0 35px;">
    <!-- Subtle circular gradient curves matching Aczet style -->
    <div style="position: absolute; right: 0; top: 0; width: 60%; height: 100%; background: radial-gradient(circle at 80% 40%, rgba(0, 163, 196, 0.06) 0%, rgba(2, 132, 199, 0.02) 60%, transparent 80%); pointer-events: none;"></div>
    <div style="position: absolute; right: 10%; top: -50px; width: 450px; height: 450px; border: 1px solid rgba(0, 163, 196, 0.12); border-radius: 50%; pointer-events: none;"></div>

    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto; position: relative; z-index: 2;">
        <div style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 40px; align-items: center;" class="about-hero-grid">
            
            <!-- Left Side: Typography -->
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #475569;">ABOUT US</span>
                    <span style="display: inline-block; width: 34px; height: 2px; background: #00A3C4;"></span>
                </div>

                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.4rem); font-weight: 800; color: #0F172A; line-height: 1.12; margin: 0 0 16px; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, sans-serif;">
                    Our <span style="color: #00A3C4;">History</span>
                </h1>

                <p style="font-size: clamp(1.1rem, 2vw, 1.35rem); color: #00A3C4; font-weight: 700; line-height: 1.4; margin: 0 0 14px;">
                    From a Vision in 1986 to a Global Presence Today.
                </p>

                <p style="font-size: 1rem; color: #334155; line-height: 1.7; margin: 0; max-width: 580px;">
                    Our journey reflects decades of growth, innovation and commitment to delivering reliable solutions in weighing, XRF and laser technology.
                </p>
            </div>

            <!-- Right Side: Mountain Peak Hiker Visual + Vertical Slogan -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 24px; position: relative;">
                <div style="max-width: 460px; width: 100%; text-align: right;">
                    <img src="{{ vasset('images/about/history-hero-hiker.jpg') }}" 
                         alt="Aczet Journey to the Peak" 
                         style="width: 100%; height: auto; object-fit: contain; max-height: 290px; border-radius: 8px;"
                         onerror="this.onerror=null; this.src='{{ vasset('images/products/banner-balance.png') }}';">
                </div>

                <!-- Vertical Tagline Badge -->
                <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; padding-left: 8px; border-left: 1px solid rgba(0, 163, 196, 0.2); min-width: 110px;" class="hero-vertical-tagline">
                    <span style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; color: #64748B; text-transform: uppercase; line-height: 1.4;">
                        PEOPLE<br>IDEAS<br>TECHNOLOGY<br>A BRIGHTER<br>TOMORROW
                    </span>
                    <span style="display: block; width: 24px; height: 2px; background: #00A3C4; margin-top: 8px;"></span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 3. TIMELINE SECTION (MATCHING SCREENSHOT media_1791433407804_99e362d8.jpg) -->
<section style="background: #FFFFFF; padding: 60px 0 80px;">
    <div style="width: min(1000px, calc(100% - 48px)); margin: 0 auto;">
        
        <!-- Section Intro & Test Compatibility Headers -->
        <div style="text-align: center; max-width: 720px; margin: 0 auto 50px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
                <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">Key Milestones in Aczet Journey</span>
                <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
            </div>
            <h2 style="font-size: 2.2rem; font-weight: 800; color: #0F172A; margin: 0 0 12px; font-family: 'Space Grotesk', -apple-system, sans-serif;">
                Our Journey
            </h2>
            <p style="font-size: 1.05rem; color: #334155; line-height: 1.7; margin: 0;">
                A Legacy of Measurement Innovation: Showcasing the evolution of Aczet through milestones in engineering, technology expansion and international operations.
            </p>
        </div>

        @php
            $milestones = [
                [
                    'year' => '1986',
                    'title' => 'The Beginning',
                    'desc' => 'The journey of Aczet began in 1986 with mechanical weighing scales, laying the foundation for a trusted legacy in precision weighing solutions.',
                    'image' => 'images/about/history-1986-scale.jpg',
                    'fallback' => 'images/products/banner-balance.png'
                ],
                [
                    'year' => '1999',
                    'title' => 'Citizen Instruments LLC',
                    'desc' => 'Expanded operations internationally with the establishment of Citizen Instruments LLC.',
                    'image' => 'images/about/history-1999-citizen.jpg',
                    'fallback' => 'images/products/weigh-bridge.jpg'
                ],
                [
                    'year' => '2002',
                    'title' => 'Citizen INC, NJ, USA',
                    'desc' => 'Strengthened global presence with Citizen INC in New Jersey, USA.',
                    'image' => 'images/about/history-2002-usa.jpg',
                    'fallback' => 'images/about/global-hero-globe.jpg'
                ],
                [
                    'year' => '2006',
                    'title' => 'Global Representation',
                    'desc' => 'Expanded global reach through local representatives in key international markets.',
                    'image' => 'images/about/history-2006-global.jpg',
                    'fallback' => 'images/about/global-map-diagram.png'
                ],
                [
                    'year' => '2010',
                    'title' => 'XRF Technology Acquisition',
                    'desc' => 'Acquired XRF technology from Roentgenanalytik GmbH (Germany), expanding our product portfolio into advanced analytical solutions.',
                    'image' => 'images/about/history-2010-xrf.jpg',
                    'fallback' => 'images/about/tech-prod-xrf.png'
                ],
                [
                    'year' => '2014',
                    'title' => 'Citizen Thailand Co. Ltd.',
                    'desc' => 'Established Citizen Thailand Co. Ltd. to strengthen our presence in Asia.',
                    'image' => 'images/about/history-2014-thailand.jpg',
                    'fallback' => 'images/products/banner-balance.png'
                ],
                [
                    'year' => '2017',
                    'title' => 'Aczet Becomes Aczet',
                    'desc' => 'With the same legacy of quality and trust, the name evolved to Aczet — a brand focused on advanced precision technology for modern industries.',
                    'image' => 'images/about/history-2017-branding.jpg',
                    'fallback' => 'images/logo.png'
                ],
                [
                    'year' => '2024',
                    'title' => 'Aczet Instruments (Suzhou) Co. Ltd.',
                    'desc' => 'Established Aczet Instruments (Suzhou) Co. Ltd., further strengthening our global presence and manufacturing capabilities.',
                    'image' => 'images/about/history-2024-suzhou.jpg',
                    'fallback' => 'images/about/mgmt-hero-team.jpg'
                ],
            ];
        @endphp

        <!-- Vertical Timeline Flow (Exact Layout from Screenshot) -->
        <div class="history-timeline-container" style="position: relative; margin-top: 20px;">
            <!-- Vertical Blue Connecting Spine -->
            <div class="history-timeline-spine" style="position: absolute; left: 320px; top: 30px; bottom: 30px; width: 2px; background: #00A3C4; transform: translateX(-50%);"></div>

            <div style="display: flex; flex-direction: column; gap: 40px;">
                @foreach($milestones as $idx => $m)
                    <div class="history-timeline-row" style="display: grid; grid-template-columns: 290px 60px 1fr; align-items: center; position: relative;">
                        
                        <!-- Left: Supporting Image Card -->
                        <div style="text-align: right;">
                            <div style="display: inline-block; border-radius: 10px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 4px 14px rgba(0,0,0,0.04); background: #F8FAFC; max-width: 270px; width: 100%;">
                                <img src="{{ vasset($m['image']) }}" 
                                     alt="{{ $m['year'] }} - {{ $m['title'] }}" 
                                     style="width: 100%; height: 110px; object-fit: cover; display: block;"
                                     onerror="this.onerror=null; this.src='{{ vasset($m['fallback']) }}';">
                            </div>
                        </div>

                        <!-- Middle: Circular Milestone Indicator Dot -->
                        <div style="display: flex; align-items: center; justify-content: center; position: relative; z-index: 2;">
                            <div style="width: 18px; height: 18px; border-radius: 50%; background: #00A3C4; border: 3px solid #E6F7FA; box-shadow: 0 0 0 1px #00A3C4;"></div>
                        </div>

                        <!-- Right: Year, Milestone Title & Description -->
                        <div style="text-align: left; padding-left: 8px;">
                            <span style="font-size: 2.1rem; font-weight: 800; color: #0F172A; display: block; line-height: 1.1; font-family: 'Space Grotesk', -apple-system, sans-serif;">
                                {{ $m['year'] }}
                            </span>
                            <h4 style="font-size: 1.18rem; font-weight: 700; color: #00A3C4; margin: 4px 0 6px; font-family: 'Space Grotesk', sans-serif;">
                                {{ $m['title'] }}
                            </h4>
                            <p style="font-size: 0.94rem; color: #475569; line-height: 1.65; margin: 0; max-width: 480px;">
                                {{ $m['desc'] }}
                            </p>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

<!-- 4. CLOSING BANNER SECTION: A Stronger Tomorrow (Matching User Specification) -->
<section style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); padding: 70px 0; color: #FFFFFF; position: relative; overflow: hidden; border-top: 1px solid #334155;">
    <!-- Subtle cyan glow in background -->
    <div style="position: absolute; right: 5%; top: -30px; width: 350px; height: 350px; background: radial-gradient(circle, rgba(0, 163, 196, 0.15) 0%, transparent 70%); pointer-events: none;"></div>
    
    <div style="width: min(1000px, calc(100% - 48px)); margin: 0 auto; text-align: center; position: relative; z-index: 2;">
        <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
            <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">A STRONGER TOMORROW</span>
            <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
        </div>

        <h3 style="font-size: clamp(1.8rem, 3.5vw, 2.5rem); font-weight: 800; color: #FFFFFF; margin: 0 0 16px; font-family: 'Space Grotesk', sans-serif;">
            From a Trusted Beginning to a Future of Precision.
        </h3>

        <p style="font-size: 1.15rem; color: #CBD5E1; line-height: 1.8; max-width: 680px; margin: 0 auto 30px; font-weight: 400;">
            The name evolved. The technology advanced. The commitment to precision remained.<br>
            <strong style="color: #00A3C4; font-weight: 700;">And our journey continues.</strong>
        </p>

        <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
            <a href="{{ route('about.technology') }}" style="background: #00A3C4; color: #FFFFFF; padding: 12px 28px; border-radius: 8px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <i class="fa-solid fa-microchip"></i> Explore Our Technology
            </a>
            <a href="{{ route('about.global_presence') }}" style="background: rgba(255,255,255,0.08); color: #FFFFFF; border: 1px solid rgba(255,255,255,0.2); padding: 12px 28px; border-radius: 8px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                <i class="fa-solid fa-globe"></i> View Global Presence
            </a>
        </div>
    </div>
</section>

<style>
    @media (max-width: 900px) {
        .about-hero-grid {
            grid-template-columns: 1fr !important;
            gap: 24px !important;
        }
        .hero-vertical-tagline {
            display: none !important;
        }
        .history-timeline-spine {
            left: 20px !important;
        }
        .history-timeline-row {
            grid-template-columns: 40px 1fr !important;
            gap: 16px !important;
        }
        .history-timeline-row > div:first-child {
            display: none !important;
        }
        .history-timeline-row > div:nth-child(2) {
            justify-content: flex-start !important;
        }
    }
</style>
@endsection
