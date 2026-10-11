@extends('layouts.app')

@section('title', 'Vision & Mission - Aczet Metrology Excellence & Values')
@section('meta_description', 'Discover the corporate vision and mission of Aczet: Driving innovation through precision, technology, and customer trust across laboratory and industrial sectors worldwide.')

@section('content')
<!-- 1. BREADCRUMBS: Home > About us > Vision & Mission -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 14px 0;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li><a href="{{ route('about.index') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">About us</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li style="color: #00A3C4; font-weight: 600;">Vision &amp; Mission</li>
            </ol>
        </nav>
    </div>
</div>

<!-- 2. HERO BANNER SECTION (MATCHING SCREENSHOT media_1791430205830_d43dd615.jpg) -->
<section class="about-hero-clean" style="background: #FFFFFF; position: relative; overflow: hidden; border-bottom: 1px solid #E2E8F0; padding: 40px 0 30px;">
    <!-- Subtle circular gradient curves matching Aczet style -->
    <div style="position: absolute; right: 0; top: 0; width: 60%; height: 100%; background: radial-gradient(circle at 80% 40%, rgba(0, 163, 196, 0.06) 0%, rgba(2, 132, 199, 0.02) 60%, transparent 80%); pointer-events: none;"></div>
    <div style="position: absolute; right: 8%; top: -60px; width: 480px; height: 480px; border: 1px solid rgba(0, 163, 196, 0.12); border-radius: 50%; pointer-events: none;"></div>

    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto; position: relative; z-index: 2;">
        <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 40px; align-items: center;" class="about-hero-grid">
            
            <!-- Left Side: Typography -->
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #475569;">ABOUT US</span>
                    <span style="display: inline-block; width: 34px; height: 2px; background: #00A3C4;"></span>
                </div>

                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.4rem); font-weight: 800; color: #0F172A; line-height: 1.15; margin: 0 0 16px; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, sans-serif;">
                    Vision &amp; <span style="color: #00A3C4;">Mission</span>
                </h1>

                <p style="font-size: clamp(1.05rem, 1.8vw, 1.25rem); color: #334155; line-height: 1.5; margin: 0; font-weight: 500; max-width: 560px;">
                    Driving innovation through precision, technology and customer trust.
                </p>
            </div>

            <!-- Right Side: Crystal Sphere Graphic + Vertical Slogan -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 24px; position: relative;">
                <div style="max-width: 440px; width: 100%; text-align: right;">
                    <img src="{{ vasset('images/about/vision-hero-sphere.jpg') }}" 
                         alt="Aczet Vision & Mission" 
                         style="width: 100%; height: auto; object-fit: contain; max-height: 300px; border-radius: 8px;"
                         onerror="this.onerror=null; this.src='{{ vasset('images/products/noimage.jpeg') }}';">
                </div>

                <!-- Vertical Tagline Badge -->
                <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; padding-left: 8px; border-left: 1px solid rgba(0, 163, 196, 0.2); min-width: 110px;" class="hero-vertical-tagline">
                    <span style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; color: #64748B; text-transform: uppercase; line-height: 1.4;">
                        HIGHER<br>PRECISION<br>BRIGHTER<br>TOMORROW
                    </span>
                    <span style="display: block; width: 24px; height: 2px; background: #00A3C4; margin-top: 8px;"></span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- MAIN CONTENT WRAPPER -->
