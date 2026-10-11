@extends('layouts.app')

@section('title', 'Management - Aczet Leadership & Corporate Governance')
@section('meta_description', 'Meet the executive leadership team guiding Aczet in precision weighing, analytical instrumentation, XRF analysis, and laser metrology innovation.')

@section('content')
<!-- 1. BREADCRUMBS: Home > About us > Management -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 14px 0;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li><a href="{{ route('about.index') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">About us</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li style="color: #00A3C4; font-weight: 600;">Management</li>
            </ol>
        </nav>
    </div>
</div>

<!-- 2. HERO BANNER SECTION (MATCHING SCREENSHOT media_1791430232136_2ae38035.jpg) -->
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
                    Manage<span style="color: #00A3C4;">ment</span>
                </h1>

                <p style="font-size: clamp(1.1rem, 2vw, 1.35rem); color: #00A3C4; font-weight: 700; line-height: 1.4; margin: 0 0 12px;">
                    The Vision. The Legacy. The People Behind Aczet.
                </p>

                <p style="font-size: 0.98rem; color: #334155; line-height: 1.7; margin: 0 0 12px; max-width: 580px;">
                    Every enduring organization begins with a vision and at Aczet, that vision began with Mr. Chandrakant Shah in 1986.
                </p>

                <p style="font-size: 0.94rem; color: #64748B; line-height: 1.65; margin: 0; max-width: 580px;">
                    What started with mechanical weighing scales has evolved into a technology-driven organization spanning weighing, XRF and laser solutions, with an expanding presence across India and international markets.
                </p>
            </div>

            <!-- Right Side: Leadership Team Photo + Vertical Slogan -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 24px; position: relative;">
                <div style="max-width: 460px; width: 100%; text-align: right; position: relative;">
                    <img src="{{ vasset('images/about/mgmt-hero-team.jpg') }}" 
                         alt="Aczet Executive Leadership" 
                         style="width: 100%; height: auto; object-fit: contain; max-height: 300px; border-radius: 8px;"
                         onerror="this.onerror=null; this.src='{{ vasset('images/products/noimage.jpeg') }}';">
                    
                    <div style="position: absolute; bottom: 12px; left: 12px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); padding: 6px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.12); color: #FFFFFF; font-size: 0.76rem; font-weight: 700; letter-spacing: 0.05em;">
                        1986 – Present
                    </div>
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

<!-- 3. THE JOURNEY SO FAR - TIMELINE STRIP (MATCHING SCREENSHOT) -->
<section style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 32px 0;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 24px; flex-wrap: wrap;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #00A3C4; display: block; margin-bottom: 4px;">MILESTONES</span>
                <h3 style="font-size: 1.35rem; font-weight: 800; color: #0F172A; margin: 0; font-family: 'Space Grotesk', sans-serif;">The Journey So Far</h3>
            </div>
            <span style="font-size: 0.85rem; color: #64748B; font-weight: 500;">Guided by Excellence and Integrity</span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 18px 20px; border-left: 3px solid #00A3C4;">
                <span style="font-size: 1.25rem; font-weight: 800; color: #00A3C4; display: block; margin-bottom: 4px; font-family: 'Space Grotesk', sans-serif;">1986</span>
                <h5 style="font-size: 0.9rem; font-weight: 700; color: #0F172A; margin: 0 0 4px;">Inception</h5>
                <p style="font-size: 0.8rem; color: #64748B; margin: 0; line-height: 1.5;">Founded with mechanical weighing scales.</p>
            </div>

            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 18px 20px; border-left: 3px solid #00A3C4;">
                <span style="font-size: 1.25rem; font-weight: 800; color: #00A3C4; display: block; margin-bottom: 4px; font-family: 'Space Grotesk', sans-serif;">1990s</span>
                <h5 style="font-size: 0.9rem; font-weight: 700; color: #0F172A; margin: 0 0 4px;">Electronic Precision</h5>
                <p style="font-size: 0.8rem; color: #64748B; margin: 0; line-height: 1.5;">Electronic balances and digital integration.</p>
            </div>

            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 18px 20px; border-left: 3px solid #00A3C4;">
                <span style="font-size: 1.25rem; font-weight: 800; color: #00A3C4; display: block; margin-bottom: 4px; font-family: 'Space Grotesk', sans-serif;">2000s</span>
                <h5 style="font-size: 0.9rem; font-weight: 700; color: #0F172A; margin: 0 0 4px;">Technology Leap</h5>
                <p style="font-size: 0.8rem; color: #64748B; margin: 0; line-height: 1.5;">Expansion into XRF analyzers &amp; laser systems.</p>
            </div>

            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 18px 20px; border-left: 3px solid #00A3C4;">
                <span style="font-size: 1.25rem; font-weight: 800; color: #00A3C4; display: block; margin-bottom: 4px; font-family: 'Space Grotesk', sans-serif;">2010s</span>
                <h5 style="font-size: 0.9rem; font-weight: 700; color: #0F172A; margin: 0 0 4px;">Global Footprint</h5>
                <p style="font-size: 0.8rem; color: #64748B; margin: 0; line-height: 1.5;">International operations in 65+ nations.</p>
            </div>

            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 18px 20px; border-left: 3px solid #00A3C4;">
                <span style="font-size: 1.25rem; font-weight: 800; color: #00A3C4; display: block; margin-bottom: 4px; font-family: 'Space Grotesk', sans-serif;">Today</span>
                <h5 style="font-size: 0.9rem; font-weight: 700; color: #0F172A; margin: 0 0 4px;">Metrology Leader</h5>
                <p style="font-size: 0.8rem; color: #64748B; margin: 0; line-height: 1.5;">Next-gen smart balances and Industry 4.0.</p>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT WRAPPER -->
