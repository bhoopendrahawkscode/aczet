@extends('layouts.app')

@section('title', 'Product FAQs & Technical Knowledge Base | Aczet Metrology')
@section('meta_description', 'Authoritative technical FAQs covering Aczet microbalances, analytical balances, XRF gold testing machines, laser welding, industrial scales, and analytical instruments.')

@section('styles')
<style>
    /* -------------------------------------------------------------
       FLAGSHIP FAQ HERO SECTION
       ------------------------------------------------------------- */
    .faq-hero-v2 {
        background: #FFFFFF;
        padding: 56px 0 40px;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #E2E8F0;
        color: #0F172A;
    }

    .faq-hero-v2::before {
        content: '';
        position: absolute;
        top: -120px;
        left: 50%;
        transform: translateX(-50%);
        width: 1000px;
        height: 500px;
        background: radial-gradient(circle, rgba(0, 163, 196, 0.08) 0%, rgba(2, 132, 199, 0.03) 45%, transparent 70%);
        filter: blur(60px);
        pointer-events: none;
        z-index: 1;
    }

    .faq-hero-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 4%;
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .faq-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(0, 163, 196, 0.1);
        border: 1px solid rgba(0, 163, 196, 0.25);
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 0.78rem;
        font-weight: 800;
        color: #00A3C4;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    .pulse-dot-cyan {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #00A3C4;
        box-shadow: 0 0 10px rgba(0, 163, 196, 0.5);
        display: inline-block;
    }

    .faq-hero-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.1rem, 4.2vw, 3.4rem);
        font-weight: 800;
        color: #0F172A;
        line-height: 1.15;
        letter-spacing: -0.03em;
        margin: 0 0 14px;
    }

    .text-accent-cyan {
        color: #00A3C4;
        background: linear-gradient(135deg, #00A3C4 0%, #0284C7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .faq-hero-subtitle {
        font-size: clamp(0.95rem, 1.2vw, 1.1rem);
        color: #64748B;
        max-width: 780px;
        margin: 0 auto 30px;
        line-height: 1.65;
    }

    /* Search Dock */
    .faq-search-dock {
        max-width: 740px;
        margin: 0 auto 20px;
        position: relative;
    }

    .faq-search-wrap {
        position: relative;
        display: flex;
        align-items: center;
        background: #FFFFFF;
        border-radius: 16px;
        border: 1.5px solid #E2E8F0;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
        transition: all 0.25s ease;
    }

    .faq-search-wrap:focus-within {
        border-color: #0284C7;
        box-shadow: 0 12px 35px rgba(2, 132, 199, 0.18);
    }

    .faq-search-icon-left {
        position: absolute;
        left: 20px;
        color: #0284C7;
        font-size: 1.2rem;
        pointer-events: none;
    }

    .faq-search-input {
        width: 100%;
        background: transparent;
        border: none;
        padding: 16px 50px 16px 56px;
        font-size: 1rem;
        font-family: inherit;
        color: #0F172A;
        outline: none;
    }

    .faq-search-clear {
        position: absolute;
        right: 18px;
        background: #F1F5F9;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        color: #64748B;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .faq-search-clear:hover {
        background: #E2E8F0;
        color: #0F172A;
    }

    /* Popular Quick Tags */
    .faq-quick-tags {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
        font-size: 0.8rem;
        color: #94A3B8;
        margin-bottom: 28px;
    }

    .faq-tag-chip {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
        color: #CBD5E1;
        padding: 3px 12px;
        border-radius: 20px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .faq-tag-chip:hover {
        background: rgba(56, 189, 248, 0.2);
        border-color: #38BDF8;
        color: #FFFFFF;
        transform: translateY(-1px);
    }

    /* Division Group Filter Tabs */
    .faq-division-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 10px;
    }

    .faq-div-tab {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.14);
        color: #E2E8F0;
        padding: 9px 18px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(8px);
    }

    .faq-div-tab:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #FFFFFF;
        border-color: rgba(255, 255, 255, 0.28);
        transform: translateY(-1px);
    }

    .faq-div-tab.active {
        background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);
        border-color: #38BDF8;
        color: #FFFFFF;
        box-shadow: 0 4px 16px rgba(2, 132, 199, 0.45);
    }

    .faq-div-tab .tab-badge {
        font-size: 0.72rem;
        padding: 2px 7px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
        font-weight: 700;
    }

    /* -------------------------------------------------------------
       METROLOGY STATS STRIP
       ------------------------------------------------------------- */
    .faq-stats-strip {
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        padding: 16px 0;
    }

    .faq-stats-grid {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 4%;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .faq-stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 4px 12px;
    }

    .faq-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #E0F2FE;
        color: #0284C7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .faq-stat-info strong {
        display: block;
        font-size: 0.96rem;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.2;
    }

    .faq-stat-info span {
        font-size: 0.75rem;
        color: #64748B;
        font-weight: 600;
    }

    /* -------------------------------------------------------------
       MAIN CONTENT 2-COLUMN LAYOUT (SIDEBAR + CONTENT)
       ------------------------------------------------------------- */
    .faq-main-section {
        max-width: 1440px;
        margin: 36px auto 70px;
        padding: 0 4%;
    }

    .faq-layout-grid {
        display: grid;
        grid-template-columns: 310px 1fr;
        gap: 36px;
        align-items: start;
    }

    /* Sticky Sidebar */
    .faq-sidebar {
        position: sticky;
        top: 90px;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .faq-nav-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    .faq-nav-card-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.98rem;
        font-weight: 800;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #F1F5F9;
    }

    .faq-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
        max-height: 400px;
        overflow-y: auto;
    }

    .faq-nav-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 7px 10px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s;
    }

    .faq-nav-link:hover {
        background: #F0F9FF;
        color: #0284C7;
        transform: translateX(2px);
    }

    .faq-nav-link.active {
        background: #E0F2FE;
        color: #0284C7;
        font-weight: 700;
    }

    .faq-nav-count {
        font-size: 0.72rem;
        background: #F1F5F9;
        color: #64748B;
        padding: 1px 7px;
        border-radius: 10px;
        font-weight: 700;
    }

    .faq-nav-link:hover .faq-nav-count,
    .faq-nav-link.active .faq-nav-count {
        background: #0284C7;
        color: #FFFFFF;
    }

    /* Support Help Card */
    .faq-support-card {
        background: linear-gradient(135deg, #F8FAFC 0%, #F0F9FF 100%);
        border: 1.5px solid #BAE6FD;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.06);
    }

    .faq-support-card h4 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.05rem;
        font-weight: 800;
        color: #0F172A;
        margin: 0 0 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .faq-support-card p {
        font-size: 0.84rem;
        color: #475569;
        line-height: 1.55;
        margin: 0 0 16px;
    }

    .btn-support-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        background: #0284C7;
        color: #FFFFFF;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.84rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
    }

    .btn-support-cta:hover {
        background: #0369A1;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
        color: #FFFFFF;
    }

    .support-helpline-row {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        color: #64748B;
    }

    .support-helpline-link {
        color: #0284C7;
        font-weight: 700;
        text-decoration: none;
    }

    /* -------------------------------------------------------------
       RIGHT ACCORDION MAIN CONTENT
       ------------------------------------------------------------- */
    /* Controls Strip */
    .faq-controls-strip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 14px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px 20px;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.02);
    }

    .faq-breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #64748B;
        font-weight: 600;
    }

    .faq-breadcrumbs a {
        color: #64748B;
        text-decoration: none;
        transition: color 0.2s;
    }

    .faq-breadcrumbs a:hover {
        color: #0284C7;
    }

    .faq-controls-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-toggle-all {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #1E293B;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-toggle-all:hover {
        background: #F0F9FF;
        border-color: #BAE6FD;
        color: #0284C7;
    }

    /* Search indicator */
    .faq-search-alert {
        display: none;
        align-items: center;
        justify-content: space-between;
        background: #F0F9FF;
        border: 1.5px solid #BAE6FD;
        border-radius: 12px;
        padding: 12px 20px;
        margin-bottom: 24px;
        color: #0369A1;
        font-weight: 700;
        font-size: 0.88rem;
    }

    .faq-search-alert button {
        background: none;
        border: none;
        color: #0284C7;
        text-decoration: underline;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.82rem;
    }

    /* Group Sections */
    .faq-group-section {
        margin-bottom: 44px;
    }

    .faq-group-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #E2E8F0;
    }

    .faq-group-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }

    .faq-group-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.45rem;
        font-weight: 800;
        color: #0F172A;
        margin: 0;
    }

    /* Category Cards */
    .faq-category-card-v2 {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .faq-category-card-v2:hover {
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border-color: #CBD5E1;
    }

    .faq-category-card-v2.highlight-pulse {
        animation: cardPulse 1.2s ease;
    }

    @keyframes cardPulse {
        0% { border-color: #0284C7; box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.25); }
        100% { border-color: #E2E8F0; box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03); }
    }

    .faq-cat-head {
        background: #F8FAFC;
        padding: 18px 26px;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .faq-cat-head-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .faq-cat-icon-chip {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        color: #0284C7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    .faq-cat-head h3 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        color: #0F172A;
        margin: 0;
    }

    .faq-cat-desc {
        font-size: 0.84rem;
        color: #64748B;
        margin: 2px 0 0;
        line-height: 1.5;
    }

    .faq-count-chip {
        background: #E0F2FE;
        color: #0369A1;
        border: 1px solid #BAE6FD;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }

    /* Accordion Item Rows */
    .faq-item-row-v2 {
        border-bottom: 1px solid #F1F5F9;
        transition: background 0.2s;
    }

    .faq-item-row-v2:last-child {
        border-bottom: none;
    }

    .faq-q-btn {
        width: 100%;
        background: transparent;
        border: none;
        padding: 18px 26px;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .faq-q-btn:hover {
        background: #F8FAFC;
    }

    .faq-item-row-v2.open .faq-q-btn {
        background: #F8FAFC;
    }

    .faq-q-left {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        flex: 1;
    }

    .faq-q-indicator {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #E0F2FE;
        color: #0284C7;
        font-size: 0.78rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .faq-q-text {
        font-size: 1.02rem;
        font-weight: 700;
        color: #0F172A;
        line-height: 1.45;
        flex: 1;
        transition: color 0.15s;
    }

    .faq-q-btn:hover .faq-q-text,
    .faq-item-row-v2.open .faq-q-text {
        color: #0284C7;
    }

    .faq-chevron-box {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #F1F5F9;
        color: #64748B;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        flex-shrink: 0;
    }

    .faq-item-row-v2.open .faq-chevron-box {
        background: #0284C7;
        color: #FFFFFF;
        transform: rotate(180deg);
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);
    }

    /* Answer Content */
    .faq-answer-wrap {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0, 1, 0, 1), padding 0.25s ease;
        padding: 0 26px;
        background: #FFFFFF;
    }

    .faq-item-row-v2.open .faq-answer-wrap {
        max-height: 1200px;
        padding: 0 26px 24px;
        transition: max-height 0.45s ease-in, padding 0.25s ease;
    }

    .faq-answer-box {
        background: #F8FAFC;
        border-left: 3.5px solid #0EA5E9;
        border-radius: 0 10px 10px 0;
        padding: 16px 20px;
        font-size: 0.94rem;
        color: #334155;
        line-height: 1.7;
        white-space: pre-line;
    }

    .faq-answer-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #E2E8F0;
        font-size: 0.78rem;
        color: #64748B;
        flex-wrap: wrap;
        gap: 10px;
    }

    .faq-feedback-btns {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-faq-feedback {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 0.76rem;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-faq-feedback:hover {
        background: #F0F9FF;
        border-color: #BAE6FD;
        color: #0284C7;
    }

    .btn-faq-feedback.voted {
        background: #0284C7;
        border-color: #0284C7;
        color: #FFFFFF;
        pointer-events: none;
    }

    /* Highlight Search Marks */
    mark.faq-highlight {
        background: #FEF08A;
        color: #854D0E;
        padding: 1px 3px;
        border-radius: 3px;
        font-weight: 700;
    }

    /* Empty State */
    .faq-empty-state {
        display: none;
        text-align: center;
        padding: 60px 24px;
        background: #FFFFFF;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    }

    .faq-empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #F0F9FF;
        color: #0284C7;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 18px;
    }

    /* Enterprise CTA Callout */
    .faq-cta-card {
        margin-top: 60px;
        background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);
        border-radius: 20px;
        padding: 42px 48px;
        color: #FFFFFF;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 28px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .faq-cta-card::before {
        content: '';
        position: absolute;
        right: -100px;
        bottom: -100px;
        width: 360px;
        height: 360px;
        background: radial-gradient(circle, rgba(2, 132, 199, 0.25) 0%, transparent 70%);
        pointer-events: none;
    }

    @media (max-width: 1024px) {
        .faq-layout-grid {
            grid-template-columns: 1fr;
        }
        .faq-sidebar {
            position: static;
        }
        .faq-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .faq-stats-grid {
            grid-template-columns: 1fr;
        }
        .faq-cta-card {
            padding: 30px 24px;
        }
    }
</style>

<!-- Structured Data: FAQPage -->
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $allFaqs->take(15)->map(function($f) {
        return [
            '@type' => 'Question',
            'name' => $f->question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $f->answer,
            ],
        ];
    })->values()->all(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<!-- 1. FLAGSHIP HERO KNOWLEDGE HUB -->
<div class="faq-hero-v2">
    <div class="faq-hero-wrap">
        <div class="faq-hero-badge">
            <span class="pulse-dot-cyan"></span>
            <span>Aczet Metrology Knowledge Hub • {{ $allFaqs->count() }}+ Technical Answers</span>
        </div>

        <h1 class="faq-hero-title">
            Frequently Asked <span class="text-accent-cyan">Questions</span>
        </h1>

        <p class="faq-hero-subtitle">
            Authoritative answers on precision laboratory balances, industrial scales, non-destructive XRF gold assayer systems, laser spot welders, and analytical instrumentation.
        </p>

        <!-- Live Instant Search Bar -->
        <div class="faq-search-dock">
            <div class="faq-search-wrap">
                <i class="fa-solid fa-magnifying-glass faq-search-icon-left"></i>
                <input type="search" 
                       id="faqSearchInput" 
                       class="faq-search-input" 
                       placeholder="Search questions, models, standards (e.g. calibration, readability, XRF, USP 41, load cells)..." 
                       oninput="filterFaqs()"
                       autocomplete="off"
                       aria-label="Search questions">
                <button type="button" class="faq-search-clear" id="faqSearchClear" onclick="clearFaqSearch()" title="Clear search">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Quick Search Shortcuts -->
        <div class="faq-quick-tags">
            <span style="font-weight:700; color:#64748B;">Popular Shortcuts:</span>
            <button type="button" class="faq-tag-chip" onclick="quickTagSearch('Calibration')">Calibration</button>
            <button type="button" class="faq-tag-chip" onclick="quickTagSearch('Microbalance')">Microbalance</button>
            <button type="button" class="faq-tag-chip" onclick="quickTagSearch('USP')">USP 41 Compliance</button>
            <button type="button" class="faq-tag-chip" onclick="quickTagSearch('XRF')">XRF Gold Assay</button>
            <button type="button" class="faq-tag-chip" onclick="quickTagSearch('Moisture')">Moisture Analyzer</button>
            <button type="button" class="faq-tag-chip" onclick="quickTagSearch('Load Cell')">Load Cells</button>
        </div>

        <!-- 5 Division Filter Tabs -->
        <div class="faq-division-tabs">
            <button type="button" class="faq-div-tab active" onclick="filterByGroup('all', this)">
                <i class="fa-solid fa-layer-group"></i>
                <span>All Topics</span>
                <span class="tab-badge">{{ $allFaqs->count() }}</span>
            </button>

            @foreach($groups as $grp)
                @php
                    $grpSlug = Str::slug($grp);
                    $grpCount = $allFaqs->where('group_name', $grp)->count();
                    $grpIcon = 'fa-solid fa-layer-group';
                    if (str_contains(strtolower($grp), 'laboratory weighing')) $grpIcon = 'fa-solid fa-scale-balanced';
                    elseif (str_contains(strtolower($grp), 'industrial weighing')) $grpIcon = 'fa-solid fa-weight-scale';
                    elseif (str_contains(strtolower($grp), 'jewel') || str_contains(strtolower($grp), 'precious')) $grpIcon = 'fa-solid fa-gem';
                    elseif (str_contains(strtolower($grp), 'laser') || str_contains(strtolower($grp), 'industrial machine')) $grpIcon = 'fa-solid fa-bolt-lightning';
                    elseif (str_contains(strtolower($grp), 'laboratory & analytical') || str_contains(strtolower($grp), 'instrument')) $grpIcon = 'fa-solid fa-flask-vial';
                @endphp
                <button type="button" class="faq-div-tab" onclick="filterByGroup('{{ $grpSlug }}', this)">
                    <i class="{{ $grpIcon }}"></i>
                    <span>{{ $grp }}</span>
                    <span class="tab-badge">{{ $grpCount }}</span>
                </button>
            @endforeach
        </div>
    </div>
</div>

<!-- 2. METROLOGY STATS STRIP -->
<div class="faq-stats-strip">
    <div class="faq-stats-grid">
        <div class="faq-stat-item">
            <div class="faq-stat-icon"><i class="fa-solid fa-circle-question"></i></div>
            <div class="faq-stat-info">
                <strong>{{ $allFaqs->count() }}+ Technical FAQs</strong>
                <span>Peer-reviewed engineering answers</span>
            </div>
        </div>

        <div class="faq-stat-item">
            <div class="faq-stat-icon"><i class="fa-solid fa-list-check"></i></div>
            <div class="faq-stat-info">
                <strong>{{ $categories->count() }} Product Categories</strong>
                <span>Covering full metrology spectrum</span>
            </div>
        </div>

        <div class="faq-stat-item">
            <div class="faq-stat-icon"><i class="fa-solid fa-certificate"></i></div>
            <div class="faq-stat-info">
                <strong>ISO 17025 &amp; OIML R76</strong>
                <span>Certified metrological precision</span>
            </div>
        </div>

        <div class="faq-stat-item">
            <div class="faq-stat-icon"><i class="fa-solid fa-headset"></i></div>
            <div class="faq-stat-info">
                <strong>Direct Application Support</strong>
                <span>Engineers available worldwide</span>
            </div>
        </div>
    </div>
</div>

<!-- 3. MAIN FAQ 2-COLUMN SECTION -->
<div class="faq-main-section">
    <div class="faq-layout-grid">
        <!-- Left Column: Sticky Knowledge Navigator & Support Card -->
        <aside class="faq-sidebar">
            <div class="faq-nav-card">
                <h3 class="faq-nav-card-title">
                    <i class="fa-solid fa-compass" style="color:#0284C7;"></i>
                    <span>Browse Categories</span>
                </h3>
                <ul class="faq-nav-list" id="faqNavList">
                    @foreach($groupedFaqs as $groupName => $cats)
                        @foreach($cats as $categoryName => $catFaqs)
                            @php
                                $catSlug = Str::slug($categoryName);
                                $grpSlug = Str::slug($groupName);
                            @endphp
                            <li class="faq-nav-item" data-group="{{ $grpSlug }}">
                                <a href="#cat-{{ $catSlug }}" class="faq-nav-link" onclick="scrollToCategory('cat-{{ $catSlug }}', event)">
                                    <span>{{ $categoryName }}</span>
                                    <span class="faq-nav-count">{{ $catFaqs->count() }}</span>
                                </a>
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>

            <!-- Direct Support Help Card -->
            <div class="faq-support-card">
                <h4>
                    <i class="fa-solid fa-user-gear" style="color:#0284C7;"></i>
                    <span>Need Custom Guidance?</span>
                </h4>
                <p>
                    Looking for custom calibration limits, USP minimum sample determination, or specific alloy assays?
                </p>
                <button type="button" class="btn-support-cta" onclick="openQuoteModal('FAQ Support CTA', null, 'Metrology Technical Consultation')">
                    <i class="fa-solid fa-comments"></i> Talk to an Engineer
                </button>
                <div class="support-helpline-row">
                    <span>Global Helpline:</span>
                    <a href="tel:+912241415555" class="support-helpline-link">+91 22 4141 5555</a>
                </div>
            </div>
        </aside>

        <!-- Right Column: Accordion Questions & Answers -->
        <main class="faq-content-main">
            <!-- Top Controls Strip -->
            <div class="faq-controls-strip">
                <nav class="faq-breadcrumbs" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
                    <i class="fa-solid fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i>
                    <span>Services &amp; Support</span>
                    <i class="fa-solid fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i>
                    <span style="color:#0284C7; font-weight:700;">Frequently Asked Questions</span>
                </nav>

                <div class="faq-controls-actions">
                    <button type="button" class="btn-toggle-all" onclick="toggleAllFaqs(true)" title="Expand all questions">
                        <i class="fa-solid fa-angles-down"></i> Expand All
                    </button>
                    <button type="button" class="btn-toggle-all" onclick="toggleAllFaqs(false)" title="Collapse all questions">
                        <i class="fa-solid fa-angles-up"></i> Collapse All
                    </button>
                </div>
            </div>

            <!-- Active Search Filter Indicator -->
            <div id="searchResultsCount" class="faq-search-alert">
                <span><i class="fa-solid fa-magnifying-glass"></i> Showing <strong id="matchedCount">0</strong> matching answers for "<span id="searchKeyword"></span>"</span>
                <button type="button" onclick="clearFaqSearch()">Clear Search</button>
            </div>

            <!-- FAQ Groups Loop -->
            @foreach($groupedFaqs as $groupName => $categories)
                @php
                    $grpSlug = Str::slug($groupName);
                    $grpIcon = 'fa-solid fa-layer-group';
                    if (str_contains(strtolower($groupName), 'laboratory weighing')) $grpIcon = 'fa-solid fa-scale-balanced';
                    elseif (str_contains(strtolower($groupName), 'industrial weighing')) $grpIcon = 'fa-solid fa-weight-scale';
                    elseif (str_contains(strtolower($groupName), 'jewel') || str_contains(strtolower($groupName), 'precious')) $grpIcon = 'fa-solid fa-gem';
                    elseif (str_contains(strtolower($groupName), 'laser') || str_contains(strtolower($groupName), 'industrial machine')) $grpIcon = 'fa-solid fa-bolt-lightning';
                    elseif (str_contains(strtolower($groupName), 'laboratory & analytical') || str_contains(strtolower($groupName), 'instrument')) $grpIcon = 'fa-solid fa-flask-vial';
                @endphp

                <section class="faq-group-section" data-group="{{ $grpSlug }}">
                    <!-- Group Header -->
                    <div class="faq-group-header">
                        <div class="faq-group-icon">
                            <i class="{{ $grpIcon }}"></i>
                        </div>
                        <div>
                            <h2 class="faq-group-title">{{ $groupName }}</h2>
                        </div>
                    </div>

                    <!-- Categories Under Group -->
                    @foreach($categories as $categoryName => $catFaqs)
                        @php
                            $catSlug = Str::slug($categoryName);
                            $catIcon = 'fa-solid fa-circle-check';
                            if (str_contains(strtolower($categoryName), 'microbalance')) $catIcon = 'fa-solid fa-feather-pointed';
                            elseif (str_contains(strtolower($categoryName), 'analytical')) $catIcon = 'fa-solid fa-scale-balanced';
                            elseif (str_contains(strtolower($categoryName), 'precision')) $catIcon = 'fa-solid fa-weight-scale';
                            elseif (str_contains(strtolower($categoryName), 'moisture')) $catIcon = 'fa-solid fa-droplet-slash';
                            elseif (str_contains(strtolower($categoryName), 'densit') || str_contains(strtolower($categoryName), 'densimeter')) $catIcon = 'fa-solid fa-cube';
                            elseif (str_contains(strtolower($categoryName), 'weight') || str_contains(strtolower($categoryName), 'test weight')) $catIcon = 'fa-solid fa-certificate';
                            elseif (str_contains(strtolower($categoryName), 'gold') || str_contains(strtolower($categoryName), 'jewel')) $catIcon = 'fa-solid fa-gem';
                            elseif (str_contains(strtolower($categoryName), 'xrf')) $catIcon = 'fa-solid fa-bullseye';
                            elseif (str_contains(strtolower($categoryName), 'laser')) $catIcon = 'fa-solid fa-bolt-lightning';
                            elseif (str_contains(strtolower($categoryName), 'load cell')) $catIcon = 'fa-solid fa-microchip';
                            elseif (str_contains(strtolower($categoryName), 'scale') || str_contains(strtolower($categoryName), 'platform')) $catIcon = 'fa-solid fa-table';
                            elseif (str_contains(strtolower($categoryName), 'refractometer')) $catIcon = 'fa-solid fa-eye';
                            elseif (str_contains(strtolower($categoryName), 'polarimeter')) $catIcon = 'fa-solid fa-compass';
                            elseif (str_contains(strtolower($categoryName), 'viscometer')) $catIcon = 'fa-solid fa-rotate';
                            elseif (str_contains(strtolower($categoryName), 'ultrasonic') || str_contains(strtolower($categoryName), 'sonicator')) $catIcon = 'fa-solid fa-wave-square';
                            elseif (str_contains(strtolower($categoryName), 'hardness')) $catIcon = 'fa-solid fa-capsules';
                            elseif (str_contains(strtolower($categoryName), 'milk')) $catIcon = 'fa-solid fa-glass-water';
                        @endphp

                        <div class="faq-category-card-v2" id="cat-{{ $catSlug }}" data-category="{{ $catSlug }}">
                            <div class="faq-cat-head">
                                <div class="faq-cat-head-left">
                                    <div class="faq-cat-icon-chip">
                                        <i class="{{ $catIcon }}"></i>
                                    </div>
                                    <div>
                                        <h3>{{ $categoryName }}</h3>
                                        @if($catFaqs->first() && $catFaqs->first()->category_intro)
                                            <p class="faq-cat-desc">{{ $catFaqs->first()->category_intro }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="faq-count-chip">{{ $catFaqs->count() }} Questions</span>
                            </div>

                            <!-- Accordion Items Container -->
                            <div class="faq-accordion-list">
                                @foreach($catFaqs as $idx => $faq)
                                    <div class="faq-item-row-v2 {{ $loop->first ? 'open' : '' }}" data-search-text="{{ strtolower($faq->question . ' ' . $faq->answer . ' ' . $categoryName . ' ' . $groupName) }}">
                                        <button type="button" class="faq-q-btn" onclick="toggleFaqAccordionV2(this)" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                            <div class="faq-q-left">
                                                <span class="faq-q-indicator">Q</span>
                                                <span class="faq-q-text">{{ $faq->question }}</span>
                                            </div>
                                            <div class="faq-chevron-box">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </div>
                                        </button>
                                        <div class="faq-answer-wrap">
                                            <div class="faq-answer-box">
                                                <div class="faq-answer-text">{{ $faq->answer }}</div>
                                                <div class="faq-answer-footer">
                                                    <div class="faq-feedback-btns">
                                                        <span>Was this answer helpful?</span>
                                                        <button type="button" class="btn-faq-feedback" onclick="voteFaq(this, true)" title="Helpful answer">
                                                            <i class="fa-regular fa-thumbs-up"></i> Yes
                                                        </button>
                                                        <button type="button" class="btn-faq-feedback" onclick="voteFaq(this, false)" title="Not helpful">
                                                            <i class="fa-regular fa-thumbs-down"></i> No
                                                        </button>
                                                    </div>
                                                    @if($faq->category)
                                                        <a href="{{ route('products.index', ['category' => $faq->category->slug]) }}" style="color:#0284C7; text-decoration:none; font-weight:700;">
                                                            Explore {{ $categoryName }} Products &rarr;
                                                        </a>
                                                    @else
                                                        <button type="button" style="background:none; border:none; color:#0284C7; font-weight:700; cursor:pointer; font-size:0.78rem;" onclick="openQuoteModal('FAQ Product Inquiry', null, '{{ addslashes($categoryName) }}')">
                                                            Request Quote on {{ $categoryName }} &rarr;
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </section>
            @endforeach

            <!-- No Results Found State -->
            <div id="noResultsBox" class="faq-empty-state">
                <div class="faq-empty-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 style="font-family:'Space Grotesk',sans-serif; font-size:1.35rem; font-weight:800; color:#0F172A; margin:0 0 10px;">
                    No Matching Technical Solutions Found
                </h3>
                <p style="color:#64748B; max-width:520px; margin:0 auto 24px; font-size:0.92rem; line-height:1.6;">
                    We couldn't find technical answers matching your keywords. Please try searching for a different term or consult with an Aczet metrology specialist directly.
                </p>
                <button type="button" class="btn-primary" onclick="clearFaqSearch()" style="padding:10px 24px; font-size:0.9rem;">
                    <i class="fa-solid fa-rotate-left"></i> Reset Search &amp; View All FAQs
                </button>
            </div>

            <!-- Bottom Enterprise Assistance Callout -->
            <div class="faq-cta-card">
                <div style="max-width: 620px;">
                    <div style="color: #38BDF8; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px;">
                        Direct Metrology Assistance
                    </div>
                    <h3 style="font-size: clamp(1.4rem, 2.5vw, 1.85rem); font-weight: 800; margin: 0 0 10px; font-family: 'Space Grotesk', sans-serif;">
                        Have a specialized measurement challenge?
                    </h3>
                    <p style="font-size: 0.95rem; color: #94A3B8; margin: 0; line-height: 1.6;">
                        Our engineering and calibration team provides custom USP 41 compliance audits, minimum sample verification, and sample testing trials for cleanrooms and factories worldwide.
                    </p>
                </div>
                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    <a href="{{ route('contact') }}" class="btn-primary" style="text-decoration: none; padding: 13px 26px; font-size: 0.92rem; font-weight: 800;">
                        <i class="fa-solid fa-envelope"></i> Contact Engineering Team
                    </a>
                    <a href="{{ route('service_registration') }}" class="btn-outline" style="text-decoration: none; padding: 13px 22px; font-size: 0.92rem; background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.25); color: #FFF; font-weight: 700;">
                        <i class="fa-solid fa-wrench"></i> Service &amp; Calibration Ticket
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Accordion Toggle
function toggleFaqAccordionV2(btn) {
    const row = btn.closest('.faq-item-row-v2');
    const wasOpen = row.classList.contains('open');

    // Smooth single-open per category card
    const parentCard = btn.closest('.faq-category-card-v2');
    parentCard.querySelectorAll('.faq-item-row-v2').forEach(r => {
        r.classList.remove('open');
        const b = r.querySelector('.faq-q-btn');
        if (b) b.setAttribute('aria-expanded', 'false');
    });

    if (!wasOpen) {
        row.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
    }
}

// Expand or Collapse All
function toggleAllFaqs(expand) {
    document.querySelectorAll('.faq-item-row-v2').forEach(row => {
        const btn = row.querySelector('.faq-q-btn');
        if (expand) {
            row.classList.add('open');
            if (btn) btn.setAttribute('aria-expanded', 'true');
        } else {
            row.classList.remove('open');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        }
    });
}

// Filter by Business Group
let currentActiveGroup = 'all';
function filterByGroup(groupSlug, btn) {
    currentActiveGroup = groupSlug;

    // Update active tab state
    document.querySelectorAll('.faq-div-tab').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    // Update sidebar nav items visibility
    document.querySelectorAll('.faq-nav-item').forEach(item => {
        const itemGroup = item.getAttribute('data-group');
        if (groupSlug === 'all' || itemGroup === groupSlug) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });

    // Run filter
    applyFilters();
}

// Live Search Filter with debounce
let searchDebounce = null;
function filterFaqs() {
    if (searchDebounce) clearTimeout(searchDebounce);
    searchDebounce = setTimeout(applyFilters, 100);
}

// Quick Search Tag Click
function quickTagSearch(keyword) {
    const input = document.getElementById('faqSearchInput');
    input.value = keyword;
    currentActiveGroup = 'all';
    document.querySelectorAll('.faq-div-tab').forEach(b => b.classList.remove('active'));
    const firstTab = document.querySelector('.faq-div-tab');
    if (firstTab) firstTab.classList.add('active');
    applyFilters();
    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function clearFaqSearch() {
    document.getElementById('faqSearchInput').value = '';
    currentActiveGroup = 'all';
    document.querySelectorAll('.faq-div-tab').forEach(b => b.classList.remove('active'));
    const firstTab = document.querySelector('.faq-div-tab');
    if (firstTab) firstTab.classList.add('active');

    document.querySelectorAll('.faq-nav-item').forEach(item => {
        item.style.display = '';
    });

    applyFilters();
}

// Category jump smooth scroll
function scrollToCategory(elementId, e) {
    if (e) e.preventDefault();
    const el = document.getElementById(elementId);
    if (!el) return;

    // Reset group filter if the category is hidden under another group
    const parentSection = el.closest('.faq-group-section');
    if (parentSection && parentSection.style.display === 'none') {
        currentActiveGroup = 'all';
        document.querySelectorAll('.faq-div-tab').forEach(b => b.classList.remove('active'));
        const firstTab = document.querySelector('.faq-div-tab');
        if (firstTab) firstTab.classList.add('active');
        applyFilters();
    }

    // Highlight card
    el.classList.add('highlight-pulse');
    setTimeout(() => el.classList.remove('highlight-pulse'), 1500);

    // Open first question
    const firstRow = el.querySelector('.faq-item-row-v2');
    if (firstRow) firstRow.classList.add('open');

    // Smooth scroll
    const yOffset = -100;
    const y = el.getBoundingClientRect().top + window.pageYOffset + yOffset;
    window.scrollTo({ top: y, behavior: 'smooth' });

    // Update active nav link
    document.querySelectorAll('.faq-nav-link').forEach(l => l.classList.remove('active'));
    const targetLink = document.querySelector(`a[href="#${elementId}"]`);
    if (targetLink) targetLink.classList.add('active');
}

// User Feedback Voting
function voteFaq(btn, helpful) {
    btn.classList.add('voted');
    const container = btn.closest('.faq-feedback-btns');
    if (container) {
        container.innerHTML = `<span style="color:#0284C7; font-weight:700;"><i class="fa-solid fa-circle-check"></i> Thank you for your feedback!</span>`;
    }
}

// Main Filter Function
function applyFilters() {
    const input = document.getElementById('faqSearchInput');
    const clearBtn = document.getElementById('faqSearchClear');
    const query = input.value.trim().toLowerCase();
    const searchAlert = document.getElementById('searchResultsCount');
    const searchKeyword = document.getElementById('searchKeyword');
    const matchedCountEl = document.getElementById('matchedCount');
    const noResults = document.getElementById('noResultsBox');

    if (query) {
        clearBtn.style.display = 'inline-flex';
        searchAlert.style.display = 'flex';
        searchKeyword.innerText = query;
    } else {
        clearBtn.style.display = 'none';
        searchAlert.style.display = 'none';
    }

    let totalVisibleFaqs = 0;
    const groupSections = document.querySelectorAll('.faq-group-section');

    groupSections.forEach(section => {
        const sectionGroup = section.getAttribute('data-group');
        const groupMatches = (currentActiveGroup === 'all' || currentActiveGroup === sectionGroup);

        if (!groupMatches) {
            section.style.display = 'none';
            return;
        }

        let visibleCardsInSection = 0;
        const categoryCards = section.querySelectorAll('.faq-category-card-v2');

        categoryCards.forEach(card => {
            let visibleRowsInCard = 0;
            const rows = card.querySelectorAll('.faq-item-row-v2');

            rows.forEach(row => {
                const text = row.getAttribute('data-search-text');
                const rowMatches = !query || text.includes(query);

                if (rowMatches) {
                    row.style.display = '';
                    visibleRowsInCard++;
                    totalVisibleFaqs++;
                    if (query) {
                        row.classList.add('open');
                    }
                } else {
                    row.style.display = 'none';
                    if (query) {
                        row.classList.remove('open');
                    }
                }
            });

            if (visibleRowsInCard > 0) {
                card.style.display = '';
                visibleCardsInSection++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCardsInSection > 0) {
            section.style.display = '';
        } else {
            section.style.display = 'none';
        }
    });

    if (matchedCountEl) matchedCountEl.innerText = totalVisibleFaqs;

    if (totalVisibleFaqs === 0) {
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
    }
}
</script>
@endsection
