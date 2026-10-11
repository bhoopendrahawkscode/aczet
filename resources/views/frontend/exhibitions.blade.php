@extends('layouts.app')

@section('title', ($t['nav_exhibitions'] ?? 'Upcoming Exhibitions & Events') . ' - Aczet')

@section('content')
<div class="page-banner">
    <h1>
        {{ $t['nav_exhibitions'] ?? 'Upcoming Exhibitions & Events' }}
    </h1>
    <p>
        {{ $t['exhibitions_sub'] ?? 'Meet our team of metrology specialists at global trade expos around the world.' }}
    </p>
</div>

<div class="container-responsive">
    <div class="card-grid-responsive">
        @foreach($exhibitions as $ex)
            <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; width: 100%;">
                <div style="background: linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%); height: 190px; display: flex; align-items: center; justify-content: center; padding: 16px; border-bottom: 1px solid #F1F5F9;">
                    <img src="{{ $ex->image_url }}" alt="{{ $ex->getLocalizedTitle($currentLocale) }}" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';" style="max-width: 90%; max-height: 90%; object-fit: contain;">
                </div>
                
                <div style="padding: clamp(16px, 3vw, 24px); flex: 1; display: flex; flex-direction: column;">
                    <span style="background: #FFF5F0; color: var(--accent); font-weight: 700; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; text-transform: uppercase; width: fit-content;">
                        {{ $ex->is_upcoming ? ($t['upcoming_event'] ?? 'Upcoming Event') : ($t['past_event'] ?? 'Past Exhibition') }}
                    </span>
                    
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin: 10px 0 6px;">
                        {{ $ex->getLocalizedTitle($currentLocale) }}
                    </h3>
                    
                    <p style="font-size: 0.88rem; color: var(--accent); font-weight: 700; margin-bottom: 8px;">
                        <i class="fa-solid fa-calendar"></i> {{ $ex->event_date->format('F d, Y') }}
                    </p>

                    <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 14px;">
                        <i class="fa-solid fa-location-dot"></i> {{ $ex->location }} ({{ $ex->hall_booth }})
                    </p>

                    <p style="font-size: 0.88rem; color: #475569; line-height: 1.5; margin-bottom: 18px; flex: 1;">
                        {{ $ex->getLocalizedDescription($currentLocale) }}
                    </p>

                    <button class="btn-primary" style="width: 100%; justify-content: center; padding: 10px; font-size: 0.85rem; margin-top: auto;" onclick="openQuoteModal('Exhibition Booth Meeting Registration', null, 'Meeting at ' + '{{ addslashes($ex->getLocalizedTitle($currentLocale)) }}')">
                        <i class="fa-solid fa-handshake"></i> {{ $t['schedule_meeting'] ?? 'Schedule Booth Meeting' }}
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
