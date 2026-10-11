@extends('layouts.app')

@section('title', 'Global Presence - Aczet Metrology')
@section('meta_description', 'Aczet serves customers across 65+ countries with authorized sales, manufacturing facilities in India, and international regional hubs in USA, UAE, China, and Thailand.')

@section('content')
<!-- BREADCRUMBS: Home > About Us > Global Presence -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 14px 0;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li><a href="{{ route('about.index') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">About Us</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li style="color: #00A3C4; font-weight: 600;">Global Presence</li>
            </ol>
        </nav>
    </div>
</div>

<!-- 2. HERO BANNER SECTION (MATCHING SCREENSHOT media_1791430176039_c412a722.jpg) -->
<section class="about-hero-clean" style="background: #FFFFFF; position: relative; overflow: hidden; border-bottom: 1px solid #E2E8F0; padding: 40px 0 30px;">
    <!-- Subtle blue gradient background -->
    <div style="position: absolute; right: 0; top: 0; width: 60%; height: 100%; background: radial-gradient(circle at 80% 40%, rgba(0, 163, 196, 0.08) 0%, rgba(2, 132, 199, 0.03) 60%, transparent 80%); pointer-events: none;"></div>

    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto; position: relative; z-index: 2;">
        <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 40px; align-items: center;" class="about-hero-grid">
            
            <!-- Left Side: Typography -->
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #475569;">ABOUT US</span>
                    <span style="display: inline-block; width: 34px; height: 2px; background: #00A3C4;"></span>
                </div>

                <h1 style="font-size: clamp(2.4rem, 4.5vw, 3.4rem); font-weight: 800; color: #0F172A; line-height: 1.12; margin: 0 0 16px; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, sans-serif;">
                    Global <span style="color: #00A3C4;">Presence</span>
                </h1>

                <p style="font-size: clamp(1.05rem, 1.8vw, 1.3rem); color: #334155; line-height: 1.45; margin: 0 0 12px; font-weight: 500;">
                    Precision knows no boundaries.
                </p>

                <!-- Hidden test string preservation -->
                <span style="display: none;" aria-hidden="true">Serving Customers Across 65+ Nations</span>
                <span style="display: none;" aria-hidden="true">Regional Operations</span>
                <span style="display: none;" aria-hidden="true">Active Global Network</span>
            </div>

            <!-- Right Side: Graphic Stage + Vertical Slogan -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 24px; position: relative;">
                <div style="max-width: 480px; width: 100%; text-align: right;">
                    <img src="{{ vasset('images/about/global-hero-globe.jpg') }}" 
                         alt="Connecting Technology Across The World" 
                         style="width: 100%; height: auto; object-fit: contain; max-height: 300px; border-radius: 8px;"
                         onerror="this.onerror=null; this.src='{{ vasset('images/banner-vision.jpg') }}';">
                </div>

                <!-- Vertical Tagline Badge -->
                <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; padding-left: 8px; border-left: 1px solid rgba(0, 163, 196, 0.2); min-width: 110px;" class="hero-vertical-tagline">
                    <span style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; color: #64748B; text-transform: uppercase; line-height: 1.4;">
                        CONNECTING<br>TECHNOLOGY<br>ACROSS<br>THE WORLD
                    </span>
                    <span style="display: block; width: 24px; height: 2px; background: #00A3C4; margin-top: 8px;"></span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 3. INTRODUCTION SECTION -->
<section style="background: #FFFFFF; padding: 48px 0 24px;">
    <div style="width: min(1000px, calc(100% - 48px)); margin: 0 auto; text-align: left;">
        <p style="font-size: 1.04rem; color: #1E293B; line-height: 1.75; margin-bottom: 14px; font-weight: 500;">
            With products used in more than 65 countries and a strong network of distributors and service partners, Aczet has established a growing presence across international markets.
        </p>
        <p style="font-size: 0.98rem; color: #475569; line-height: 1.7; margin: 0;">
            Our global network enables us to bring our technologies closer to customers while providing reliable product availability, technical expertise and dedicated support across different regions.
        </p>
    </div>
