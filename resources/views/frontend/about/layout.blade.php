@extends('layouts.app')

@section('content')
<!-- About Us Header Banner with Breadcrumbs -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 14px 0;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li><a href="{{ route('about.index') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">About us</a></li>
                @if(trim($__env->yieldContent('page_title')) !== 'About Us')
                    <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                    <li style="color: #00A3C4; font-weight: 600;">@yield('page_title')</li>
                @endif
            </ol>
        </nav>
    </div>
</div>

<section class="about-hero-clean" style="background: #FFFFFF; position: relative; overflow: hidden; border-bottom: 1px solid #E2E8F0; padding: 36px 0 28px;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto; position: relative; z-index: 2;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 20px; flex-wrap: wrap;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #475569;">ABOUT US</span>
                    <span style="display: inline-block; width: 34px; height: 2px; background: #00A3C4;"></span>
                </div>
                <h1 style="color: #0F172A; font-size: clamp(2rem, 3.8vw, 2.8rem); font-weight: 800; line-height: 1.15; font-family: 'Space Grotesk', sans-serif; margin: 0 0 10px;">
                    @yield('hero_heading')
                </h1>
                <p style="color: #475569; font-size: 1.05rem; max-width: 720px; margin: 0; line-height: 1.6;">
                    @yield('hero_subheading')
                </p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('products.index') }}" class="btn-primary" style="padding: 10px 22px; font-size: 0.88rem; text-decoration: none; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-flask"></i> Explore Instruments
                </a>
                <button type="button" onclick="openQuoteModal('Corporate Inquiry - About Aczet', 0, 'General Information')" class="btn-mt-secondary" style="padding: 10px 22px; font-size: 0.88rem; border-radius: 10px;">
                    <i class="fa-regular fa-envelope"></i> Contact Us
                </button>
            </div>
        </div>
    </div>
</section>

<!-- About Us Main Layout (Left Sidebar + Content Body) -->
<section style="background: #F8FAFC; padding: 48px 0 80px; min-height: 60vh;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 36px; align-items: start;" class="about-grid-layout">
            
            <!-- Left Sidebar Navigation (Matching Reference media_1791430117826_88bf1c8d.png) -->
            <aside class="about-sidebar">
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.03); position: sticky; top: 100px;">
                    <div style="padding: 16px 20px; background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%); color: #FFFFFF; border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: #E0F2FE; font-weight: 800; display: block; margin-bottom: 2px;">Company Navigation</span>
                        <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: #FFFFFF; font-family: 'Space Grotesk', sans-serif;">About Aczet</h3>
                    </div>

                    <ul style="list-style: none; padding: 6px 0; margin: 0;">
                        <li>
                            <a href="{{ route('about.index') }}" class="about-nav-link {{ request()->routeIs('about.index') ? 'active' : '' }}">
                                <i class="fa-solid fa-building"></i>
                                <span>About Us</span>
                                <i class="fa-solid fa-chevron-right arrow-indicator"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.technology') }}" class="about-nav-link {{ request()->routeIs('about.technology') ? 'active' : '' }}">
                                <i class="fa-solid fa-microchip"></i>
                                <span>Our Technology</span>
                                <i class="fa-solid fa-chevron-right arrow-indicator"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.vision_mission') }}" class="about-nav-link {{ request()->routeIs('about.vision_mission') ? 'active' : '' }}">
                                <i class="fa-solid fa-compass"></i>
                                <span>Vision &amp; Mission</span>
                                <i class="fa-solid fa-chevron-right arrow-indicator"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.global_presence') }}" class="about-nav-link {{ request()->routeIs('about.global_presence') ? 'active' : '' }}">
                                <i class="fa-solid fa-globe"></i>
                                <span>Global Presence</span>
                                <i class="fa-solid fa-chevron-right arrow-indicator"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.management') }}" class="about-nav-link {{ request()->routeIs('about.management') ? 'active' : '' }}">
                                <i class="fa-solid fa-users-gear"></i>
                                <span>Management</span>
                                <i class="fa-solid fa-chevron-right arrow-indicator"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.history') }}" class="about-nav-link {{ request()->routeIs('about.history') ? 'active' : '' }}">
                                <i class="fa-solid fa-timeline"></i>
                                <span>Our History</span>
                                <i class="fa-solid fa-chevron-right arrow-indicator"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.why_choose_aczet') }}" class="about-nav-link {{ request()->routeIs('about.why_choose_aczet') ? 'active' : '' }}">
                                <i class="fa-solid fa-award"></i>
                                <span>Why Choose Aczet</span>
                                <i class="fa-solid fa-chevron-right arrow-indicator"></i>
                            </a>
                        </li>
                    </ul>

                    <!-- Sidebar Trust Card -->
                    <div style="margin: 16px; padding: 18px; background: #F8FAFC; border-radius: 8px; border-left: 3px solid #00A3C4;">
                        <h4 style="font-size: 0.88rem; font-weight: 700; color: #0F172A; margin: 0 0 6px;">{{ site_setting('about_trust_card_title', 'ISO 17025 Calibrated') }}</h4>
                        <p style="font-size: 0.78rem; color: #64748B; margin: 0 0 12px; line-height: 1.5;">{{ site_setting('about_trust_card_desc', 'All Aczet weighing and metrology systems ship with factory calibration traceable to national & international standards.') }}</p>
                        <a href="{{ route('technical_publications') }}" style="font-size: 0.78rem; font-weight: 700; color: #00A3C4; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            {{ site_setting('about_trust_card_btn', 'View Accreditations →') }}
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Main Content Pane -->
            <main class="about-main-body">
                @yield('about_content')
            </main>

        </div>
    </div>
</section>

<style>
    .about-nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 20px;
        color: #334155;
        font-size: 0.92rem;
        font-weight: 600;
        text-decoration: none;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
    }
    .about-nav-link i:first-child {
        width: 18px;
        color: #64748B;
        transition: color 0.2s ease;
    }
    .about-nav-link .arrow-indicator {
        margin-left: auto;
        font-size: 0.75rem;
        color: #94A3B8;
        opacity: 0;
        transform: translateX(-4px);
        transition: all 0.2s ease;
    }
    .about-nav-link:hover {
        background: #F1F5F9;
        color: #00A3C4;
    }
    .about-nav-link:hover i:first-child {
        color: #00A3C4;
    }
    .about-nav-link:hover .arrow-indicator {
        opacity: 1;
        transform: translateX(0);
    }
    .about-nav-link.active {
        background: #E6F7FA !important;
        border-left-color: #00A3C4 !important;
        color: #00A3C4 !important;
        font-weight: 700 !important;
    }
    .about-nav-link.active i:first-child {
        color: #00A3C4 !important;
    }
    .about-nav-link.active .arrow-indicator {
        opacity: 1;
        color: #00A3C4;
        transform: translateX(0);
    }

    @media (max-width: 960px) {
        .about-grid-layout {
            grid-template-columns: 1fr !important;
        }
        .about-sidebar {
            position: static;
        }
    }
</style>
@endsection
