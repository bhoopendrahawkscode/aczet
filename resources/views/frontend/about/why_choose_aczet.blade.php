@extends('layouts.app')

@section('title', 'Why Choose Aczet - Precision Engineering & Global Metrology Partner')
@section('meta_description', 'Discover why industries in 65+ countries choose Aczet: Precision engineering, advanced technology, dependable service, and a comprehensive portfolio of weighing, XRF, and laser systems.')

@section('content')
<!-- 1. BREADCRUMBS: Home > About Us > Why Choose Aczet -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 14px 0;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li><a href="{{ route('about.index') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">About Us</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li style="color: #00A3C4; font-weight: 600;">Why Choose Aczet</li>
            </ol>
        </nav>
    </div>
</div>

<!-- 2. HERO BANNER SECTION -->
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
                    Why Choose <span style="color: #00A3C4;">Aczet</span>
                </h1>

                <p style="font-size: clamp(1.1rem, 2vw, 1.35rem); color: #00A3C4; font-weight: 700; line-height: 1.4; margin: 0 0 14px;">
                    When precision and reliability are essential, the right technology partner makes all the difference.
                </p>

                <p style="font-size: 0.98rem; color: #334155; line-height: 1.7; margin: 0 0 12px; max-width: 580px;">
                    Aczet combines advanced engineering, global expertise and customer focused solutions to deliver high performance instruments trusted by industries worldwide. Our approach goes beyond developing individual products. We create technology solutions designed to support accuracy, quality, productivity and confidence across critical applications.
                </p>

                <p style="font-size: 0.94rem; color: #64748B; line-height: 1.65; margin: 0; max-width: 580px;">
                    From precision weighing and moisture analysis to XRF metal analysis and laser technology, Aczet brings together a broad portfolio of technologies to serve the evolving needs of modern industries.
                </p>
            </div>

            <!-- Right Side: Graphic Stage + Vertical Slogan -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 24px; position: relative;">
                <div style="max-width: 460px; width: 100%; text-align: right;">
                    <img src="{{ vasset('images/about/tech-hero-stage.jpg') }}" 
                         alt="Why Choose Aczet - Engineered for Precision" 
                         style="width: 100%; height: auto; object-fit: contain; max-height: 290px; border-radius: 8px;"
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

