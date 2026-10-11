@extends('layouts.app')

@section('title', ($t['nav_dealer'] ?? 'Distributor & Dealer Portal') . ' - Aczet')

@section('content')
<div class="page-banner">
    <h1>
        {{ $t['dealer_title'] ?? 'Global Distributor & Dealer Network' }}
    </h1>
    <p>
        {{ $t['dealer_sub'] ?? 'Partner with Aczet to distribute high-precision analytical equipment in your region.' }}
    </p>
</div>

<div class="container-responsive" style="max-width: 900px;">
    <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: clamp(20px, 4vw, 40px); box-shadow: var(--shadow-md);">
        <h2 style="font-size: clamp(1.3rem, 2.5vw, 1.5rem); font-weight: 800; color: var(--primary); margin-bottom: 6px;">
            {{ $t['apply_dealership'] ?? 'Apply for Authorized Dealership' }}
        </h2>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 20px;">
            {{ $t['dealership_benefits'] ?? 'Gain access to competitive wholesale pricing, OEM technical support, and regional exclusivity.' }}
        </p>

        <form action="{{ route('dealer.register') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['modal_company'] ?? 'Company Name' }} *</label>
                    <input type="text" name="company_name" required class="form-control" placeholder="e.g. MetroLab Scientific Instruments">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['contact_person'] ?? 'Contact Person' }} *</label>
                    <input type="text" name="contact_person" required class="form-control" placeholder="e.g. Marcus Vance">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['modal_email'] ?? 'Corporate Email' }} *</label>
                    <input type="email" name="email" required class="form-control" placeholder="marcus@metrolab.com">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['modal_phone'] ?? 'Phone Number' }} *</label>
                    <input type="text" name="phone" required class="form-control" placeholder="+49 69 123456">
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['country_territory'] ?? 'Country / Territory' }} *</label>
                <input type="text" name="country" required class="form-control" placeholder="e.g. Germany & Central Europe">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: var(--primary);">{{ $t['portfolio_desc'] ?? 'Company Address & Business Portfolio' }}</label>
                <textarea name="address" rows="4" class="form-control" placeholder="Detail existing laboratory distribution networks and warehouse capabilities..."></textarea>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-weight: 800; border-radius: 10px;">
                <i class="fa-solid fa-paper-plane"></i> {{ $t['submit_dealership'] ?? 'Submit Dealership Application' }}
            </button>
        </form>
    </div>
</div>
@endsection
