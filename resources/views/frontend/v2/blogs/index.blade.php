@extends('layouts.app')

@php
    $currentLocale = $currentLocale ?? app()->getLocale();
    $currentRegion = $currentRegion ?? session('aczet_region', 'India');
    $blogs = $blogs ?? \App\Models\Blog::orderBy('published_at', 'desc')->paginate(9)->withQueryString();
    $currentType = $currentType ?? 'all';
    $types = ['Blog', 'News', 'Press Release'];
@endphp

@section('title', 'Technical Blog & News | Aczet Metrology')
@section('meta_description', 'Latest insights, technical articles, industry news, and corporate updates from Aczet\'s metrology experts.')

@section('styles')
<style>
    .blogs-page {
        background: var(--color-bg-primary);
    }
    .blogs-header {
        background: linear-gradient(135deg, var(--color-void) 0%, var(--color-navy) 60%, var(--color-slate) 100%);
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .blogs-header::before {
        content: '';
        position: absolute;
        top: -120px;
        left: 50%;
        transform: translateX(-50%);
        width: 900px;
        height: 450px;
        background: radial-gradient(circle, rgba(232, 93, 15, 0.15) 0%, rgba(14, 165, 233, 0.1) 40%, transparent 70%);
        filter: blur(80px);
        pointer-events: none;
    }
    .type-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }
    .type-btn {
        @apply px-4 py-2 text-sm font-semibold rounded-full transition-all duration-200 focus-ring;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: #E2E8F0;
    }
    .type-btn:hover {
        @apply bg-white/20 border-white/30;
    }
    .type-btn.active {
        @apply bg-orange-500 border-orange-500 text-white;
    }
    .blog-card {
        @apply bg-white border border-[var(--color-border-light)] rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-[var(--color-border-medium)] hover:-translate-y-1 flex flex-col;
    }
    .blog-image {
        @apply aspect-video bg-[var(--color-border-light)] overflow-hidden relative;
    }
    .blog-image img {
        @apply w-full h-full object-cover transition-transform duration-500;
    }
    .blog-card:hover .blog-image img {
        @apply scale-105;
    }
    .blog-badge {
        @apply absolute top-3 left-3;
    }
    .blog-content {
        @apply p-5 flex flex-col flex-1;
    }
    .blog-meta {
        @apply flex items-center gap-2 text-xs text-[var(--color-text-muted)] mb-2;
    }
    .blog-title {
        @apply font-bold text-[var(--color-text-primary)] mb-2 line-clamp-1;
    }
    .blog-excerpt {
        @apply text-[var(--color-text-secondary)] text-sm line-clamp-2 mb-4 flex-1;
    }
    .blog-link {
        @apply inline-flex items-center gap-1 text-sm font-semibold text-orange-600 hover:text-orange-700 transition-colors;
    }
    .blog-link:hover x-ui.icon {
        transform: translateX(4px);
    }
</style>
@endsection

@section('content')
<div class="blogs-page">
    <!-- Page Header -->
    <header class="blogs-header relative">
        <div class="container-responsive py-16 md:py-24 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold mb-6" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(2, 132, 199,0.4); color: #FF9B60;">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    <span>Technical Insights</span>
                </span>
                <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight mb-4">
                    Blog & Technical News
                </h1>
                <p class="text-lg md:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                    Deep-dive technical articles, industry trends, calibration guides, and corporate updates from Aczet's metrology experts.
                </p>
            </div>
        </div>
    </header>

    <div class="container-responsive py-12 md:py-16">
        <!-- Type Filter -->
        <div class="type-filter mb-10" role="tablist" aria-label="Blog types">
            <button
                class="type-btn {{ $currentType === 'all' ? 'active' : '' }}"
                data-type="all"
                role="tab"
                aria-selected="{{ $currentType === 'all' ? 'true' : 'false' }}"
            >
                All
            </button>
            @foreach($types as $type)
                <button
                    class="type-btn {{ $currentType === $type ? 'active' : '' }}"
                    data-type="{{ $type }}"
                    role="tab"
                    aria-selected="{{ $currentType === $type ? 'true' : 'false' }}"
                >
                    {{ $type }}
                </button>
            @endforeach
        </div>

        <!-- Blogs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="blogs-grid" role="list" aria-label="Blog posts">
            @foreach($blogs as $blog)
                <article class="blog-card" data-type="{{ $blog->type }}" role="listitem">
                    <div class="blog-image relative">
                        @if($blog->image)
                            <img src="{{ $blog->image }}" alt="{{ $blog->title }}" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[var(--color-text-muted)]">
                                <x-ui.icon name="file-text" class="h-12 w-12" />
                            </div>
                        @endif>
                        <div class="blog-badge">
                            <x-ui.badge variant="primary" size="sm">{{ $blog->type }}</x-ui.badge>
                        </div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <x-ui.icon name="calendar" class="h-4 w-4" />
                            <time>{{ $blog->published_at->format('M d, Y') }}</time>
                            <span class="mx-1">•</span>
                            <x-ui.icon name="user" class="h-4 w-4" />
                            <span>{{ $blog->author }}</span>
                        </div>
                        <h3 class="blog-title">
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="hover:text-orange-600 transition-colors">{{ $blog->title }}</a>
                        </h3>
                        <p class="blog-excerpt">{{ Str::limit($blog->summary ?? $blog->content, 120) }}</p>
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="blog-link">
                            Read More
                            <x-ui.icon name="arrow-right" class="h-4 w-4 transition-transform duration-200" />
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        @if($blogs->count() === 0)
            <div class="text-center py-16">
                <x-ui.icon name="newspaper" class="h-16 w-16 text-[var(--color-border-medium)] mx-auto mb-4" />
                <h3 class="text-xl font-bold text-[var(--color-text-primary)] mb-2">No articles found</h3>
                <p class="text-[var(--color-text-secondary)]">No posts match your current filter.</p>
            </div>
        @endif

        <!-- Pagination -->
        @if($blogs->hasPages())
            <div class="mt-10">
                <x-ui.pagination :paginator="$blogs" />
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const typeButtons = document.querySelectorAll('.type-btn');
        const blogCards = document.querySelectorAll('.blog-card');

        typeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const type = btn.dataset.type;

                // Update active button
                typeButtons.forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');

                // Filter cards
                blogCards.forEach((card, index) => {
                    const cardType = card.dataset.type;
                    const shouldShow = type === 'all' || cardType === type;

                    if (shouldShow) {
                        card.style.display = 'block';
                        card.style.animation = `fade-in 0.3s ease-out ${index * 30}ms both`;
                    } else {
                        card.style.animation = `fade-out 0.2s ease-in forwards`;
                        setTimeout(() => card.style.display = 'none', 200);
                    }
                });

                // Update URL
                const url = new URL(window.location.href);
                if (type === 'all') {
                    url.searchParams.delete('type');
                } else {
                    url.searchParams.set('type', type);
                }
                window.history.replaceState({}, '', url);
            });
        });

        // Sync from URL on load
        const urlParams = new URLSearchParams(window.location.search);
        const currentType = urlParams.get('type') || 'all';
        const activeBtn = document.querySelector(`.type-btn[data-type="${currentType}"]`);
        if (activeBtn) activeBtn.click();
    });
</script>
@endsection