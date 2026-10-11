@extends('layouts.app')

@section('title', 'Customer Portal - Aczet Metrology')
@section('meta_description', 'Manage your Aczet client account, review recent quotation requests, and access technical metrology resources.')

@section('content')
<!-- Header Strip -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 14px 0;">
    <div style="width: min(1280px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #00A3C4; font-size: 11px;">&gt;</li>
                <li style="color: #0F172A; font-weight: 600;">Customer Portal</li>
            </ol>
        </nav>
    </div>
</div>

<div style="background: #F8FAFC; padding: 40px 0 80px; min-height: 70vh;">
    <div style="width: min(1200px, calc(100% - 48px)); margin: 0 auto;">
        
        <!-- Welcome Card -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 30px 36px; margin-bottom: 30px; box-shadow: 0 4px 16px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 18px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(0, 163, 196, 0.12); color: #00A3C4; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 800;">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div>
                    <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: #00A3C4; letter-spacing: 0.1em; display: block; margin-bottom: 2px;">VERIFIED CLIENT ACCOUNT</span>
                    <h1 style="font-size: 1.7rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">
                        Welcome, {{ $user->name }}
                    </h1>
                    <p style="font-size: 0.88rem; color: #64748B; margin: 0;">
                        {{ $user->email }} {{ $user->company ? '• ' . $user->company : '' }}
                    </p>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('products.index') }}" style="background: #00A3C4; color: #FFFFFF; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 0.88rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-flask"></i> Browse Catalog
                </a>
                <form action="{{ route('customer.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: #FFFFFF; color: #EF4444; border: 1px solid #FECACA; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 30px; align-items: start;" class="customer-dashboard-grid">
            
            <!-- Left: Recent Quotes & Inquiries -->
            <div>
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 28px; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
                        <h3 style="font-size: 1.18rem; font-weight: 800; color: #0F172A; margin: 0; font-family: 'Space Grotesk', sans-serif;">
                            <i class="fa-solid fa-file-invoice" style="color: #00A3C4; margin-right: 8px;"></i> Recent Quotation Inquiries &amp; RFQs
                        </h3>
                        <span style="font-size: 0.8rem; font-weight: 700; color: #64748B;">Total: {{ $recentInquiries->count() }}</span>
                    </div>

                    @if($recentInquiries->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            @foreach($recentInquiries as $inq)
                                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap;">
                                    <div>
                                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #0F172A; margin: 0 0 4px;">
                                            {{ $inq->product ? $inq->product->name : ($inq->source ?? 'General Inquiry') }}
                                        </h4>
                                        <span style="font-size: 0.78rem; color: #64748B;">
                                            Submitted on {{ $inq->created_at->format('M d, Y h:i A') }} • Region: {{ $inq->region }}
                                        </span>
                                    </div>
                                    <div>
                                        <span style="background: rgba(0, 163, 196, 0.1); color: #00A3C4; padding: 4px 10px; border-radius: 20px; font-size: 0.76rem; font-weight: 700;">
                                            {{ $inq->status ?? 'Received' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 40px 20px; color: #64748B;">
                            <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; color: #CBD5E1; margin-bottom: 12px; display: block;"></i>
                            <h4 style="font-size: 1rem; font-weight: 700; color: #0F172A; margin: 0 0 6px;">No Quotation Inquiries Logged Yet</h4>
                            <p style="font-size: 0.88rem; margin: 0 0 16px;">Browse our precision metrology instruments and request quotes directly with your account.</p>
                            <a href="{{ route('products.index') }}" style="background: #00A3C4; color: #FFFFFF; padding: 8px 18px; border-radius: 6px; font-weight: 700; font-size: 0.84rem; text-decoration: none;">
                                Explore Laboratory Balances
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Quick Client Services & Profile Card -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                    <h4 style="font-size: 1rem; font-weight: 800; color: #0F172A; margin: 0 0 14px; font-family: 'Space Grotesk', sans-serif;">
                        Account Profile
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.86rem; color: #475569;">
                        <div>
                            <strong style="color: #0F172A; display: block;">Name:</strong> {{ $user->name }}
                        </div>
                        <div>
                            <strong style="color: #0F172A; display: block;">Email:</strong> {{ $user->email }}
                        </div>
                        @if($user->phone)
                            <div>
                                <strong style="color: #0F172A; display: block;">Phone:</strong> {{ $user->phone }}
                            </div>
                        @endif
                        @if($user->company)
                            <div>
                                <strong style="color: #0F172A; display: block;">Company:</strong> {{ $user->company }}
                            </div>
                        @endif
                    </div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 24px; border-left: 3px solid #00A3C4;">
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; margin: 0 0 8px;">
                        Need Support or Service?
                    </h4>
                    <p style="font-size: 0.82rem; color: #64748B; margin: 0 0 14px; line-height: 1.5;">
                        Register your equipment for warranty, schedule calibration or contact technical support.
                    </p>
                    <a href="{{ route('service_registration') }}" style="font-size: 0.82rem; font-weight: 700; color: #00A3C4; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                        Equipment Service Registration &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @media (max-width: 900px) {
        .customer-dashboard-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

