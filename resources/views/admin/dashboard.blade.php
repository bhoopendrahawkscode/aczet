@extends('layouts.admin')

@section('title', 'Admin Dashboard - Aczet CMS')
@section('page_title', 'Dashboard Overview')

@section('content')
<!-- Metrics Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-bottom: 30px;">
    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #FFF5F0; color: var(--accent); width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
        <div>
            <h3 style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">{{ $totalLeads }}</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Total Inquiry Leads</p>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #EFF6FF; color: #2563EB; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
        <div>
            <h3 style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">{{ $totalProducts }}</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Catalog Instruments</p>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #ECFDF5; color: #059669; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-handshake"></i>
        </div>
        <div>
            <h3 style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">{{ $totalDealers }}</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Distributor Partners</p>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #F5F3FF; color: #7C3AED; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-calendar-star"></i>
        </div>
        <div>
            <h3 style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">{{ $totalExhibitions }}</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Global Exhibitions</p>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #FFFBEB; color: #D97706; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-photo-film"></i>
        </div>
        <div>
            <h3 style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">{{ $totalGalleryItems }}</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Gallery Photos</p>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #F0FDF4; color: #16A34A; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-newspaper"></i>
        </div>
        <div>
            <h3 style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">{{ $totalBlogs }}</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Blog & News Posts</p>
        </div>
    </div>
</div>

<!-- Recent Leads Table -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">Recent Inquiries & Leads</h3>
        <a href="{{ route('admin.leads') }}" class="btn-sm btn-outline"><i class="fa-solid fa-list"></i> View All Leads</a>
    </div>

    <table class="table-custom">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Lead Name</th>
                <th>Company</th>
                <th>Product Interest</th>
                <th>Region</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentLeads as $lead)
                <tr>
                    <td>#{{ $lead->id }}</td>
                    <td>{{ $lead->created_at->format('M d, Y') }}</td>
                    <td style="font-weight: 700;">{{ $lead->name }}<br><span style="font-weight: normal; font-size: 0.8rem; color: var(--text-muted);">{{ $lead->email }}</span></td>
                    <td>{{ $lead->company ?? 'N/A' }}</td>
                    <td>{{ $lead->product ? $lead->product->name : 'General Inquiry' }}</td>
                    <td><span class="badge" style="background:#E2E8F0;">{{ $lead->region }}</span></td>
                    <td>
                        <span class="badge {{ $lead->status === 'New' ? 'badge-danger' : ($lead->status === 'In Progress' ? 'badge-warning' : 'badge-success') }}">
                            {{ $lead->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pending Dealer Requests -->
@if($pendingDealers->count() > 0)
    <div class="card" style="border-left: 4px solid var(--accent);">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 16px;">Pending Dealer Registrations</h3>
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Country</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingDealers as $dealer)
                    <tr>
                        <td style="font-weight: 700;">{{ $dealer->company_name }}</td>
                        <td>{{ $dealer->contact_person }}</td>
                        <td>{{ $dealer->email }}</td>
                        <td>{{ $dealer->country }}</td>
                        <td>
                            <form action="{{ route('admin.dealers.approve', $dealer->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-sm btn-success">Approve Dealer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