<div style="background: #FFFFFF; padding: 60px 0 80px;">
    <div style="width: min(1120px, calc(100% - 48px)); margin: 0 auto; display: flex; flex-direction: column; gap: 60px;">

        <!-- 4. QUICK LEADERSHIP OVERVIEW (3 COLUMNS FROM SCREENSHOT) -->
        <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 22px 24px; border-top: 3px solid #00A3C4;">
                <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #00A3C4; display: block; margin-bottom: 6px;">DIRECTOR</span>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0 0 4px;">SANJAY</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; font-weight: 500;">Industrial &amp; Laboratory Products, Corporate Strategy &amp; Global Operations</p>
            </div>

            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 22px 24px; border-top: 3px solid #00A3C4;">
                <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #00A3C4; display: block; margin-bottom: 6px;">DIRECTOR</span>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0 0 4px;">HEMAL</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; font-weight: 500;">Jewellery &amp; Retail Solutions, XRF &amp; Laser Technology, Corporate Finance</p>
            </div>

            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 22px 24px; border-top: 3px solid #00A3C4;">
                <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #00A3C4; display: block; margin-bottom: 6px;">DIRECTOR</span>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0 0 4px;">VIRAL</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; font-weight: 500;">Research &amp; Development, Production Infrastructure, Global Exports (70+ Countries)</p>
            </div>
        </section>

        <!-- 5. FOUNDER & CHAIRMAN SECTION (MATCHING SCREENSHOT) -->
        <section style="display: grid; grid-template-columns: 320px 1fr; gap: 40px; align-items: center; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 36px 40px;" class="founder-section-row">
            <!-- Left Side: Founder Visual / Avatar Card -->
            <div style="text-align: center;">
                <div style="width: 180px; height: 180px; margin: 0 auto 16px; border-radius: 50%; background: linear-gradient(135deg, rgba(0, 163, 196, 0.15) 0%, rgba(15, 23, 42, 0.08) 100%); border: 3px solid #00A3C4; display: flex; align-items: center; justify-content: center; color: #00A3C4; font-size: 4rem; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 163, 196, 0.15);">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <h4 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">Mr. Chandrakant Shah</h4>
                <span style="font-size: 0.8rem; font-weight: 800; color: #00A3C4; text-transform: uppercase; letter-spacing: 0.1em; display: block;">CHAIRMAN &amp; FOUNDER</span>
            </div>

            <!-- Right Side: Content -->
            <div>
                <div style="display: inline-block; background: rgba(0, 163, 196, 0.1); border-left: 3px solid #00A3C4; padding: 8px 16px; border-radius: 0 6px 6px 0; margin-bottom: 16px;">
                    <p style="margin: 0; font-size: 1.05rem; font-style: italic; font-weight: 700; color: #0F172A;">
                        "Impossible is not a word in his dictionary."
                    </p>
                </div>

                <p style="font-size: 0.98rem; color: #334155; line-height: 1.75; margin-bottom: 14px;">
                    The story of Aczet begins with Mr. Chandrakant Shah, whose journey in the weighing industry started with mechanical scales in 1986.
                </p>

                <p style="font-size: 0.95rem; color: #475569; line-height: 1.7; margin-bottom: 14px;">
                    With an entrepreneurial vision and strong understanding of the market, he expanded the business into Jewellery and Retail sectors, laying the foundation for Aczet's growth. His philosophy has always centred around perseverance, customer trust and long-term growth.
                </p>

                <p style="font-size: 0.95rem; color: #475569; line-height: 1.7; margin: 0;">
                    As Chairman, he continues to guide Aczet's strategic direction with a vision to strengthen the company's global presence and take Indian precision technology to markets worldwide.
                </p>
            </div>
        </section>

        <!-- 6. MANAGEMENT TEAM SECTION (3 DETAILED DIRECTORS WITH 01, 02, 03 WATERMARKS) -->
        <section>
            <div style="text-align: left; margin-bottom: 32px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <span style="display: inline-block; width: 28px; height: 2px; background: #00A3C4;"></span>
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">LEADERSHIP DIRECTORS</span>
                </div>
                <h3 style="font-size: 1.8rem; font-weight: 800; color: #0F172A; margin: 0; font-family: 'Space Grotesk', sans-serif;">Executive Leadership</h3>
            </div>

            <div style="display: flex; flex-direction: column; gap: 32px;">
                
                <!-- Director 1: Mr. Sanjay Shah (01) -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 36px 40px; position: relative; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.02); display: grid; grid-template-columns: 240px 1fr; gap: 36px; align-items: center;" class="director-card-grid">
                    <!-- Watermark number -->
                    <span style="position: absolute; right: 24px; top: 12px; font-size: 6rem; font-weight: 900; color: rgba(0, 163, 196, 0.05); font-family: 'Space Grotesk', sans-serif; pointer-events: none; line-height: 1;">01</span>
                    
                    <!-- Photo -->
                    <div style="text-align: center;">
                        <img src="{{ vasset('images/about/mgmt-sanjay-shah.jpg') }}" 
                             alt="Mr. Sanjay Shah" 
                             style="width: 170px; height: 210px; object-fit: cover; border-radius: 8px; border: 1px solid #E2E8F0; box-shadow: 0 4px 12px rgba(0,0,0,0.06); display: block; margin: 0 auto 12px;"
                             onerror="this.onerror=null; this.src='{{ vasset('images/products/noimage.jpeg') }}';">
                        <h4 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0 0 2px;">Mr. Sanjay Shah</h4>
                        <span style="font-size: 0.76rem; font-weight: 700; color: #00A3C4; text-transform: uppercase; letter-spacing: 0.08em;">DIRECTOR</span>
                    </div>

                    <!-- Details -->
                    <div>
                        <div style="display: inline-block; background: rgba(0, 163, 196, 0.08); border-left: 3px solid #00A3C4; padding: 6px 14px; border-radius: 0 4px 4px 0; margin-bottom: 14px;">
                            <p style="margin: 0; font-size: 0.95rem; font-style: italic; font-weight: 600; color: #0F172A;">
                                "Innovation and operational excellence drive sustainable growth."
                            </p>
                        </div>

                        <p style="font-size: 0.94rem; color: #334155; line-height: 1.7; margin-bottom: 12px;">
                            Joining Aczet in 1992 after completing his engineering in Digital Electronics, Mr. Sanjay Shah has played an important role in the company's growth for more than three decades. With technical expertise and business understanding, he leads Industrial and Laboratory product sectors while supporting global operations.
                        </p>

                        <div style="background: #F8FAFC; border-radius: 8px; padding: 12px 16px; border: 1px solid #E2E8F0;">
                            <span style="font-size: 0.78rem; font-weight: 700; color: #00A3C4; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 6px;">Key Focus Areas:</span>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap; font-size: 0.85rem; color: #475569;">
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> Industrial Business Growth</span>
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> International Partnerships</span>
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> Corporate Operations</span>
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> Government Projects</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Director 2: Mr. Viral Shah (02) -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 36px 40px; position: relative; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.02); display: grid; grid-template-columns: 240px 1fr; gap: 36px; align-items: center;" class="director-card-grid">
                    <!-- Watermark number -->
                    <span style="position: absolute; right: 24px; top: 12px; font-size: 6rem; font-weight: 900; color: rgba(0, 163, 196, 0.05); font-family: 'Space Grotesk', sans-serif; pointer-events: none; line-height: 1;">02</span>
                    
                    <!-- Portrait / Icon Box -->
                    <div style="text-align: center;">
                        <div style="width: 170px; height: 210px; margin: 0 auto 12px; border-radius: 8px; background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; color: #00A3C4; border: 1px solid #334155;">
                            <i class="fa-solid fa-flask-vial" style="font-size: 3rem; margin-bottom: 8px;"></i>
                            <span style="font-size: 0.72rem; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.08em;">R&amp;D &amp; Production</span>
                        </div>
                        <h4 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0 0 2px;">Mr. Viral Shah</h4>
                        <span style="font-size: 0.76rem; font-weight: 700; color: #00A3C4; text-transform: uppercase; letter-spacing: 0.08em;">DIRECTOR</span>
                    </div>

                    <!-- Details -->
                    <div>
                        <div style="display: inline-block; background: rgba(0, 163, 196, 0.08); border-left: 3px solid #00A3C4; padding: 6px 14px; border-radius: 0 4px 4px 0; margin-bottom: 14px;">
                            <p style="margin: 0; font-size: 0.95rem; font-style: italic; font-weight: 600; color: #0F172A;">
                                "Innovation begins with understanding technology. Growth begins with taking it to the world."
                            </p>
                        </div>

                        <p style="font-size: 0.94rem; color: #334155; line-height: 1.7; margin-bottom: 12px;">
                            Mr. Viral Shah leads three key pillars of Aczet: Exports, Research &amp; Development, and Production infrastructure. He works closely with engineering and manufacturing teams to develop new metrological technologies, improve plant productivity and ensure uncompromising global quality standards.
                        </p>

                        <p style="font-size: 0.92rem; color: #475569; line-height: 1.65; margin-bottom: 14px;">
                            Under his leadership, Aczet has expanded its global presence with products available across 70+ countries, combining continuous R&amp;D with manufacturing excellence to take Indian precision technology to the world.
                        </p>

                        <div style="background: #F8FAFC; border-radius: 8px; padding: 12px 16px; border: 1px solid #E2E8F0;">
                            <span style="font-size: 0.78rem; font-weight: 700; color: #00A3C4; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 6px;">Key Responsibilities:</span>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap; font-size: 0.85rem; color: #475569;">
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> Global Exports (70+ Nations)</span>
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> Research &amp; Development</span>
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> Advanced Production Systems</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Director 3: Mr. Hemal Shah (03) -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 36px 40px; position: relative; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.02); display: grid; grid-template-columns: 240px 1fr; gap: 36px; align-items: center;" class="director-card-grid">
                    <!-- Watermark number -->
                    <span style="position: absolute; right: 24px; top: 12px; font-size: 6rem; font-weight: 900; color: rgba(0, 163, 196, 0.05); font-family: 'Space Grotesk', sans-serif; pointer-events: none; line-height: 1;">03</span>
                    
                    <!-- Portrait / Icon Box -->
                    <div style="text-align: center;">
                        <div style="width: 170px; height: 210px; margin: 0 auto 12px; border-radius: 8px; background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; color: #00A3C4; border: 1px solid #334155;">
                            <i class="fa-solid fa-gem" style="font-size: 3rem; margin-bottom: 8px;"></i>
                            <span style="font-size: 0.72rem; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.08em;">Jewellery &amp; Finance</span>
                        </div>
                        <h4 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin: 0 0 2px;">Mr. Hemal Shah</h4>
                        <span style="font-size: 0.76rem; font-weight: 700; color: #00A3C4; text-transform: uppercase; letter-spacing: 0.08em;">DIRECTOR</span>
                    </div>

                    <!-- Details -->
                    <div>
                        <div style="display: inline-block; background: rgba(0, 163, 196, 0.08); border-left: 3px solid #00A3C4; padding: 6px 14px; border-radius: 0 4px 4px 0; margin-bottom: 14px;">
                            <p style="margin: 0; font-size: 0.95rem; font-style: italic; font-weight: 600; color: #0F172A;">
                                "Expanding possibilities through technology, innovation and customer understanding."
                            </p>
                        </div>

                        <p style="font-size: 0.94rem; color: #334155; line-height: 1.7; margin-bottom: 12px;">
                            Mr. Hemal Shah has played a significant role in developing Aczet's presence in the Jewellery and Retail sectors. His keen understanding of jewellery industry requirements helped Aczet evolve from a weighing solutions provider into a complete technology partner, introducing XRF Gold Testing and Laser Systems.
                        </p>

                        <div style="background: #F8FAFC; border-radius: 8px; padding: 12px 16px; border: 1px solid #E2E8F0;">
                            <span style="font-size: 0.78rem; font-weight: 700; color: #00A3C4; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 6px;">Pioneered Technologies &amp; Oversight:</span>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap; font-size: 0.85rem; color: #475569;">
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> XRF Gold Testing Technology</span>
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> Laser Welding Solutions</span>
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> Laser Marking Technology</span>
                                <span style="background: #FFFFFF; padding: 4px 10px; border-radius: 4px; border: 1px solid #E2E8F0;"><i class="fa-solid fa-check" style="color: #00A3C4; margin-right: 4px;"></i> Corporate Financial Operations</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- 7. LEADERSHIP PHILOSOPHY SECTION (MATCHING SPECIFICATION & TEST ASSERTIONS) -->
        <section style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 40px 48px; text-align: center;">
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <span style="display: inline-block; width: 20px; height: 2px; background: #00A3C4;"></span>
                    <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">Executive Philosophy</span>
                    <span style="display: inline-block; width: 20px; height: 2px; background: #00A3C4;"></span>
                </div>

                <h3 style="font-size: 1.85rem; font-weight: 800; color: #0F172A; margin: 0 0 14px; font-family: 'Space Grotesk', sans-serif;">
                    One Vision. Many Areas of Expertise.
                </h3>

                <p style="font-size: 1.05rem; color: #334155; line-height: 1.7; font-weight: 500; margin: 0 0 12px;">
                    Four leaders. Different responsibilities. One shared direction. Guided by excellence and integrity in every technological breakthrough.
                </p>

                <p style="font-size: 0.95rem; color: #64748B; line-height: 1.7; margin: 0 0 20px;">
                    From the foundation established in 1986 to today's technology-driven global presence, Aczet's leadership continues to build on a legacy of precision, innovation, trust and perseverance.
                </p>

                <div style="display: inline-block; padding: 10px 24px; background: #FFFFFF; border: 1px solid #00A3C4; border-radius: 30px;">
                    <span style="font-size: 0.92rem; font-weight: 700; color: #00A3C4;">
                        "The journey began with a vision. The next chapter is about taking that vision further in India and across the world."
                    </span>
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
        .founder-section-row {
            grid-template-columns: 1fr !important;
            text-align: center !important;
            padding: 24px !important;
        }
        .director-card-grid {
            grid-template-columns: 1fr !important;
            padding: 24px !important;
        }
    }
</style>
@endsection