<!-- MAIN CONTENT WRAPPER -->
<div style="background: #FFFFFF; padding: 60px 0 80px;">
    <div style="width: min(1160px, calc(100% - 48px)); margin: 0 auto; display: flex; flex-direction: column; gap: 70px;">

        <!-- 3. SIX CORE VALUE PILLARS -->
        <section>
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px;">
                <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">OUR CORE STRENGTHS</span>
                    <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
                </div>
                <h2 style="font-size: 2.2rem; font-weight: 800; color: #0F172A; margin: 0 0 10px; font-family: 'Space Grotesk', sans-serif;">
                    Engineered for Excellence
                </h2>
                <p style="font-size: 1rem; color: #475569; margin: 0;">
                    Six fundamental commitments that define our instruments, technology and customer partnerships.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
                
                <!-- Pillar 1: Precision Engineering -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 30px; border-top: 4px solid #00A3C4; transition: transform 0.2s;">
                    <div style="width: 44px; height: 44px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 16px;">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">Precision Engineering</h3>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 10px;">Precision is at the heart of everything we do.</span>
                    <p style="font-size: 0.92rem; color: #475569; line-height: 1.65; margin: 0;">
                        Our instruments are developed using advanced engineering and high quality components to deliver high accuracy, stability and long term performance in demanding laboratory and industrial environments. Every detail matters when the result depends on a precise measurement, helping customers operate with greater confidence.
                    </p>
                </div>

                <!-- Pillar 2: Advanced Technology -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 30px; border-top: 4px solid #00A3C4; transition: transform 0.2s;">
                    <div style="width: 44px; height: 44px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 16px;">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">Advanced Technology</h3>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 10px;">Technology evolves. So do we.</span>
                    <p style="font-size: 0.92rem; color: #475569; line-height: 1.65; margin: 0;">
                        Aczet continuously invests in research, product development and innovation to provide cutting edge solutions across precision weighing, XRF metal analysis and laser technology. By bringing together engineering expertise, advanced electronics and intelligent software, we address real application requirements.
                    </p>
                </div>

                <!-- Pillar 3: Global Presence -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 30px; border-top: 4px solid #00A3C4; transition: transform 0.2s;">
                    <div style="width: 44px; height: 44px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 16px;">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">Global Presence</h3>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 10px;">Precision knows no boundaries.</span>
                    <p style="font-size: 0.92rem; color: #475569; line-height: 1.65; margin: 0;">
                        With products used in more than 65 countries and a strong network of distributors and service partners, Aczet has established a growing presence across international markets. Our global network enables us to bring our technologies closer to customers while providing reliable product availability and dedicated support.
                    </p>
                </div>

                <!-- Pillar 4: Wide Product Portfolio -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 30px; border-top: 4px solid #00A3C4; transition: transform 0.2s;">
                    <div style="width: 44px; height: 44px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 16px;">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">Wide Product Portfolio</h3>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 10px;">One partner. Multiple technology solutions.</span>
                    <p style="font-size: 0.92rem; color: #475569; line-height: 1.65; margin: 0;">
                        Aczet offers a comprehensive portfolio ranging from analytical balances and moisture analyzers to XRF gold testing machines and laser systems. This breadth allows us to support diverse applications and industries through technologies designed around specific measurement and quality requirements.
                    </p>
                </div>

                <!-- Pillar 5: Reliable Service & Support -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 30px; border-top: 4px solid #00A3C4; transition: transform 0.2s;">
                    <div style="width: 44px; height: 44px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 16px;">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">Reliable Service &amp; Support</h3>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 10px;">Our relationship with customers extends beyond the product.</span>
                    <p style="font-size: 0.92rem; color: #475569; line-height: 1.65; margin: 0;">
                        Through our global service network, Aczet provides installation support, product training, technical assistance and after sales service to help customers achieve maximum productivity from their technology investments. Dependable technology is supported by dependable service throughout its operational journey.
                    </p>
                </div>

                <!-- Pillar 6: Trusted by Industries -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 30px; border-top: 4px solid #00A3C4; transition: transform 0.2s;">
                    <div style="width: 44px; height: 44px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 16px;">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">Trusted by Industries</h3>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 10px;">Technology creates value when it solves real industry challenges.</span>
                    <p style="font-size: 0.92rem; color: #475569; line-height: 1.65; margin: 0;">
                        Aczet solutions are trusted by companies across pharmaceuticals, laboratories, food processing, research institutions and the jewellery sector. By understanding application nuances, we deliver technologies that support accuracy, quality control, and productivity where they matter most.
                    </p>
                </div>

            </div>
        </section>

        <!-- 4. INDUSTRIES WE SERVE SECTION -->
        <section style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 48px 40px;">
            <div style="text-align: center; max-width: 800px; margin: 0 auto 36px;">
                <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">APPLICATIONS &amp; SECTORS</span>
                    <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
                </div>
                <h2 style="font-size: 2.2rem; font-weight: 800; color: #0F172A; margin: 0 0 14px; font-family: 'Space Grotesk', sans-serif;">
                    Industries We Serve
                </h2>
                <p style="font-size: 0.98rem; color: #334155; line-height: 1.7; margin: 0;">
                    Different industries demand different levels of precision. Aczet develops advanced measurement and testing technologies to meet these requirements across a wide range of applications. From jewellery manufacturing and laboratory research to pharmaceutical production, food processing and industrial manufacturing, our solutions support environments where accuracy, quality control and reliability are essential.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <!-- Industry 1: Jewellery -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="fa-solid fa-gem" style="color: #00A3C4; font-size: 1.1rem;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0;">Jewellery Industry</h4>
                    </div>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Where precision defines value.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.65; margin: 0;">
                        Aczet provides solutions including XRF gold testing machines, carat balances, laser welding machines and laser marking systems to support jewellery manufacturers, retailers and testing laboratories. These technologies enable accurate metal analysis and hallmarking.
                    </p>
                </div>

                <!-- Industry 2: Laboratories & Research -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="fa-solid fa-flask-vial" style="color: #00A3C4; font-size: 1.1rem;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0;">Laboratories &amp; Research</h4>
                    </div>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Where every measurement matters.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.65; margin: 0;">
                        Our analytical balances, micro balances and laboratory instruments support research laboratories, testing facilities and academic institutions where high precision measurement is critical, achieving accurate and repeatable analytical results.
                    </p>
                </div>

                <!-- Industry 3: Pharmaceutical Industry -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="fa-solid fa-capsules" style="color: #00A3C4; font-size: 1.1rem;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0;">Pharmaceutical Industry</h4>
                    </div>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Precision is fundamental to pharmaceutical processes.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.65; margin: 0;">
                        Aczet precision weighing and analytical instruments support drug formulation, laboratory analysis and quality control processes within pharmaceutical manufacturing environments, fully complying with regulatory standards.
                    </p>
                </div>

                <!-- Industry 4: Food Industry -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="fa-solid fa-wheat-awn" style="color: #00A3C4; font-size: 1.1rem;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0;">Food Industry</h4>
                    </div>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Accuracy supports quality.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.65; margin: 0;">
                        Our moisture analyzers and precision weighing systems help food manufacturers and processors with accurate ingredient measurement, moisture testing and quality control, contributing to consistency and shelf life across production.
                    </p>
                </div>

                <!-- Industry 5: Education & Research Institutions -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="fa-solid fa-graduation-cap" style="color: #00A3C4; font-size: 1.1rem;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0;">Education &amp; Research</h4>
                    </div>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Enabling the next generation of discovery.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.65; margin: 0;">
                        Universities and research institutes use Aczet instruments for scientific research, laboratory experiments and academic training. Our precision technologies support both established research and active learning environments.
                    </p>
                </div>

                <!-- Industry 6: Industrial Manufacturing -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="fa-solid fa-industry" style="color: #00A3C4; font-size: 1.1rem;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0;">Industrial Manufacturing</h4>
                    </div>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Precision drives production.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.65; margin: 0;">
                        Aczet industrial weighing systems and measurement technologies help manufacturers maintain production accuracy, process control and operational efficiency across tough factory environments.
                    </p>
                </div>
            </div>
        </section>

        <!-- 5. OUR TECHNOLOGY SECTION -->
        <section>
            <div style="text-align: center; max-width: 780px; margin: 0 auto 36px;">
                <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">TECHNOLOGY PORTFOLIO</span>
                    <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
                </div>
                <h2 style="font-size: 2.2rem; font-weight: 800; color: #0F172A; margin: 0 0 10px; font-family: 'Space Grotesk', sans-serif;">
                    Our Technology
                </h2>
                <p style="font-size: 1.05rem; font-weight: 600; color: #00A3C4; margin: 0 0 12px;">
                    At Aczet, technology is where engineering meets application.
                </p>
                <p style="font-size: 0.96rem; color: #475569; line-height: 1.7; margin: 0;">
                    We combine precision engineering, advanced electronics and intelligent software to develop high performance technologies serving laboratory, industrial and jewellery applications. Our technology portfolio brings together specialised solutions designed to address different measurement, analysis and production requirements.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px; border-left: 3px solid #00A3C4;">
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0 0 4px;">Precision Weighing</h4>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Built around accuracy.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.6; margin: 0;">
                        Micro balances, semi micro, analytical balances, and industrial weighing systems designed for ultra-high accuracy measurements where stability and repeatability are essential.
                    </p>
                </div>

                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px; border-left: 3px solid #00A3C4;">
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0 0 4px;">XRF Metal Analysis</h4>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Fast analysis. Reliable insight.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.6; margin: 0;">
                        Fast, non-destructive metal analysis for gold purity testing and alloy identification, enabling rapid verification without damaging precious samples.
                    </p>
                </div>

                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px; border-left: 3px solid #00A3C4;">
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0 0 4px;">Laser Solutions</h4>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Precision beyond measurement.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.6; margin: 0;">
                        Laser marking and spot welding systems engineered for high precision engraving, metal marking, and intricate jewellery repair applications.
                    </p>
                </div>

                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 24px; border-left: 3px solid #00A3C4;">
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0 0 4px;">Moisture Analysis</h4>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; display: block; margin-bottom: 8px;">Measure what cannot always be seen.</span>
                    <p style="font-size: 0.88rem; color: #64748B; line-height: 1.6; margin: 0;">
                        Halogen moisture analyzers providing dependable moisture determination across pharmaceutical, food, chemical, and agricultural applications.
                    </p>
                </div>
            </div>
        </section>

        <!-- 6. RESEARCH, DEVELOPMENT & MANUFACTURING SECTION -->
        <section style="background: linear-gradient(135deg, #F8FAFC 0%, #FFFFFF 100%); border: 1px solid #E2E8F0; border-radius: 16px; padding: 48px 44px; display: grid; grid-template-columns: 1fr 340px; gap: 40px; align-items: center;" class="rd-section-grid">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <span style="display: inline-block; width: 24px; height: 2px; background: #00A3C4;"></span>
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #00A3C4;">INNOVATION INFRASTRUCTURE</span>
                </div>

                <h3 style="font-size: 1.85rem; font-weight: 800; color: #0F172A; margin: 0 0 6px; font-family: 'Space Grotesk', sans-serif;">
                    Research, Development &amp; Manufacturing
                </h3>
                
                <p style="font-size: 1.05rem; font-weight: 700; color: #00A3C4; margin: 0 0 14px;">
                    From an Idea to a Precision Technology
                </p>

                <p style="font-size: 0.95rem; color: #334155; line-height: 1.7; margin-bottom: 12px;">
                    Innovation is at the foundation of Aczet’s growth. We operate our own modern manufacturing facilities equipped with advanced production technologies, precision calibration systems and strict quality control processes.
                </p>

                <p style="font-size: 0.95rem; color: #475569; line-height: 1.7; margin-bottom: 12px;">
                    Our in house R&amp;D team brings together expertise across mechanical engineering, electronics, embedded systems and software development. This multidisciplinary approach enables us to continuously develop innovative technologies and improve product performance.
                </p>

                <p style="font-size: 0.95rem; color: #475569; line-height: 1.7; margin: 0;">
                    From concept design and engineering to prototyping, testing and final production, every stage follows rigorous standards. The result is a development and manufacturing approach focused on one objective: creating instruments that deliver accuracy, reliability and long term performance.
                </p>
            </div>

            <!-- Image / Card Box -->
            <div style="text-align: center;">
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
                    <img src="{{ vasset('images/about/history-2024-suzhou.jpg') }}" 
                         alt="Aczet Manufacturing Facilities" 
                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 14px;"
                         onerror="this.onerror=null; this.src='{{ vasset('images/products/banner-balance.png') }}';">
                    <span style="font-size: 0.85rem; font-weight: 700; color: #0F172A; display: block;">ISO 9001 &amp; ISO 17025 Facility</span>
                    <span style="font-size: 0.76rem; color: #64748B;">In-house R&amp;D &amp; Precision Cleanrooms</span>
                </div>
            </div>
        </section>

        <!-- 7. CLOSING CTA BANNER -->
        <section style="background: #0F172A; border-radius: 14px; padding: 48px; text-align: center; color: #FFFFFF; position: relative; overflow: hidden;">
            <div style="position: absolute; right: 0; top: 0; width: 400px; height: 100%; background: radial-gradient(circle at 80% 50%, rgba(0, 163, 196, 0.2) 0%, transparent 70%); pointer-events: none;"></div>
            
            <div style="position: relative; z-index: 2; max-width: 650px; margin: 0 auto;">
                <h3 style="font-size: 1.8rem; font-weight: 800; color: #FFFFFF; margin: 0 0 12px; font-family: 'Space Grotesk', sans-serif;">
                    Ready to Experience Aczet Precision?
                </h3>
                <p style="font-size: 1rem; color: #CBD5E1; line-height: 1.7; margin: 0 0 24px;">
                    Connect with our application specialists to find the right metrology solution for your laboratory, industrial plant, or jewellery facility.
                </p>
                <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                    <a href="{{ route('products.index') }}" style="background: #00A3C4; color: #FFFFFF; padding: 12px 26px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; text-decoration: none;">
                        Explore Product Catalog
                    </a>
                    <button type="button" onclick="openQuoteModal('General Inquiry - Why Choose Aczet', 0, 'General Inquiry')" style="background: transparent; color: #FFFFFF; border: 1px solid rgba(255,255,255,0.3); padding: 12px 26px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer;">
                        Request a Quote
                    </button>
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
        .rd-section-grid {
            grid-template-columns: 1fr !important;
            padding: 24px !important;
        }
    }
</style>
@endsection

