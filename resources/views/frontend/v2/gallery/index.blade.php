@extends('layouts.app')

@php
    $currentLocale = $currentLocale ?? app()->getLocale();
    $currentRegion = $currentRegion ?? session('aczet_region', 'India');
    $galleryItems = $galleryItems ?? \App\Models\Gallery::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
    $categories = $galleryItems->pluck('category')->unique()->filter()->values();
@endphp

@section('title', 'Gallery & Media Library | Aczet Metrology')
@section('meta_description', 'Explore Aczet\'s state-of-the-art manufacturing facilities, cleanrooms, precision instruments, and team photos.')

@section('styles')
<style>
    .gallery-page {
        background: var(--color-bg-primary);
    }
    .gallery-header {
        background: linear-gradient(135deg, var(--color-void) 0%, var(--color-navy) 60%, var(--color-slate) 100%);
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .gallery-header::before {
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
    .filter-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }
    .filter-tab {
        @apply px-4 py-2 text-sm font-semibold rounded-full transition-all duration-200 focus-ring;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: #E2E8F0;
    }
    .filter-tab:hover {
        @apply bg-white/20 border-white/30;
    }
    .filter-tab.active {
        @apply bg-orange-500 border-orange-500 text-white;
    }
    .gallery-grid {
        @apply grid gap-6;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
    .gallery-item {
        @apply bg-white border border-[var(--color-border-light)] rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-[var(--color-border-medium)] hover:-translate-y-1;
    }
    .gallery-image {
        @apply aspect-video bg-[var(--color-bg-tertiary)] overflow-hidden relative;
    }
    .gallery-image img {
        @apply w-full h-full object-cover transition-transform duration-500;
    }
    .gallery-item:hover .gallery-image img {
        @apply scale-105;
    }
    .gallery-overlay {
        @apply absolute inset-0 bg-gradient-to-t from-[var(--color-void)]/90 via-transparent to-transparent opacity-0 transition-opacity duration-300 flex items-end p-4;
    }
    .gallery-item:hover .gallery-overlay {
        @apply opacity-100;
    }
    .gallery-info {
        @apply p-5;
    }
    .gallery-category {
        @apply inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 mb-2;
    }
    .gallery-title {
        @apply font-bold text-[var(--color-text-primary)] mb-1 line-clamp-1;
    }
    .gallery-desc {
        @apply text-sm text-[var(--color-text-secondary)] line-clamp-2;
    }
    .lightbox {
        @apply fixed inset-0 z-[var(--z-modal)] flex items-center justify-center p-4 bg-[var(--color-void)]/95 backdrop-blur-sm;
    }
    .lightbox-content {
        @apply relative max-w-5xl w-full max-h-[90vh];
    }
    .lightbox-image {
        @apply w-full h-auto max-h-[80vh] object-contain rounded-xl;
    }
    .lightbox-nav {
        @apply absolute top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 border border-white/20 text-white flex items-center justify-center hover:bg-white/20 transition-colors focus-ring;
    }
    .lightbox-close {
        @apply absolute top-4 right-4 w-12 h-12 rounded-full bg-white/10 border border-white/20 text-white flex items-center justify-center hover:bg-white/20 transition-colors focus-ring;
    }
    .lightbox-counter {
        @apply absolute bottom-4 left-1/2 -translate-x-1/2 text-white/70 text-sm font-medium;
    }
</style>
@endsection

@section('content')
<div class="gallery-page">
    <!-- Page Header -->
    <header class="gallery-header relative">
        <div class="container-responsive py-16 md:py-24 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold mb-6" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(2, 132, 199,0.4); color: #FF9B60;">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    <span>Media Gallery</span>
                </span>
                <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight mb-4">
                    Gallery & Media Library
                </h1>
                <p class="text-lg md:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                    Explore our world-class manufacturing facilities, precision cleanrooms, cutting-edge instruments, and the passionate team behind Aczet's metrology excellence.
                </p>
            </div>
        </div>
    </header>

    <div class="container-responsive py-12 md:py-16">
        <!-- Filter Tabs -->
        <div class="filter-tabs mb-10" role="tablist" aria-label="Gallery categories">
            <button
                class="filter-tab active"
                data-filter="all"
                role="tab"
                aria-selected="true"
            >
                All
            </button>
            @foreach($categories as $category)
                <button
                    class="filter-tab"
                    data-filter="{{ Str::slug($category) }}"
                    role="tab"
                    aria-selected="false"
                >
                    {{ $category }}
                </button>
            @endforeach
        </div>

        <!-- Gallery Grid -->
        <div class="gallery-grid" id="gallery-grid" role="list" aria-label="Gallery images">
            @foreach($galleryItems as $item)
                <article class="gallery-item" data-category="{{ Str::slug($item->category) }}" role="listitem">
                    <div class="gallery-image">
                        @if($item->image)
                            <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[var(--color-text-muted)]">
                                <x-ui.icon name="image" class="h-16 w-16" />
                            </div>
                        @endif
                        <div class="gallery-overlay">
                            <button
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-colors"
                                onclick="openLightbox('{{ $item->image }}', '{{ addslashes($item->title)} }', '{{ addslashes($item->description ?? '') }}')"
                                aria-label="View {{ $item->title }} in full size"
                            >
                                <x-ui.icon name="arrow-up-right" class="h-5 w-5" />
                                View Fullscreen
                            </button>
                        </div>
                    </div>
                    <div class="gallery-info">
                        @if($item->category)
                            <span class="gallery-category">{{ $item->category }}</span>
                        @endif
                        <h3 class="gallery-title">{{ $item->title }}</h3>
                        @if($item->description)
                            <p class="gallery-desc">{{ Str::limit($item->description, 120) }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        @if($galleryItems->count() === 0)
            <div class="text-center py-16">
                <x-ui.icon name="image" class="h-16 w-16 text-[var(--color-border-medium)] mx-auto mb-4" />
                <h3 class="text-xl font-bold text-[var(--color-text-primary)] mb-2">No gallery items found</h3>
                <p class="text-[var(--color-text-secondary)]">Check back soon for new media content.</p>
            </div>
        @endif
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="lightbox hidden" role="dialog" aria-modal="true" aria-labelledby="lightbox-title">
        <button class="lightbox-close" onclick="closeLightbox()" aria-label="Close lightbox">
            <x-ui.icon name="x" class="h-6 w-6" />
        </button>
        <button class="lightbox-nav" style="left: 16px;" onclick="navigateLightbox(-1)" aria-label="Previous image">
            <x-ui.icon name="chevron-left" class="h-6 w-6" />
        </button>
        <button class="lightbox-nav" style="right: 16px;" onclick="navigateLightbox(1)" aria-label="Next image">
            <x-ui.icon name="chevron-right" class="h-6 w-6" />
        </button>
        <div class="lightbox-content">
            <img id="lightbox-image" class="lightbox-image" src="" alt="">
        </div>
        <div class="lightbox-counter" id="lightbox-counter"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Gallery Filter
    document.addEventListener('DOMContentLoaded', () => {
        const filterTabs = document.querySelectorAll('.filter-tab');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const grid = document.getElementById('gallery-grid');

        filterTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const filter = tab.dataset.filter;

                // Update active tab
                filterTabs.forEach(t => {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');

                // Filter items with animation
                galleryItems.forEach((item, index) => {
                    const category = item.dataset.category;
                    const shouldShow = filter === 'all' || category === filter;

                    if (shouldShow) {
                        item.style.display = 'block';
                        item.style.animation = `fade-in 0.3s ease-out ${index * 30}ms both`;
                    } else {
                        item.style.animation = `fade-out 0.2s ease-in forwards`;
                        setTimeout(() => item.style.display = 'none', 200);
                    }
                });
            });
        });

        // Lightbox
        let lightboxItems = [];
        let currentIndex = 0;

        window.openLightbox = (src, title, description) => {
            lightboxItems = Array.from(document.querySelectorAll('.gallery-item:not([style*="display: none"])')).map(item => ({
                src: item.querySelector('img')?.src || '',
                title: item.querySelector('.gallery-title')?.textContent || '',
                description: item.querySelector('.gallery-desc')?.textContent || ''
            }));
            currentIndex = lightboxItems.findIndex(item => item.src === src);
            if (currentIndex === -1) currentIndex = 0;
            showLightbox();
        };

        function showLightbox() {
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightbox-image');
            const counter = document.getElementById('lightbox-counter');
            const item = lightboxItems[currentIndex];

            if (item) {
                img.src = item.src;
                img.alt = item.title;
                counter.textContent = `${currentIndex + 1} / ${lightboxItems.length}`;
                lightbox.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        window.closeLightbox = () => {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        };

        window.navigateLightbox = (direction) => {
            currentIndex = (currentIndex + direction + lightboxItems.length) % lightboxItems.length;
            showLightbox();
        };

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            const lightbox = document.getElementById('lightbox');
            if (!lightbox.classList.contains('hidden')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') navigateLightbox(-1);
                if (e.key === 'ArrowRight') navigateLightbox(1);
            }
        });

        // Click outside to close
        document.getElementById('lightbox').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) closeLightbox();
        });
    });

    // Add fade-out animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes fade-out {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to { opacity: 0; transform: translateY(-20px) scale(0.95); }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection