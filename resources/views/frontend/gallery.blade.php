@extends('layouts.app')

@section('title', ($t['nav_gallery'] ?? 'Media & Manufacturing Gallery') . ' - Aczet')

@section('content')
<div class="page-banner">
    <h1>
        {{ $t['nav_gallery'] ?? 'Media & Manufacturing Gallery' }}
    </h1>
    <p>
        {{ $t['gallery_sub'] ?? 'Explore our state-of-the-art precision production facilities, cleanrooms, and metrology calibration chambers.' }}
    </p>
</div>

<div class="container-responsive">
    @if($galleryItems->isNotEmpty())
        <!-- Category Filter Chips -->
        @php
            $categories = $galleryItems->pluck('category')->unique()->filter()->values();
        @endphp

        @if($categories->count() > 1)
            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 36px;">
                <button class="gallery-filter-btn active" data-filter="all" onclick="filterGallery('all', this)" style="background: var(--primary); color: #FFF; border: none; padding: 8px 18px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">
                    All Media ({{ $galleryItems->count() }})
                </button>
                @foreach($categories as $cat)
                    <button class="gallery-filter-btn" data-filter="{{ Str::slug($cat) }}" onclick="filterGallery('{{ Str::slug($cat) }}', this)" style="background: #F1F5F9; color: var(--text-dark); border: 1px solid var(--border-color); padding: 8px 18px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        @endif

        <div class="card-grid-responsive" id="galleryGrid">
            @foreach($galleryItems as $item)
                @php
                    $imgUrl = is_object($item) ? ($item->image_url ?? $item->image ?? '') : ($item['image_url'] ?? $item['image'] ?? '');
                    $title = is_object($item) ? ($item->title ?? '') : ($item['title'] ?? '');
                    $category = is_object($item) ? ($item->category ?? 'General') : ($item['category'] ?? 'General');
                    $catSlug = Str::slug($category);
                @endphp
                <div class="gallery-card" data-category="{{ $catSlug }}" style="background: #FFF; border-radius: 12px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-sm); transition: transform 0.2s, box-shadow 0.2s; width: 100%;">
                    <div style="background: linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%); height: clamp(200px, 30vw, 250px); display: flex; align-items: center; justify-content: center; overflow: hidden; border-bottom: 1px solid #F1F5F9;">
                        <img src="{{ $imgUrl }}" alt="{{ $title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                    </div>
                    <div style="padding: 18px;">
                        <span style="background: #FFF5F0; color: var(--accent); font-weight: 700; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $category }}
                        </span>
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-top: 8px;">
                            {{ $title }}
                        </h3>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
            <i class="fa-solid fa-images" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 16px; display: block;"></i>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">No Gallery Photos Available</h3>
            <p style="margin-top: 8px;">Please check back soon for our latest facility media and manufacturing photography.</p>
        </div>
    @endif
</div>

<script>
function filterGallery(category, btn) {
    document.querySelectorAll('.gallery-filter-btn').forEach(function(b) {
        b.style.background = '#F1F5F9';
        b.style.color = 'var(--text-dark)';
        b.style.border = '1px solid var(--border-color)';
    });
    btn.style.background = 'var(--primary)';
    btn.style.color = '#FFF';
    btn.style.border = 'none';

    var cards = document.querySelectorAll('.gallery-card');
    cards.forEach(function(card) {
        if (category === 'all' || card.getAttribute('data-category') === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection
