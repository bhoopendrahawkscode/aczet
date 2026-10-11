@extends('layouts.app')

@section('title', 'Scientific Webinars & Technical Virtual Events - Aczet')
@section('meta_description', 'Join live technical webinars and virtual training workshops hosted by Aczet metrology specialists.')

@section('content')
<div class="page-banner">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(2, 132, 199, 0.1); border: 1px solid rgba(2, 132, 199, 0.25); color: #0284C7; padding: 6px 14px; border-radius: 30px; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;">
        <i class="fa-solid fa-video"></i> Aczet Knowledge Academy
    </div>
    <h1>Scientific Webinars & Virtual Events</h1>
    <p>
        Interactive masterclasses on pharmaceutical regulatory compliance, high-resolution microbalance calibration, and automated GLP data logging.
    </p>
</div>

<div class="container-responsive">
    <!-- Upcoming Masterclasses -->
    <h2 style="font-size: clamp(1.3rem, 3vw, 1.6rem); font-weight: 800; color: var(--primary); margin-bottom: 20px; border-bottom: 2px solid var(--accent); padding-bottom: 8px;">
        <i class="fa-solid fa-calendar-star" style="color:var(--accent);"></i> Upcoming Live Masterclasses
    </h2>

    <div class="card-grid-responsive" style="margin-bottom: 50px;">
        <!-- Webinar 1 -->
        <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; width: 100%;">
            <div style="background: #0F172A; padding: 20px; color: #FFF; position: relative;">
                <span style="background: #0369A1; color: #FFF; padding: 4px 8px; border-radius: 4px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase;">
                    LIVE WEBINAR
                </span>
                <h3 style="font-size: 1.15rem; font-weight: 800; margin: 10px 0 4px;">USP &lt;41&gt; &amp; &lt;1251&gt; Compliance in Pharmaceutical Weighing</h3>
                <p style="font-size: 0.8rem; color: #94A3B8; margin: 0;"><i class="fa-solid fa-clock"></i> September 24, 2026 &bull; 02:00 PM CET (45 mins)</p>
            </div>
            <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                <p style="font-size: 0.88rem; color: #475569; line-height: 1.6; margin-bottom: 18px; flex: 1;">
                    Master minimum weight determination, repeatable measurement uncertainty, and audit-ready GLP/GMP calibration workflows with Dr. Hans Müller.
                </p>
                <div style="border-top: 1px solid #F1F5F9; padding-top: 14px; margin-top: auto;">
                    <button class="btn-primary" style="width: 100%; justify-content: center; padding: 10px; font-size: 0.85rem;" onclick="openQuoteModal('Webinar Registration: USP 41 Compliance', null, 'Registering for USP 41 Compliance Webinar')">
                        <i class="fa-solid fa-ticket"></i> Reserve Free Seat
                    </button>
                </div>
            </div>
        </div>

        <!-- Webinar 2 -->
        <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; width: 100%;">
            <div style="background: #0F172A; padding: 20px; color: #FFF; position: relative;">
                <span style="background: #2563EB; color: #FFF; padding: 4px 8px; border-radius: 4px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase;">
                    APPLICATION WORKSHOP
                </span>
                <h3 style="font-size: 1.15rem; font-weight: 800; margin: 10px 0 4px;">Halogen Moisture Analysis Optimization for Food &amp; Grain</h3>
                <p style="font-size: 0.8rem; color: #94A3B8; margin: 0;"><i class="fa-solid fa-clock"></i> October 12, 2026 &bull; 11:00 AM EST (40 mins)</p>
            </div>
            <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                <p style="font-size: 0.88rem; color: #475569; line-height: 1.6; margin-bottom: 18px; flex: 1;">
                    Learn temperature ramp programming, step drying profiles, and 0.001g moisture repeatability using the Aczet MB 200 halogen system.
                </p>
                <div style="border-top: 1px solid #F1F5F9; padding-top: 14px; margin-top: auto;">
                    <button class="btn-primary" style="width: 100%; justify-content: center; padding: 10px; font-size: 0.85rem;" onclick="openQuoteModal('Webinar Registration: Moisture Analysis', null, 'Registering for Moisture Analysis Webinar')">
                        <i class="fa-solid fa-ticket"></i> Reserve Free Seat
                    </button>
                </div>
            </div>
        </div>

        <!-- Webinar 3 -->
        <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; width: 100%;">
            <div style="background: #0F172A; padding: 20px; color: #FFF; position: relative;">
                <span style="background: #173887; color: #FFF; padding: 4px 8px; border-radius: 4px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase;">
                    TECHNICAL MASTERCLASS
                </span>
                <h3 style="font-size: 1.15rem; font-weight: 800; margin: 10px 0 4px;">MonoTech EMFC Load Cell Physics &amp; Nanogram Precision</h3>
                <p style="font-size: 0.8rem; color: #94A3B8; margin: 0;"><i class="fa-solid fa-clock"></i> November 05, 2026 &bull; 04:00 PM IST (50 mins)</p>
            </div>
            <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                <p style="font-size: 0.88rem; color: #475569; line-height: 1.6; margin-bottom: 18px; flex: 1;">
                    Explore electromagnetic force restoration, automated draft shield dynamics, and temperature compensation algorithms in ultra-micro balances.
                </p>
                <div style="border-top: 1px solid #F1F5F9; padding-top: 14px; margin-top: auto;">
                    <button class="btn-primary" style="width: 100%; justify-content: center; padding: 10px; font-size: 0.85rem;" onclick="openQuoteModal('Webinar Registration: MonoTech Physics', null, 'Registering for MonoTech Physics Webinar')">
                        <i class="fa-solid fa-ticket"></i> Reserve Free Seat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- On-Demand Recorded Sessions -->
    <h2 style="font-size: clamp(1.3rem, 3vw, 1.6rem); font-weight: 800; color: var(--primary); margin-bottom: 20px; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;">
        <i class="fa-solid fa-circle-play" style="color:#2563EB;"></i> On-Demand Recorded Sessions
    </h2>

    <div class="card-grid-responsive">
        <div style="background: #FFF; border-radius: 10px; border: 1px solid var(--border-color); padding: 18px; box-shadow: var(--shadow-sm); width: 100%;">
            <div style="font-size: 0.78rem; font-weight: 700; color: var(--accent); margin-bottom: 4px;">ON-DEMAND REPLAY (38 MINS)</div>
            <h4 style="font-size: 1rem; font-weight: 700; color: var(--primary); margin-bottom: 6px;">OIML R76 Class I &amp; II Verification for Non-Automatic Instruments</h4>
            <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 14px;">Legal metrology rules, maximum permissible error (MPE), and sealing standards.</p>
            <a href="{{ route('docs.download', ['filename' => 'oiml_r76_standards.pdf']) }}" download="oiml_r76_standards.pdf" class="btn-sm btn-outline" style="font-size: 0.8rem; padding: 6px 12px;">
                <i class="fa-solid fa-file-pdf"></i> Download Presentation Notes
            </a>
        </div>

        <div style="background: #FFF; border-radius: 10px; border: 1px solid var(--border-color); padding: 18px; box-shadow: var(--shadow-sm); width: 100%;">
            <div style="font-size: 0.78rem; font-weight: 700; color: var(--accent); margin-bottom: 4px;">ON-DEMAND REPLAY (42 MINS)</div>
            <h4 style="font-size: 1rem; font-weight: 700; color: var(--primary); margin-bottom: 6px;">Automating 21 CFR Part 11 Audit Trails with Aczet Data Direct</h4>
            <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 14px;">Electronic signatures, user permissions, and tamper-proof RS232/USB records.</p>
            <a href="{{ route('docs.download', ['filename' => 'monotech_whitepaper.pdf']) }}" download="monotech_whitepaper.pdf" class="btn-sm btn-outline" style="font-size: 0.8rem; padding: 6px 12px;">
                <i class="fa-solid fa-file-pdf"></i> Download Presentation Notes
            </a>
        </div>
    </div>
</div>
@endsection