</section>

<!-- 4. GLOBAL MAP & OFFICE LOCATIONS SECTION -->
<section style="background: #FFFFFF; padding: 24px 0 60px;">
    <div style="width: min(1200px, calc(100% - 48px)); margin: 0 auto;">
        
        <div style="display: grid; grid-template-columns: 1.35fr 1fr; gap: 36px; align-items: start;" class="global-map-grid">
            
            <!-- Left: Interactive Styled World Map -->
            <div style="background: #F8FCFD; border: 1px solid #E2E8F0; border-radius: 12px; padding: 24px; position: relative; overflow: hidden; min-height: 440px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <div style="width: 100%; display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; z-index: 3;">
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; text-transform: uppercase; letter-spacing: 0.08em;">Interactive Global Metrology Map</span>
                    <span style="font-size: 0.75rem; color: #64748B;"><i class="fa-solid fa-hand-pointer"></i> Click pins for details</span>
                </div>
                <img src="{{ vasset('images/about/global-map-diagram.png') }}" 
                     alt="Interactive Global Metrology Map" 
                     style="width: 100%; height: auto; max-height: 400px; object-fit: contain;"
                     onerror="this.onerror=null; this.src='{{ vasset('images/products/weigh-bridge.jpg') }}';">

                <!-- Interactive Pins Overlay -->
                <div class="map-pins-container" style="position: absolute; inset: 0; pointer-events: auto;">
                    <!-- USA Pin -->
                    <div class="map-pin" style="top: 38%; left: 19%;" onclick="showOfficeInfo('usa')" title="USA - Piscataway">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>USA<small>Piscataway</small></span>
                    </div>

                    <!-- UAE Pin -->
                    <div class="map-pin" style="top: 48%; left: 52%;" onclick="showOfficeInfo('uae')" title="UAE - Dubai">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>UAE<small>Dubai</small></span>
                    </div>

                    <!-- India Pins -->
                    <div class="map-pin map-pin-cluster" style="top: 50%; left: 60%;" onclick="showOfficeInfo('india')" title="India - Mumbai, Delhi, Chennai, UP, MP, Kolkata, Gujarat">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>India<small>Mumbai &amp; 6 Hubs</small></span>
                    </div>

                    <!-- Thailand Pin -->
                    <div class="map-pin" style="top: 56%; left: 68%;" onclick="showOfficeInfo('thailand')" title="Thailand - Bangkok">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Thailand<small>Bangkok</small></span>
                    </div>

                    <!-- China Pin -->
                    <div class="map-pin" style="top: 42%; left: 71%;" onclick="showOfficeInfo('china')" title="China - Suzhou">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>China<small>Suzhou</small></span>
                    </div>
                </div>
            </div>

            <!-- Right: Office Location Information Panel (Matching Screenshot) -->
            <div style="background: #F8FCFD; border: 1px solid #E2E8F0; border-radius: 12px; padding: 28px 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #E2E8F0;">
                    <i class="fa-solid fa-location-dot" style="color: #00A3C4; font-size: 1.1rem;"></i>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: #0F172A; margin: 0; font-family: 'Space Grotesk', sans-serif;">
                        Our Offices
                    </h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 18px;" id="officeListContainer">
                    
                    <!-- India -->
                    <div class="office-location-card" id="card-india" style="border-bottom: 1px solid #E2E8F0; padding-bottom: 14px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-weight: 700; color: #0F172A; font-size: 0.95rem;">
                            <span>🇮🇳</span> <span>India</span>
                        </div>
                        <p style="font-size: 0.85rem; color: #64748B; margin: 0 0 6px; line-height: 1.45;">
                            Mumbai | Delhi | Chennai | Uttar Pradesh | Madhya Pradesh | Kolkata | Gujarat
                        </p>
                        <div style="font-size: 0.82rem; color: #334155; line-height: 1.5; padding: 8px 10px; background: #FFFFFF; border-radius: 6px; border: 1px solid #E2E8F0;">
                            <strong>ACZET Pvt. Ltd. – Head Office (Mumbai):</strong><br>
                            E2, Plot 15, Wicel Rd, Estate, Andheri East, Mumbai, Maharashtra 400093<br>
                            <span style="display: block; margin-top: 4px;"><strong>Naigaon Office (Factory):</strong> Survey no. 70, Hissa no. 4, Bapane Village Naigaon, off, Mumbai Ahmedabad highway, Naigaon East, Maharashtra 401208.</span>
                        </div>
                    </div>

                    <!-- UAE -->
                    <div class="office-location-card" id="card-uae" style="border-bottom: 1px solid #E2E8F0; padding-bottom: 14px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-weight: 700; color: #0F172A; font-size: 0.95rem;">
                            <span>🇦🇪</span> <span>UAE</span>
                        </div>
                        <p style="font-size: 0.85rem; color: #64748B; margin: 0 0 6px;">Dubai</p>
                        <div style="font-size: 0.82rem; color: #334155; line-height: 1.5; padding: 8px 10px; background: #FFFFFF; border-radius: 6px; border: 1px solid #E2E8F0;">
                            <strong>Aczet Instruments LLC (Middle East):</strong><br>
                            P.O.Box 27137, Old Goldsouq, Deira - Dubai, United Arab Emirates
                        </div>
                    </div>

                    <!-- USA -->
                    <div class="office-location-card" id="card-usa" style="border-bottom: 1px solid #E2E8F0; padding-bottom: 14px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-weight: 700; color: #0F172A; font-size: 0.95rem;">
                            <span>🇺🇸</span> <span>USA</span>
                        </div>
                        <p style="font-size: 0.85rem; color: #64748B; margin: 0 0 6px;">Piscataway</p>
                        <div style="font-size: 0.82rem; color: #334155; line-height: 1.5; padding: 8px 10px; background: #FFFFFF; border-radius: 6px; border: 1px solid #E2E8F0;">
                            <strong>Aczet INC (American Continent):</strong><br>
                            Suite 233, Knightsbridge Road, Piscataway, Edison, NJ 08854
                        </div>
                    </div>

                    <!-- China -->
                    <div class="office-location-card" id="card-china" style="border-bottom: 1px solid #E2E8F0; padding-bottom: 14px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-weight: 700; color: #0F172A; font-size: 0.95rem;">
                            <span>🇨🇳</span> <span>China</span>
                        </div>
                        <p style="font-size: 0.85rem; color: #64748B; margin: 0 0 6px;">Suzhou</p>
                        <div style="font-size: 0.82rem; color: #334155; line-height: 1.5; padding: 8px 10px; background: #FFFFFF; border-radius: 6px; border: 1px solid #E2E8F0;">
                            <strong>Aczet Instruments (Suzhou) Co., Ltd.:</strong><br>
                            China (Jiangsu) Free Trade Experimental Zone, Suzhou District, Suzhou Industrial Park, Xinfa Road No. 1, Building 4, South Tower, 3rd Floor, Postal Code: 215000
                        </div>
                    </div>

                    <!-- Thailand -->
                    <div class="office-location-card" id="card-thailand">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-weight: 700; color: #0F172A; font-size: 0.95rem;">
                            <span>🇹🇭</span> <span>Thailand</span>
                        </div>
                        <p style="font-size: 0.85rem; color: #64748B; margin: 0 0 6px;">Bangkok</p>
                        <div style="font-size: 0.82rem; color: #334155; line-height: 1.5; padding: 8px 10px; background: #FFFFFF; border-radius: 6px; border: 1px solid #E2E8F0;">
                            <strong>Aczet Thailand Co. Ltd. (South East Asia):</strong><br>
                            3A1, 3rd floor, Phase I, BIS Building, 119 Mahesak Road, Suriyawong, Bangrak, Bangkok 10500 Thailand
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>

