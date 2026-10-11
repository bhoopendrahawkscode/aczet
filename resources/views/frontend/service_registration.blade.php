@extends('layouts.app')

@section('title', ($t['nav_service'] ?? 'Service & Warranty Registration') . ' - Aczet')

@section('content')
<!-- Breadcrumb Navigation -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 12px 0;">
    <div style="width: min(1200px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #94A3B8; font-size: 11px;">&gt;</li>
                <li style="color: #0F172A; font-weight: 600;">Service &amp; Warranty Registration</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-banner">
    <h1>
        {{ $t['nav_service'] ?? 'Service & Warranty Registration' }}
    </h1>
    <p>
        {{ $t['service_sub'] ?? 'Register your Aczet product for warranty validation and technical support tickets.' }}
    </p>
</div>

<div style="background: #F8FAFC; padding: 40px 0 70px; border-top: 1px solid #F1F5F9;">
    <div class="container-responsive" style="max-width: 900px; margin: 0 auto;">
        <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: clamp(24px, 4vw, 44px); box-shadow: var(--shadow-sm);">
            <h2 style="font-size: clamp(1.3rem, 2.5vw, 1.45rem); font-weight: 800; color: #0F172A; margin-bottom: 22px;">
                <i class="fa-solid fa-wrench" style="color:#0284C7;"></i> {{ $t['service_form_title'] ?? 'Product Service Ticket Form' }}
            </h2>

        <form action="{{ route('service.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px; color: var(--primary);">{{ $t['modal_name'] ?? 'Customer Name' }} *</label>
                    <input type="text" name="customer_name" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px; color: var(--primary);">{{ $t['modal_email'] ?? 'Email Address' }} *</label>
                    <input type="email" name="email" required class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px; color: var(--primary);">{{ $t['modal_phone'] ?? 'Phone Number' }} *</label>
                    <input type="text" name="phone" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px; color: var(--primary);">{{ $t['product_model_name'] ?? 'Product Model / Name' }} *</label>
                    <input type="text" name="product_name" required placeholder="e.g. CY 224 Analytical Balance" class="form-control">
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px; color: var(--primary);">{{ $t['serial_number'] ?? 'Serial Number (S/N)' }} *</label>
                <input type="text" name="serial_number" required placeholder="Found on barcode tag on back of unit" class="form-control">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px; color: var(--primary);">{{ $t['issue_description'] ?? 'Description of Issue or Service Required' }} *</label>
                <textarea name="issue_description" rows="4" required placeholder="Provide error code or details..." class="form-control"></textarea>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-weight: 800; border-radius: 10px;">
                <i class="fa-solid fa-headset"></i> {{ $t['log_service_ticket'] ?? 'Submit Service & Warranty Ticket' }}
            </button>
        </form>
    </div>
</div>
</div>
@endsection
