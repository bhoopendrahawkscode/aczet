@extends('frontend.about.layout')

@section('title', 'About Us | Aczet Precision Metrology & Industrial Solutions')
@section('meta_description', 'Discover Aczet - A global leader in precision weighing, analytical microbalances, XRF metal analysis, and industrial metrology solutions serving 65+ countries.')

@section('page_title', 'About Us')
@section('hero_heading', site_setting('about_hero_heading', 'About Aczet'))
@section('hero_subheading', site_setting('about_hero_subheading', 'Decades of engineering excellence delivering certified precision weighing, analytical instruments, XRF metal analysis, and laser processing technology to global industries.'))

@section('about_content')
<div style="display: flex; flex-direction: column; gap: 32px;">

    <!-- Card 1: Corporate Profile & Overview -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 36px 40px; box-shadow: 0 2px 12px rgba(0,0,0,0.02);">
        <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: #00A3C4; letter-spacing: 0.06em; display: block; margin-bottom: 6px;">{{ site_setting('about_overview_eyebrow', 'Corporate Overview') }}</span>
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #0F172A; margin: 0 0 16px; font-family: 'Space Grotesk', sans-serif;">{{ site_setting('about_overview_title', 'Precision Engineering. Global Trust.') }}</h2>
        
        <p style="font-size: 1.02rem; color: #334155; line-height: 1.75; margin-bottom: 20px;">
            {{ site_setting('about_overview_p1', 'Aczet is a premier manufacturer and global supplier of high-precision weighing balances, laboratory instruments, XRF gold testing and coating thickness analyzers, and precision laser systems. Combining German metrological concepts with world-class production capabilities, Aczet equips pharmaceutical laboratories, jewellery manufacturing clusters, universities, and heavy industrial plants with dependable accuracy.') }}
        </p>

        <p style="font-size: 0.98rem; color: #64748B; line-height: 1.7; margin-bottom: 28px;">
            {{ site_setting('about_overview_p2', 'Our instruments are deployed in over 65 countries, supported by an established international distributor network, dedicated application engineering centers, and ISO/IEC 17025 accredited calibration labs. Whether measuring a fraction of a microgram in formulation research or screening alloy composition in precious metal refineries, Aczet delivers confidence in every measurement.') }}
        </p>

        <!-- Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; padding: 24px; background: #F8FAFC; border-radius: 10px; border: 1px solid #E2E8F0;">
            <div>
                <span style="font-size: 2.2rem; font-weight: 800; color: #00A3C4; display: block; font-family: 'Space Grotesk', sans-serif;">{{ site_setting('about_stat_1_val', '65+') }}</span>
                <span style="font-size: 0.85rem; font-weight: 700; color: #0F172A;">{{ site_setting('about_stat_1_label', 'Countries Served') }}</span>
                <span style="font-size: 0.76rem; color: #64748B; display: block;">{{ site_setting('about_stat_1_sub', 'Across 5 continents') }}</span>
            </div>
            <div>
                <span style="font-size: 2.2rem; font-weight: 800; color: #0284C7; display: block; font-family: 'Space Grotesk', sans-serif;">{{ site_setting('about_stat_2_val', '500+') }}</span>
                <span style="font-size: 0.85rem; font-weight: 700; color: #0F172A;">{{ site_setting('about_stat_2_label', 'Certified Models') }}</span>
                <span style="font-size: 0.76rem; color: #64748B; display: block;">{{ site_setting('about_stat_2_sub', 'Lab & industrial catalog') }}</span>
            </div>
            <div>
                <span style="font-size: 2.2rem; font-weight: 800; color: #10B981; display: block; font-family: 'Space Grotesk', sans-serif;">{{ site_setting('about_stat_3_val', '17025') }}</span>
                <span style="font-size: 0.85rem; font-weight: 700; color: #0F172A;">{{ site_setting('about_stat_3_label', 'ISO Accredited') }}</span>
                <span style="font-size: 0.76rem; color: #64748B; display: block;">{{ site_setting('about_stat_3_sub', 'Traceable calibration labs') }}</span>
            </div>
            <div>
                <span style="font-size: 2.2rem; font-weight: 800; color: #8B5CF6; display: block; font-family: 'Space Grotesk', sans-serif;">{{ site_setting('about_stat_4_val', '25+') }}</span>
                <span style="font-size: 0.85rem; font-weight: 700; color: #0F172A;">{{ site_setting('about_stat_4_label', 'Years Innovation') }}</span>
                <span style="font-size: 0.76rem; color: #64748B; display: block;">{{ site_setting('about_stat_4_sub', 'Patented metrology') }}</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Core Engineering Pillars -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 36px 40px; box-shadow: 0 2px 12px rgba(0,0,0,0.02);">
        <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: #00A3C4; letter-spacing: 0.06em; display: block; margin-bottom: 6px;">Our Foundation</span>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; margin: 0 0 20px; font-family: 'Space Grotesk', sans-serif;">Four Pillars of Aczet Excellence</h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            <div style="padding: 20px; background: #F8FAFC; border-radius: 8px; border-left: 3px solid #00A3C4;">
                <div style="width: 36px; height: 36px; background: rgba(0, 163, 196, 0.1); color: #00A3C4; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 12px;">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 8px;">Monobloc Sensor Core</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Precision-machined electromagnetic weighing blocks deliver rapid response times, microgram sensitivity, and thermal stability under rigorous laboratory conditions.</p>
            </div>

            <div style="padding: 20px; background: #F8FAFC; border-radius: 8px; border-left: 3px solid #10B981;">
                <div style="width: 36px; height: 36px; background: rgba(16, 185, 129, 0.1); color: #10B981; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 12px;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 8px;">Regulatory Compliance</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Full adherence to FDA 21 CFR Part 11 electronic records, USP Chapter 41 minimum weight requirements, OIML Class I &amp; II legal-for-trade certification, and CE marking.</p>
            </div>

            <div style="padding: 20px; background: #F8FAFC; border-radius: 8px; border-left: 3px solid #0284C7;">
                <div style="width: 36px; height: 36px; background: rgba(2, 132, 199, 0.12); color: #0284C7; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 12px;">
                    <i class="fa-solid fa-atom"></i>
                </div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 8px;">XRF &amp; Laser Integration</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Advanced Silicon Drift Detector (SDD) X-ray fluorescence analysis and multi-axis laser marking/welding systems built for non-destructive testing and fabrication.</p>
            </div>

            <div style="padding: 20px; background: #F8FAFC; border-radius: 8px; border-left: 3px solid #8B5CF6;">
                <div style="width: 36px; height: 36px; background: rgba(139, 92, 246, 0.1); color: #8B5CF6; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 12px;">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 8px;">Global Engineering Support</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Rapid turnaround times, localized spare parts inventory, on-site IQ/OQ validation services, and comprehensive dealer training portals worldwide.</p>
            </div>
        </div>
    </div>

    <!-- Card 3: Industries We Empower -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 36px 40px; box-shadow: 0 2px 12px rgba(0,0,0,0.02);">
        <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: #00A3C4; letter-spacing: 0.06em; display: block; margin-bottom: 6px;">Applications</span>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; margin: 0 0 14px; font-family: 'Space Grotesk', sans-serif;">Serving High-Precision Sectors</h3>
        
        <p style="font-size: 0.95rem; color: #64748B; line-height: 1.65; margin-bottom: 24px;">
            Aczet solutions are engineered for demanding sectors where measurement uncertainty directly impacts product safety, quality, and commercial transactions.
        </p>

        <div style="display: flex; gap: 14px; flex-wrap: wrap;">
            <span style="padding: 8px 16px; background: #F1F5F9; color: #1E293B; border-radius: 20px; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-capsules" style="color:#00A3C4;"></i> Pharmaceutical &amp; Biotech
            </span>
            <span style="padding: 8px 16px; background: #F1F5F9; color: #1E293B; border-radius: 20px; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-gem" style="color:#0284C7;"></i> Jewellery &amp; Hallmarking
            </span>
            <span style="padding: 8px 16px; background: #F1F5F9; color: #1E293B; border-radius: 20px; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-industry" style="color:#10B981;"></i> Chemical &amp; Petrochemical
            </span>
            <span style="padding: 8px 16px; background: #F1F5F9; color: #1E293B; border-radius: 20px; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-wheat-awn" style="color:#F59E0B;"></i> Food &amp; Beverage Processing
            </span>
            <span style="padding: 8px 16px; background: #F1F5F9; color: #1E293B; border-radius: 20px; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-graduation-cap" style="color:#8B5CF6;"></i> Academic &amp; Research Institutes
            </span>
            <span style="padding: 8px 16px; background: #F1F5F9; color: #1E293B; border-radius: 20px; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-truck-ramp-box" style="color:#64748B;"></i> Logistics &amp; Heavy Industry
            </span>
        </div>
    </div>

    <!-- Card 4: Why Choose Aczet -->
    <div id="why-choose-aczet" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 36px 40px; box-shadow: 0 2px 12px rgba(0,0,0,0.02); scroll-margin-top: 100px;">
        <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: #00A3C4; letter-spacing: 0.06em; display: block; margin-bottom: 6px;">Why Partner With Us</span>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; margin: 0 0 16px; font-family: 'Space Grotesk', sans-serif;">Why Choose Aczet</h3>
        
        <p style="font-size: 0.98rem; color: #334155; line-height: 1.7; margin-bottom: 24px;">
            Choosing Aczet means partnering with an internationally accredited instrumentation manufacturer committed to metrological integrity, responsive engineering support, and lasting product value.
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 20px; border-left: 3px solid #00A3C4;">
                <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 6px;"><i class="fa-solid fa-certificate" style="color: #00A3C4; margin-right: 6px;"></i> Certified Accuracy</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Full compliance with ISO/IEC 17025, OIML Class I &amp; II approvals, and FDA 21 CFR Part 11 audit trails.</p>
            </div>

            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 20px; border-left: 3px solid #00A3C4;">
                <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 6px;"><i class="fa-solid fa-microchip" style="color: #00A3C4; margin-right: 6px;"></i> Monobloc Sensor Core</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Single-block electromagnetic force restoration cells ensuring rapid settling times and lifetime stability.</p>
            </div>

            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 20px; border-left: 3px solid #00A3C4;">
                <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 6px;"><i class="fa-solid fa-globe" style="color: #00A3C4; margin-right: 6px;"></i> 65+ Countries Supported</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Direct subsidiaries and certified distributor network offering local installation, calibration, and spare parts.</p>
            </div>

            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 20px; border-left: 3px solid #00A3C4;">
                <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 6px;"><i class="fa-solid fa-headset" style="color: #00A3C4; margin-right: 6px;"></i> Rapid Technical Service</h4>
                <p style="font-size: 0.85rem; color: #64748B; margin: 0; line-height: 1.6;">Dedicated application specialists providing custom software integration, calibration protocols, and fast turnaround.</p>
            </div>
        </div>
    </div>

</div>
@endsection