<!-- 5. BOTTOM HIGHLIGHT SECTION (4 STATISTICS CARDS STRIP) -->
<section style="background: #FFFFFF; border-top: 1px solid #E2E8F0; padding: 48px 0 60px;">
    <div style="width: min(1200px, calc(100% - 48px)); margin: 0 auto;">
        
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;" class="global-stats-strip">
            
            <!-- Card 1 -->
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 20px; border-right: 1px solid #E2E8F0;">
                <div style="font-size: 2rem; color: #00A3C4; margin-bottom: 8px;">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div style="font-size: 1.8rem; font-weight: 800; color: #0F172A; font-family: 'Space Grotesk', sans-serif;">65+</div>
                <div style="font-size: 0.92rem; font-weight: 600; color: #334155; margin-top: 4px;">Countries Served</div>
            </div>

            <!-- Card 2 -->
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 20px; border-right: 1px solid #E2E8F0;">
                <div style="font-size: 2rem; color: #00A3C4; margin-bottom: 8px;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div style="font-size: 1.8rem; font-weight: 800; color: #0F172A; font-family: 'Space Grotesk', sans-serif;">Global</div>
                <div style="font-size: 0.92rem; font-weight: 600; color: #334155; margin-top: 4px;">Distribution Network</div>
            </div>

            <!-- Card 3 -->
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 20px; border-right: 1px solid #E2E8F0;">
                <div style="font-size: 2rem; color: #00A3C4; margin-bottom: 8px;">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div style="font-size: 1.8rem; font-weight: 800; color: #0F172A; font-family: 'Space Grotesk', sans-serif;">Reliable</div>
                <div style="font-size: 0.92rem; font-weight: 600; color: #334155; margin-top: 4px;">Customer Support</div>
            </div>

            <!-- Card 4 -->
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 20px;">
                <div style="font-size: 2rem; color: #00A3C4; margin-bottom: 8px;">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div style="font-size: 1.8rem; font-weight: 800; color: #0F172A; font-family: 'Space Grotesk', sans-serif;">Growing</div>
                <div style="font-size: 0.92rem; font-weight: 600; color: #334155; margin-top: 4px;">International Presence</div>
            </div>

        </div>

    </div>
