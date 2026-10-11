@extends('layouts.app')

@section('title', ($t['privacy_policy_title'] ?? 'Privacy Policy & Data Protection') . ' - Aczet')

@section('content')
<div class="page-banner">
    <h1>
        {{ $t['privacy_policy_title'] ?? 'Privacy Policy & Data Protection' }}
    </h1>
    <p>
        {{ $t['privacy_policy_sub'] ?? 'Compliance with GDPR, CCPA, and Global Metrology Data Standards.' }}
    </p>
</div>

<div class="container-responsive" style="max-width: 900px;">
    <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: clamp(20px, 4vw, 40px); box-shadow: var(--shadow-sm); line-height: 1.8; color: #334155;">
        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 10px;">1. Information We Collect</h2>
        <p style="margin-bottom: 20px; font-size: 0.95rem;">
            Aczet Inc. collects information provided directly by customers when requesting formal commercial quotations, registering warranty service tickets, applying for authorized distributorships, or downloading technical whitepapers.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 10px;">2. Use of Collected Data</h2>
        <p style="margin-bottom: 20px; font-size: 0.95rem;">
            Data is used exclusively to facilitate factory calibrations, deliver equipment quotes, provide firmware updates, and ensure regulatory traceability under ISO 9001:2015 and OIML R76 standards. We do not sell or lease customer contact information to third parties.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 10px;">3. Cookies & Analytical Tracking</h2>
        <p style="margin-bottom: 20px; font-size: 0.95rem;">
            Our website utilizes essential session cookies and Google Analytics 4 to track user navigation and optimize regional performance. Users can configure cookie preferences through the interactive cookie consent banner.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 10px;">4. Corporate Headquarters & Inquiries</h2>
        <p style="margin-bottom: 0; font-size: 0.95rem;">
            For data inquiries or GDPR deletion requests, contact our Compliance Officer at <strong>compliance@aczet.com</strong> or call <strong>+91 22 2847 4800</strong>.
        </p>
    </div>
</div>
@endsection
