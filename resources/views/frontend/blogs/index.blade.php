@extends('layouts.app')

@section('title', ($t['nav_blog'] ?? 'Blog & News') . ' - Aczet')

@section('content')
<div class="page-banner">
    <h1>
        {{ $t['nav_blog'] ?? 'Blog & News' }}
    </h1>
    <p>
        {{ $t['blog_sub'] ?? 'Articles on laboratory metrology, ISO compliance, precision weighing technology, and company news.' }}
    </p>
</div>

<div class="container-responsive">
    <!-- Filter Tabs -->
    <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 36px;">
        <a href="{{ route('blogs.index') }}" style="text-decoration: none; padding: 8px 20px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s; {{ (!isset($currentType) || $currentType === 'all') ? 'background: var(--primary); color: #FFF;' : 'background: #F1F5F9; color: var(--text-dark); border: 1px solid var(--border-color);' }}">
            All Articles
        </a>
        <a href="{{ route('blogs.index', ['type' => 'Blog']) }}" style="text-decoration: none; padding: 8px 20px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s; {{ (isset($currentType) && $currentType === 'Blog') ? 'background: var(--primary); color: #FFF;' : 'background: #F1F5F9; color: var(--text-dark); border: 1px solid var(--border-color);' }}">
            <i class="fa-solid fa-book-open"></i> Technical Blogs
        </a>
        <a href="{{ route('blogs.index', ['type' => 'News']) }}" style="text-decoration: none; padding: 8px 20px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s; {{ (isset($currentType) && $currentType === 'News') ? 'background: var(--primary); color: #FFF;' : 'background: #F1F5F9; color: var(--text-dark); border: 1px solid var(--border-color);' }}">
            <i class="fa-solid fa-newspaper"></i> Company News
        </a>
    </div>

    @if($blogs->isNotEmpty())
        <div class="card-grid-responsive">
            @foreach($blogs as $blog)
                <div style="background: #FFF; border-radius: 12px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; width: 100%; transition: transform 0.2s;">
                    <div style="background: linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%); height: 190px; display: flex; align-items: center; justify-content: center; padding: 16px; position: relative; border-bottom: 1px solid #F1F5F9;">
                        @if($blog->image)
                            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" style="max-width: 90%; max-height: 90%; object-fit: contain;" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                        @else
                            <img src="{{ asset('images/products/noimage.jpeg') }}" alt="{{ $blog->title }}" style="max-width: 90%; max-height: 90%; object-fit: contain; opacity: 0.6;">
                        @endif

                        <span style="position: absolute; top: 12px; right: 12px; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; {{ $blog->type === 'News' ? 'background: #FEE2E2; color: #B91C1C;' : ($blog->type === 'Press Release' ? 'background: #F3E8FF; color: #6B21A8;' : 'background: #DBEAFE; color: #1E40AF;') }}">
                            {{ $blog->type ?? 'Blog' }}
                        </span>
                    </div>
                    
                    <div style="padding: clamp(16px, 3vw, 24px); flex: 1; display: flex; flex-direction: column;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-size: 0.78rem; color: var(--accent); font-weight: 700;">
                                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Recent' }}
                            </span>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">
                                By {{ $blog->author ?: 'Aczet Team' }}
                            </span>
                        </div>

                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin: 4px 0 10px; line-height: 1.35;">
                            <a href="{{ route('blogs.show', $blog->slug) }}" style="color: inherit; text-decoration: none;">{{ $blog->title }}</a>
                        </h3>

                        <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 18px; flex: 1;">
                            {{ $blog->summary ?: Str::limit(strip_tags($blog->content), 120) }}
                        </p>

                        <a href="{{ route('blogs.show', $blog->slug) }}" style="font-weight: 700; color: var(--primary); font-size: 0.88rem; text-decoration:none; display: inline-flex; align-items: center; gap: 6px; margin-top: auto;">
                            {{ $t['read_more'] ?? 'Read Full Article' }} <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($blogs->hasPages())
            <div style="margin-top: 40px; width: 100%;">
                {{ $blogs->links() }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
            <i class="fa-solid fa-newspaper" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 16px; display: block;"></i>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">No Articles Found</h3>
            <p style="margin-top: 8px;">There are currently no articles in this category. Check back soon for updates.</p>
        </div>
    @endif
</div>
@endsection
