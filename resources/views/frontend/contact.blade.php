@extends('layouts.app')

@section('title', ($t['nav_contact'] ?? 'Contact Us') . ' | Aczet')
@section('meta_description', 'Connect with Aczet technical sales engineers, regional offices, and service teams for weighing and measurement solutions.')

@section('styles')
<style>
    .contact-page { --contact-ink: #0F172A; --contact-blue: #0284C7; --contact-mint: #F0F9FF; --contact-orange: #0284C7; background: #F8FAFC; color: var(--contact-ink); }
    .contact-wrap { width: min(1240px, calc(100% - 48px)); margin: 0 auto; }
    .contact-hero { background: #FFFFFF; color: #0F172A; padding: 60px 0 48px; border-bottom: 1px solid #E2E8F0; position: relative; overflow: hidden; }
    .contact-hero::after { content: 'ACZET / GLOBAL'; position: absolute; right: 5%; bottom: 25px; color: rgba(0, 163, 196, .2); font-size: .72rem; font-weight: 800; letter-spacing: .2em; writing-mode: vertical-rl; }
    .contact-hero h1 { max-width: 720px; margin: 18px 0 14px; font: 700 clamp(2.5rem, 5vw, 4.2rem)/1.02 'Space Grotesk', sans-serif; letter-spacing: -.04em; color: #0F172A; }
    .contact-hero p { max-width: 600px; margin: 0; color: #64748B; font-size: 1.04rem; line-height: 1.75; }
    .contact-eyebrow { display: inline-flex; align-items: center; gap: 9px; color: #00A3C4; font-size: .75rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
    .contact-eyebrow::before { content: ''; width: 28px; height: 2px; background: #00A3C4; }
    .contact-main { padding: 60px 0 90px; }
    .contact-grid { display: grid; grid-template-columns: .9fr 1.1fr; gap: 64px; align-items: start; }
    .contact-intro h2 { margin: 14px 0 16px; font: 700 clamp(2rem, 3.3vw, 3rem)/1.05 'Space Grotesk', sans-serif; letter-spacing: -.045em; color: #0F172A; }
    .contact-intro > p { max-width: 500px; color: #64748B; line-height: 1.75; }
    .office-list { display: grid; gap: 14px; margin-top: 30px; }
    .office-card { padding: 20px 22px; background: #fff; border: 1px solid #d8e4df; border-left: 4px solid #00A3C4; border-radius: 8px; }
    .office-card:nth-child(2) { border-left-color: #0284C7; }
    .office-card:nth-child(3) { border-left-color: #0369A1; }
    .office-card h3 { margin: 0 0 8px; font: 700 1.03rem 'Space Grotesk', sans-serif; color: #0F172A; }
    .office-card p { margin: 0; color: #64748B; font-size: .84rem; line-height: 1.65; }
    .office-contact { margin-top: 8px !important; color: #0284C7 !important; font-size: .78rem !important; font-weight: 700; }
    .contact-form-card { background: #fff; border: 1px solid #E2E8F0; border-radius: 12px; padding: clamp(26px, 4vw, 44px); box-shadow: 0 10px 30px rgba(15, 23, 42, .06); }
    .contact-form-card h2 { margin: 0 0 8px; font: 700 clamp(1.6rem, 3vw, 2.25rem) 'Space Grotesk', sans-serif; letter-spacing: -.035em; color: #0F172A; }
    .contact-form-card > p { margin: 0 0 28px; color: #64748B; font-size: .88rem; line-height: 1.65; }
    .contact-form { display: grid; gap: 17px; }
    .contact-form label { display: block; margin-bottom: 6px; color: #0F172A; font-size: .78rem; font-weight: 800; }
    .contact-form .form-control { border: 1px solid #cadbd6; border-radius: 6px; padding: 13px 14px; background: #fbfcfb; font-size: .86rem; }
    .contact-form .form-control:focus { border-color: #0284C7; box-shadow: 0 0 0 3px rgba(2, 132, 199, .15); }
    .contact-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .contact-submit { width: 100%; justify-content: center; min-height: 50px; margin-top: 4px; border: 0; border-radius: 10px; background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%); color: #fff; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25); }
    .contact-submit:hover { background: linear-gradient(135deg, #0EA5E9 0%, #0284C7 100%); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(2, 132, 199, 0.45); }
    .contact-note { display: flex; gap: 10px; align-items: center; margin-top: 18px !important; color: #75878a; font-size: .72rem !important; }
    .contact-note i { color: #1a9b75; }
    @media (max-width: 880px) { .contact-grid { grid-template-columns: 1fr; gap: 38px; } .contact-hero { padding: 58px 0 64px; } }
    @media (max-width: 560px) { .contact-wrap { width: min(100% - 32px, 560px); } .contact-main { padding: 52px 0 72px; } .contact-form-row { grid-template-columns: 1fr; gap: 17px; } }
</style>
@endsection

@section('content')
<div class="contact-page">
    <section class="contact-hero"><div class="contact-wrap"><span class="contact-eyebrow">Technical sales & support</span><h1>Let’s solve the weighing challenge together.</h1><p>Talk with Aczet engineers about balances, industrial systems, material analysis, service, and the right solution for your process.</p></div></section>
    <main class="contact-main"><div class="contact-wrap contact-grid">
        <section class="contact-intro"><span class="contact-eyebrow">Worldwide technical presence</span><h2>People close to the work.</h2><p>Our regional teams connect product knowledge with the realities of your laboratory, plant, showroom, or production line.</p>
            <div class="office-list">
                <article class="office-card"><h3><i class="fa-solid fa-building"></i> Aczet World Headquarters</h3><p>{{ site_setting('address_hq', 'Aczet Metrology, 201, Commercial Center, Mumbai 400069, Maharashtra, India') }}</p><p class="office-contact"><i class="fa-solid fa-phone"></i> {{ site_setting('phone_primary', '+91 22 4141 5555') }} &nbsp; <i class="fa-solid fa-envelope"></i> {{ site_setting('email_sales', 'sales@aczet.com') }}</p></article>
                <article class="office-card"><h3><i class="fa-solid fa-globe"></i> European Technical Center</h3><p>{{ site_setting('address_technical_center', 'Aczet European Technical Center, Neugasse 29, 8005 Zürich, Switzerland') }}</p><p class="office-contact"><i class="fa-solid fa-envelope"></i> {{ site_setting('email_support', 'support@aczet.com') }}</p></article>
                <article class="office-card"><h3><i class="fa-solid fa-landmark"></i> Americas Regional Hub</h3><p>{{ site_setting('address_usa', 'Aczet America Inc., 1250 E Business Center Dr, Mount Prospect, IL 60056, USA') }}</p><p class="office-contact"><i class="fa-solid fa-envelope"></i> {{ site_setting('email_sales', 'sales@aczet.com') }}</p></article>
            </div>
        </section>
        <section class="contact-form-card"><h2>{{ $t['send_message_title'] ?? 'Direct engineering inquiry' }}</h2><p>{{ $t['send_message_sub'] ?? 'Tell us what you are measuring and our team will respond with useful technical information.' }}</p>
            <form class="contact-form" action="{{ route('leads.store') }}" method="POST">@csrf<input type="hidden" name="source" value="Contact Us Page Form"><input type="hidden" name="region" value="{{ $currentRegion }}">
                <div><label for="contactName">{{ $t['modal_name'] ?? 'Full Name' }} *</label><input id="contactName" type="text" name="name" required class="form-control"></div>
                <div class="contact-form-row"><div><label for="contactEmail">{{ $t['modal_email'] ?? 'Work Email' }} *</label><input id="contactEmail" type="email" name="email" required class="form-control"></div><div><label for="contactPhone">{{ $t['modal_phone'] ?? 'Phone / WhatsApp' }}</label><input id="contactPhone" type="text" name="phone" class="form-control"></div></div>
                <div><label for="contactCompany">{{ $t['modal_company'] ?? 'Company / Laboratory Name' }}</label><input id="contactCompany" type="text" name="company" class="form-control"></div>
                <div><label for="contactMessage">{{ $t['modal_message'] ?? 'Application Details / Special Requirements' }}</label><textarea id="contactMessage" name="message" rows="5" placeholder="Tell us about your sample, capacity, environment, or application..." class="form-control"></textarea></div>
                <button type="submit" class="contact-submit"><i class="fa-solid fa-arrow-right"></i> {{ $t['modal_submit'] ?? 'Send Inquiry' }}</button>
            </form><p class="contact-note"><i class="fa-solid fa-circle-check"></i> Your information goes directly to the Aczet technical sales team.</p>
        </section>
    </div></main>
</div>
@endsection