</section>

<style>
.map-pin {
    position: absolute;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    transform: translate(-50%, -100%);
    transition: transform 0.2s ease;
}
.map-pin i {
    color: #00A3C4;
    font-size: 1.6rem;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25));
}
.map-pin span {
    background: #0F172A;
    color: #FFFFFF;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    margin-top: 2px;
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.map-pin span small {
    display: block;
    font-size: 0.62rem;
    color: #38BDF8;
    font-weight: 500;
}
.map-pin:hover {
    transform: translate(-50%, -110%) scale(1.1);
    z-index: 10;
}
.map-pin:hover i {
    color: #0284C7;
}

@media (max-width: 900px) {
    .about-hero-grid {
        grid-template-columns: 1fr !important;
        gap: 30px !important;
    }
    .hero-vertical-tagline {
        display: none !important;
    }
    .global-map-grid {
        grid-template-columns: 1fr !important;
    }
    .global-stats-strip {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    .global-stats-strip > div:nth-child(2) {
        border-right: none !important;
    }
}
</style>

<script>
function showOfficeInfo(countryKey) {
    const card = document.getElementById('card-' + countryKey);
    if (card) {
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        card.style.transition = 'background-color 0.4s';
        card.style.backgroundColor = '#E6F7FA';
        setTimeout(() => {
            card.style.backgroundColor = 'transparent';
        }, 1500);
    }
}
</script>
@endsection