<div style="background: #FFFFFF; padding: 60px 0 80px;">
    <div style="width: min(1120px, calc(100% - 48px)); margin: 0 auto; display: flex; flex-direction: column; gap: 60px;">

        <!-- 3. OUR VISION SECTION (MATCHING SCREENSHOT) -->
        <section style="display: grid; grid-template-columns: 380px 1fr; gap: 48px; align-items: center;" class="vision-mission-row">
            <!-- Left Side: Image -->
            <div style="border-radius: 12px; overflow: hidden; background: #F8FAFC; border: 1px solid #E2E8F0; box-shadow: 0 4px 20px rgba(0,0,0,0.04); text-align: center;">
                <img src="{{ vasset('images/about/vision-sphere-hand.jpg') }}" 
                     alt="Aczet Vision - Future Direction" 
                     style="width: 100%; height: 280px; object-fit: cover; display: block;"
                     onerror="this.onerror=null; this.src='{{ vasset('images/products/noimage.jpeg') }}';">
            </div>

            <!-- Right Side: Content -->
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                    <span style="display: inline-block; width: 28px; height: 2px; background: #00A3C4;"></span>
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">OUR DIRECTION</span>
                </div>

                <h2 style="font-size: 2.2rem; font-weight: 800; color: #0F172A; margin: 0 0 10px; font-family: 'Space Grotesk', -apple-system, sans-serif;">
                    Our Vision
                </h2>

                <p style="font-size: 1.22rem; font-weight: 700; color: #00A3C4; margin: 0 0 16px;">
                    A Global Technology Partner.
                </p>

                <p style="font-size: 1.05rem; color: #1E293B; line-height: 1.75; font-weight: 500; margin: 0 0 14px;">
                    To be a globally trusted technology partner by delivering innovative, reliable and high-performance solutions that create value for customers across industries.
                </p>

                <p style="font-size: 0.98rem; color: #475569; line-height: 1.75; margin: 0;">
                    Through continuous improvement, advanced engineering and customer-focused innovation, Aczet aims to strengthen its presence across global markets.
                </p>
            </div>
        </section>

        <!-- Divider -->
        <hr style="border: 0; border-top: 1px solid #E2E8F0; margin: 0;">

        <!-- 4. OUR MISSION SECTION (MATCHING SCREENSHOT) -->
        <section style="display: grid; grid-template-columns: 380px 1fr; gap: 48px; align-items: start;" class="vision-mission-row">
            <!-- Left Side: Image -->
            <div style="border-radius: 12px; overflow: hidden; background: #F8FAFC; border: 1px solid #E2E8F0; box-shadow: 0 4px 20px rgba(0,0,0,0.04); text-align: center;">
                <img src="{{ vasset('images/about/mission-target-dart.jpg') }}" 
                     alt="Aczet Mission - Achievement & Target" 
                     style="width: 100%; height: 280px; object-fit: cover; display: block;"
                     onerror="this.onerror=null; this.src='{{ vasset('images/products/noimage.jpeg') }}';">
            </div>

            <!-- Right Side: Content -->
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                    <span style="display: inline-block; width: 28px; height: 2px; background: #00A3C4;"></span>
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">OUR COMMITMENT</span>
                </div>

                <h2 style="font-size: 2.2rem; font-weight: 800; color: #0F172A; margin: 0 0 10px; font-family: 'Space Grotesk', -apple-system, sans-serif;">
                    Our Mission
                </h2>

                <p style="font-size: 1.22rem; font-weight: 700; color: #00A3C4; margin: 0 0 16px;">
                    Innovate. Expand. Earn Trust.
                </p>

                <p style="font-size: 1.05rem; color: #1E293B; line-height: 1.75; font-weight: 500; margin: 0 0 14px;">
                    To develop advanced products aligned with customer requirements and continuously expand our global presence across markets, industries and regions.
                </p>

                <p style="font-size: 0.98rem; color: #475569; line-height: 1.75; margin: 0 0 24px;">
                    We aim to earn customer confidence and trust by delivering quality solutions, reliable support and becoming a preferred technology partner worldwide.
                </p>

                <!-- Detailed Narrative Blocks -->
                <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 20px;">
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 20px 24px; border-left: 3px solid #00A3C4;">
                        <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 6px;">Delivering Precision Technology Across the World</h4>
                        <p style="font-size: 0.9rem; color: #475569; line-height: 1.65; margin: 0;">
                            Aczet’s journey has extended beyond markets and borders, establishing a strong international presence across more than 65 countries worldwide. Our technologies in precision weighing, analytical instruments, XRF metal analysis and laser solutions are trusted by industries and laboratories across multiple continents.
                        </p>
                    </div>

                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 20px 24px; border-left: 3px solid #00A3C4;">
                        <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 6px;">Strong Global Distribution Network</h4>
                        <p style="font-size: 0.9rem; color: #475569; line-height: 1.65; margin: 0;">
                            Global reach becomes meaningful when it creates local value. Aczet’s international distribution network enables us to effectively serve customers across Asia, Europe, the Middle East, Africa and the Americas with localized expertise, installation services, and responsive technical support.
                        </p>
                    </div>

                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 20px 24px; border-left: 3px solid #00A3C4;">
                        <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 6px;">Our Promise</h4>
                        <p style="font-size: 0.9rem; color: #475569; line-height: 1.65; margin: 0;">
                            From precision engineering to global support, every part of the Aczet journey is built around one purpose: delivering technology that customers can rely on. <strong>Aczet. Precision engineered. Globally trusted.</strong>
                        </p>
                    </div>
                </div>

            </div>
        </section>

        <!-- 5. GUIDING VALUES PILLARS -->
        <section style="margin-top: 10px; padding: 36px 40px; background: #F8FAFC; border-radius: 12px; border: 1px solid #E2E8F0;">
            <div style="text-align: center; max-width: 650px; margin: 0 auto 32px;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: #00A3C4; letter-spacing: 0.1em; display: block; margin-bottom: 6px;">Core Values</span>
                <h3 style="font-size: 1.6rem; font-weight: 800; color: #0F172A; margin: 0; font-family: 'Space Grotesk', sans-serif;">Principles That Guide Everything We Do</h3>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                <div style="background: #FFFFFF; padding: 24px; border-radius: 8px; border: 1px solid #E2E8F0;">
                    <div style="width: 40px; height: 40px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 12px;">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 8px;">Metrological Integrity</h4>
                    <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Measurement truth is non-negotiable. Every specification is verified through strict testing and certified reference standards.</p>
                </div>

                <div style="background: #FFFFFF; padding: 24px; border-radius: 8px; border: 1px solid #E2E8F0;">
                    <div style="width: 40px; height: 40px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 12px;">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 8px;">Continuous Innovation</h4>
                    <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">We invest persistently in R&amp;D to pioneer faster settling times, digital filtration, and intelligent interfaces.</p>
                </div>

                <div style="background: #FFFFFF; padding: 24px; border-radius: 8px; border: 1px solid #E2E8F0;">
                    <div style="width: 40px; height: 40px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 12px;">
                        <i class="fa-solid fa-handshake-angle"></i>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 8px;">Customer Centricity</h4>
                    <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Our engineering begins by listening to customer challenges, providing swift assistance and lifecycle reliability.</p>
                </div>

                <div style="background: #FFFFFF; padding: 24px; border-radius: 8px; border: 1px solid #E2E8F0;">
                    <div style="width: 40px; height: 40px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 12px;">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 8px;">Sustainable Engineering</h4>
                    <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Energy-efficient electronics and recyclable materials ensure our instruments contribute to sustainable operations.</p>
                </div>
            </div>
        </section>

    </div>
</div>

<style>
    @media (max-width: 900px) {
        .about-hero-grid {
            grid-template-columns: 1fr !important;
            gap: 24px !important;
        }
        .hero-vertical-tagline {
            display: none !important;
        }
        .vision-mission-row {
            grid-template-columns: 1fr !important;
            gap: 28px !important;
        }
    }
</style>
@endsection
