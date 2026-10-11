@extends('layouts.app')

@section('title', ($t['nav_careers'] ?? 'Careers & Openings') . ' - Aczet')

@section('content')
<div class="page-banner">
    <h1>
        {{ $t['nav_careers'] ?? 'Careers at Aczet' }}
    </h1>
    <p>
        {{ $t['careers_sub'] ?? 'Join our engineering, metrology, and global sales teams shaping scientific innovation.' }}
    </p>
</div>

<div class="container-responsive" style="max-width: 1100px;">
    <div style="display: flex; flex-direction: column; gap: 20px;">
        @foreach($careers as $job)
            <div style="background: #FFF; border-radius: 12px; border: 1px solid var(--border-color); padding: clamp(18px, 3.5vw, 24px); display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow-sm); flex-wrap: wrap; gap: 16px;">
                <div style="flex: 1 1 260px; min-width: 0;">
                    <span style="background: #E2E8F0; color: var(--text-dark); font-weight: 700; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; display: inline-block;">
                        {{ $job->department }}
                    </span>
                    <h3 style="font-size: clamp(1.1rem, 2.5vw, 1.25rem); font-weight: 700; color: var(--primary); margin: 8px 0 4px;">{{ $job->title }}</h3>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px;">
                        <i class="fa-solid fa-location-dot"></i> {{ $job->location }} &bull; <i class="fa-solid fa-briefcase"></i> {{ $job->type }} &bull; {{ $t['experience'] ?? 'Experience' }}: {{ $job->experience }}
                    </p>
                    <p style="font-size: 0.88rem; color: #475569; margin: 0; line-height: 1.5;">{{ $job->description }}</p>
                </div>

                <button class="btn-primary" style="white-space: nowrap; padding: 10px 18px; font-size: 0.85rem;" onclick="openQuoteModal('Job Application: ' + '{{ addslashes($job->title) }}', null, 'Application for {{ addslashes($job->title) }}')">
                    {{ $t['apply_now'] ?? 'Apply Now' }}
                </button>
            </div>
        @endforeach
    </div>
</div>
@endsection
