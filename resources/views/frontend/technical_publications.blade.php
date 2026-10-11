@extends('layouts.app')

@section('title', 'Technical Publications & Whitepapers - Aczet')

@section('content')
<div class="page-banner">
    <h1>Technical Publications & Compliance Whitepapers</h1>
    <p>In-depth guides on metrological standards, USP compliance, and calibration science.</p>
</div>

<div class="container-responsive" style="max-width: 1200px;">
    <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
        @foreach($publications as $pub)
            <div style="background: #FFF; border-radius: 12px; border: 1px solid var(--border-color); padding: clamp(18px, 3.5vw, 28px); box-shadow: var(--shadow-sm); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div style="flex: 1 1 240px; min-width: 0;">
                    <span style="background: #F1F5F9; color: var(--accent); font-weight: 700; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; border: 1px solid #E2E8F0; display: inline-block;">
                        {{ $pub->category }}
                    </span>
                    <h3 style="font-size: clamp(1.1rem, 2.5vw, 1.3rem); font-weight: 700; color: var(--primary); margin: 8px 0 6px;">
                        {{ $pub->getLocalizedTitle($currentLocale) }}
                    </h3>
                    <p style="font-size: 0.9rem; color: #475569; line-height: 1.6; margin: 0;">
                        {{ $pub->getLocalizedSummary($currentLocale) }}
                    </p>
                </div>

                <a href="{{ asset($pub->file) }}" download class="btn-primary" style="padding: 10px 18px; font-size: 0.85rem; white-space: nowrap; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-file-arrow-down"></i> Download PDF
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
