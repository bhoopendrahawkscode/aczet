@extends('layouts.admin')

@section('title', 'Lead Management CMS - Aczet')
@section('page_title', 'Lead & Inquiry Management')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
        <form action="{{ route('admin.leads') }}" method="GET" style="display: flex; gap: 12px; flex: 1; flex-wrap: wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, email, company..." style="padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; flex: 1; min-width: 200px;">
            
            <select name="status" style="padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
                <option value="">All Statuses</option>
                <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>New</option>
                <option value="Contacted" {{ request('status') == 'Contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>

            <select name="region" style="padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
                <option value="">All Regions</option>
                @foreach(['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'] as $reg)
                    <option value="{{ $reg }}" {{ request('region') == $reg ? 'selected' : '' }}>{{ $reg }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-sm btn-outline" style="padding: 10px 16px;"><i class="fa-solid fa-search"></i> Filter</button>
        </form>

        <a href="{{ route('admin.leads.export', request()->query()) }}" class="btn-sm btn-success" style="padding: 10px 16px;">
            <i class="fa-solid fa-file-excel"></i> Export CSV / Excel
        </a>
    </div>

    <table class="table-custom">
        <thead>
            <tr>
                <th>ID</th>
                <th>Submitted</th>
                <th>Lead Contact</th>
                <th>Company</th>
                <th>Product Tag</th>
                <th>Region</th>
                <th>Source</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leads as $lead)
                <tr>
                    <td>#{{ $lead->id }}</td>
                    <td>{{ $lead->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <strong style="color: var(--primary);">{{ $lead->name }}</strong><br>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $lead->email }} | {{ $lead->phone }}</span>
                    </td>
                    <td>{{ $lead->company ?? 'N/A' }}</td>
                    <td>{{ $lead->product ? $lead->product->name : 'General Inquiry' }}</td>
                    <td><span class="badge" style="background:#E2E8F0;">{{ $lead->region }}</span></td>
                    <td><span style="font-size: 0.8rem; color: var(--text-muted);">{{ $lead->source }}</span></td>
                    <td>
                        <form action="{{ route('admin.leads.update_status', $lead->id) }}" method="POST">
                            @csrf
                            <select name="status" onchange="this.form.submit()" style="padding: 4px; font-size: 0.8rem; border-radius: 4px; border: 1px solid var(--border-color);">
                                <option value="New" {{ $lead->status === 'New' ? 'selected' : '' }}>New</option>
                                <option value="Contacted" {{ $lead->status === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="In Progress" {{ $lead->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Closed" {{ $lead->status === 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.leads.delete', $lead->id) }}" method="POST" onsubmit="return confirm('Delete lead record?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $leads->links() }}
    </div>
</div>
@endsection
