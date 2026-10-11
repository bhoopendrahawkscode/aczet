@extends('layouts.app')

@section('title', $blog->title . ' - Aczet')

@section('content')
<div class="container-responsive" style="max-width: 900px; padding-top: 30px; padding-bottom: 60px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('blogs.index') }}" style="text-decoration: none; color: var(--text-muted); font-size: 0.88rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-arrow-left"></i> Back to All Articles
        </a>
    </div>

    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 12px;">
        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; {{ $blog->type === 'News' ? 'background: #FEE2E2; color: #B91C1C;' : ($blog->type === 'Press Release' ? 'background: #F3E8FF; color: #6B21A8;' : 'background: #DBEAFE; color: #1E40AF;') }}">
            {{ $blog->type ?? 'Blog' }}
        </span>
        <span style="color: var(--accent); font-weight: 700; font-size: 0.85rem;">
            By {{ $blog->author ?: 'Aczet Team' }} &bull; {{ $blog->published_at ? $blog->published_at->format('F d, Y') : 'Recent' }}
        </span>
    </div>
    
    <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.6rem, 3.5vw, 2.5rem); font-weight: 800; color: var(--primary); margin: 0 0 24px; line-height: 1.25;">
        {{ $blog->title }}
    </h1>

    @if($blog->image)
        <div style="width: 100%; border-radius: 14px; overflow: hidden; background: #0F172A; margin-bottom: 32px; display: flex; align-items: center; justify-content: center; max-height: 480px;">
            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" style="width: 100%; height: clamp(220px, 45vw, 450px); object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
        </div>
    @endif

    <div style="font-size: 1.05rem; line-height: 1.85; color: #334155;">
        @if($blog->summary)
            <div style="font-size: 1.15rem; font-weight: 600; color: var(--primary); margin-bottom: 24px; padding: 18px 24px; background: #F8FAFC; border-left: 4px solid var(--accent); border-radius: 0 8px 8px 0; line-height: 1.6;">
                {{ $blog->summary }}
            </div>
        @endif

        <div style="white-space: pre-line;">
            {!! nl2br(e($blog->content)) !!}
        </div>
    </div>
</div>
@endsection
