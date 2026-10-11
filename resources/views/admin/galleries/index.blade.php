@extends('layouts.admin')

@section('title', 'Media & Gallery Hub - Aczet CMS')
@section('page_title', 'Media & Gallery Manager')

@section('styles')
<style>
    /* Modern UI Enhancements for Gallery CMS */
    .gallery-hub-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .gallery-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #FFFFFF;
        border: 1px solid var(--border-color);
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--primary);
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .gallery-toolbar {
        background: #FFFFFF;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(15,23,42,0.03);
    }

    .filter-chip-btn {
        background: #F8FAFC;
        border: 1px solid var(--border-color);
        color: #475569;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .filter-chip-btn:hover {
        background: #F1F5F9;
        color: var(--primary);
        border-color: #CBD5E1;
    }

    .filter-chip-btn.active {
        background: var(--primary);
        color: #FFFFFF;
        border-color: var(--primary);
    }

    .view-toggle-btn {
        background: #F1F5F9;
        border: 1px solid var(--border-color);
        color: #64748B;
        padding: 7px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .view-toggle-btn.active {
        background: #FFFFFF;
        color: var(--accent);
        border-color: var(--accent);
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* Visual Grid Cards */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .gallery-item-card {
        background: #FFFFFF;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .gallery-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        border-color: #CBD5E1;
    }

    .gallery-img-wrap {
        height: 180px;
        background: radial-gradient(circle at center, #1E293B 0%, #0B132B 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 16px;
        overflow: hidden;
    }

    .gallery-img-wrap img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .gallery-item-card:hover .gallery-img-wrap img {
        transform: scale(1.05);
    }

    .cat-chip {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(6px);
        color: #FF9B60;
        font-weight: 800;
        font-size: 0.7rem;
        padding: 3px 9px;
        border-radius: 6px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid rgba(229, 86, 4, 0.3);
    }

    .status-chip {
        position: absolute;
        top: 12px;
        right: 12px;
        font-weight: 700;
        font-size: 0.72rem;
        padding: 3px 9px;
        border-radius: 20px;
        backdrop-filter: blur(6px);
    }
    .status-chip.active {
        background: rgba(16, 185, 129, 0.9);
        color: #FFFFFF;
    }
    .status-chip.hidden {
        background: rgba(245, 158, 11, 0.9);
        color: #FFFFFF;
    }

    .order-badge-pill {
        position: absolute;
        bottom: 10px;
        left: 12px;
        background: rgba(255, 255, 255, 0.15);
        color: #FFFFFF;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        backdrop-filter: blur(4px);
    }

    .zoom-btn {
        position: absolute;
        bottom: 10px;
        right: 12px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: #FFFFFF;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        backdrop-filter: blur(4px);
    }
    .zoom-btn:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: scale(1.1);
    }

    .gallery-body {
        padding: 16px 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .gallery-body h4 {
        font-size: 0.98rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 6px;
        line-height: 1.35;
    }

    .gallery-body .image-path-text {
        font-size: 0.74rem;
        color: var(--text-muted);
        font-family: monospace;
        word-break: break-all;
        margin-bottom: 14px;
        display: block;
    }

    .gallery-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid var(--border-color);
        margin-top: auto;
    }

    /* Drag & Drop Upload Zone */
    .dropzone-box {
        border: 2px dashed #CBD5E1;
        border-radius: 12px;
        padding: 24px 16px;
        text-align: center;
        background: #F8FAFC;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .dropzone-box:hover, .dropzone-box.dragover {
        border-color: var(--accent);
        background: rgba(229, 86, 4, 0.04);
    }

    .dropzone-box input[type="file"] {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Live Preview Box */
    .preview-box {
        display: none;
        align-items: center;
        gap: 14px;
        background: #0F172A;
        border-radius: 12px;
        padding: 12px;
        margin-top: 12px;
        color: #FFFFFF;
        position: relative;
    }

    .preview-box.active {
        display: flex;
    }

    .preview-thumbnail {
        width: 70px;
        height: 55px;
        object-fit: contain;
        background: rgba(255,255,255,0.08);
        border-radius: 6px;
        padding: 4px;
    }

    /* Switch toggle */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #CBD5E1;
        transition: .3s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: var(--accent);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }

    /* Quick category helper pills */
    .quick-cat-tag {
        display: inline-block;
        font-size: 0.72rem;
        background: #F1F5F9;
        color: #475569;
        padding: 3px 8px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.15s;
    }
    .quick-cat-tag:hover {
        background: #E2E8F0;
        color: var(--primary);
    }
</style>
@endsection

@section('content')

<!-- Header with Live Stats -->
<div class="gallery-hub-header">
    <div>
        <div style="font-size:0.8rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">
            Content Management &bull; Media Hub
        </div>
        <h3 style="font-size:1.6rem; font-weight:800; color:var(--primary); margin:0;">
            Media & Gallery Images
        </h3>
        <p style="margin-top:6px; color:var(--text-muted); font-size:0.9rem;">
            Upload and organize laboratory, cleanroom manufacturing, and metrology testing facility images shown on the public gallery.
        </p>
    </div>

    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <div class="gallery-stat-pill">
            <i class="fa-solid fa-camera-retro" style="color:var(--accent);"></i>
            <span>{{ $galleries->count() }} Gallery Photos</span>
        </div>
        <div class="gallery-stat-pill">
            <i class="fa-solid fa-circle-check" style="color:#10B981;"></i>
            <span>{{ $galleries->where('is_active', true)->count() }} Live</span>
        </div>
        <a href="{{ route('gallery') }}" target="_blank" class="btn-sm btn-outline" style="text-decoration:none; padding:8px 16px; border-radius:8px; font-weight:700;">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Public Gallery
        </a>
    </div>
</div>

@if($errors->any())
    <div class="card" style="border-left:4px solid #EF4444; color:#991B1B; padding:16px 20px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
        <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<!-- Toolbar: Filter Pills, Search Bar, and View Toggle -->
<div class="gallery-toolbar">
    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
        <span style="font-size:0.82rem; font-weight:700; color:var(--text-muted); margin-right:4px;">Filter:</span>
        <button class="filter-chip-btn active" data-cat="all" onclick="filterGalleryItems('all', this)">
            All ({{ $galleries->count() }})
        </button>
        @php
            $uniqueCategories = $galleries->pluck('category')->unique()->filter()->values();
        @endphp
        @foreach($uniqueCategories as $cat)
            @php
                $catCount = $galleries->where('category', $cat)->count();
            @endphp
            <button class="filter-chip-btn" data-cat="{{ Str::slug($cat) }}" onclick="filterGalleryItems('{{ Str::slug($cat) }}', this)">
                {{ $cat }} <span style="opacity:0.75; font-size:0.75rem;">({{ $catCount }})</span>
            </button>
        @endforeach
    </div>

    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <div style="position:relative; width:220px;">
            <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:0.85rem;"></i>
            <input type="text" id="gallerySearchInput" onkeyup="searchGalleryItems(this.value)" placeholder="Search photos..." class="form-control" style="padding-left:34px; padding-top:7px; padding-bottom:7px; font-size:0.85rem; border-radius:20px;">
        </div>

        <div style="display:flex; gap:4px;">
            <button class="view-toggle-btn active" id="btnViewGrid" onclick="setViewMode('grid')" title="Grid View">
                <i class="fa-solid fa-table-cells-large"></i>
            </button>
            <button class="view-toggle-btn" id="btnViewTable" onclick="setViewMode('table')" title="Table View">
                <i class="fa-solid fa-list-ul"></i>
            </button>
        </div>
    </div>
</div>

<!-- Main Workspace Layout (2 columns: Gallery Items + Sticky Editor Form) -->
<div style="display:grid; grid-template-columns:minmax(0,1.4fr) minmax(340px,0.7fr); gap:28px; align-items:start;">

    <!-- Left Column: Gallery Media Display -->
    <div>
        <!-- 1. Grid View Mode -->
        <div class="gallery-grid" id="galleryGridView">
            @forelse($galleries as $gallery)
                @php
                    $catSlug = Str::slug($gallery->category);
                @endphp
                <div class="gallery-item-card" data-category="{{ $catSlug }}" data-title="{{ strtolower($gallery->title) }}" id="galleryCard-{{ $gallery->id }}">
                    <div class="gallery-img-wrap">
                        <img src="{{ vasset($gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy">
                        
                        <span class="cat-chip">{{ $gallery->category }}</span>
                        
                        <span class="status-chip {{ $gallery->is_active ? 'active' : 'hidden' }}">
                            {{ $gallery->is_active ? 'Active' : 'Hidden' }}
                        </span>

                        <span class="order-badge-pill">
                            <i class="fa-solid fa-sort"></i> #{{ $gallery->sort_order }}
                        </span>

                        <button type="button" class="zoom-btn" onclick="openLightbox('{{ vasset($gallery->image) }}', '{{ addslashes($gallery->title) }}', '{{ addslashes($gallery->category) }}')" title="Full Preview">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </button>
                    </div>

                    <div class="gallery-body">
                        <h4>{{ $gallery->title }}</h4>
                        <span class="image-path-text" title="{{ $gallery->image }}">
                            {{ Str::limit($gallery->image, 42) }}
                        </span>

                        <div class="gallery-card-footer">
                            <span style="font-size:0.75rem; color:#94A3B8; font-weight:600;">
                                ID: #{{ $gallery->id }}
                            </span>
                            <div style="display:flex; gap:6px;">
                                <button class="btn-sm btn-outline" type="button" onclick='openGalleryEditor(@json($gallery))' style="padding:5px 10px; font-size:0.75rem; border-radius:6px;">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                <form action="{{ route('admin.galleries.delete', $gallery->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this gallery photo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-sm btn-danger" type="submit" style="padding:5px 10px; font-size:0.75rem; border-radius:6px;" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card" style="grid-column:1/-1; text-align:center; padding:60px 20px; color:var(--text-muted);">
                    <i class="fa-solid fa-images" style="font-size:3rem; margin-bottom:14px; color:#CBD5E1; display:block;"></i>
                    <h4 style="font-size:1.2rem; font-weight:700; color:var(--primary);">No Gallery Photos Found</h4>
                    <p style="font-size:0.88rem; margin-top:4px;">Use the editor form on the right to upload your first photo.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. Table View Mode (Hidden by default, toggled via switcher) -->
        <div class="card" id="galleryTableView" style="display:none; padding:0; overflow:hidden;">
            <table class="table-custom" style="margin:0; min-width:680px;">
                <thead>
                    <tr>
                        <th style="width:110px;">Thumbnail</th>
                        <th>Photo Details</th>
                        <th style="width:90px; text-align:center;">Order</th>
                        <th style="width:100px; text-align:center;">Status</th>
                        <th style="width:120px; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($galleries as $gallery)
                    @php
                        $catSlug = Str::slug($gallery->category);
                    @endphp
                    <tr class="gallery-table-row" data-category="{{ $catSlug }}" data-title="{{ strtolower($gallery->title) }}">
                        <td>
                            <div style="width:84px; height:56px; border-radius:8px; overflow:hidden; background:#0B132B; display:flex; align-items:center; justify-content:center; padding:4px; cursor:pointer;" onclick="openLightbox('{{ vasset($gallery->image) }}', '{{ addslashes($gallery->title) }}', '{{ addslashes($gallery->category) }}')">
                                <img src="{{ vasset($gallery->image) }}" alt="{{ $gallery->title }}" style="max-width:100%; max-height:100%; object-fit:contain;">
                            </div>
                        </td>
                        <td>
                            <span style="background:#FFF5F0; color:var(--accent); font-weight:700; padding:2px 8px; border-radius:4px; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.5px;">
                                {{ $gallery->category }}
                            </span>
                            <div style="font-weight:700; color:var(--primary); font-size:0.95rem; margin-top:4px;">
                                {{ $gallery->title }}
                            </div>
                            <small style="color:var(--text-muted); font-size:0.74rem; font-family:monospace;">
                                {{ Str::limit($gallery->image, 48) }}
                            </small>
                        </td>
                        <td style="text-align:center; font-weight:700; color:var(--primary);">
                            #{{ $gallery->sort_order }}
                        </td>
                        <td style="text-align:center;">
                            <span class="badge {{ $gallery->is_active ? 'badge-success' : 'badge-warning' }}">
                                {{ $gallery->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <button class="btn-sm btn-outline" type="button" onclick='openGalleryEditor(@json($gallery))' title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.galleries.delete', $gallery->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this gallery photo?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn-sm btn-danger" type="submit" style="margin-left:4px;" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:40px; color:var(--text-muted);">
                            No gallery images available.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Sticky Add / Edit Form Card -->
    <div style="position:sticky; top:90px;">
        <div class="card" style="box-shadow:0 10px 35px rgba(15,23,42,0.06); border-radius:18px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid var(--border-color); padding-bottom:14px;">
                <h3 id="galleryFormTitle" style="font-size:1.15rem; font-weight:800; color:var(--primary); margin:0; display:flex; align-items:center; gap:8px;">
                    <span style="width:10px; height:10px; background:var(--accent); border-radius:50%; display:inline-block;"></span>
                    Add Gallery Photo
                </h3>
                <button type="button" id="btnCancelEdit" onclick="resetGalleryForm()" style="display:none; background:none; border:none; color:var(--accent); font-size:0.8rem; font-weight:700; cursor:pointer;">
                    <i class="fa-solid fa-rotate-left"></i> Cancel Edit
                </button>
            </div>

            <form id="galleryForm" action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display:grid; gap:18px;">

                    <!-- Photo Title -->
                    <div>
                        <label for="galleryTitle" style="font-weight:700; font-size:0.84rem; color:#334155; margin-bottom:6px; display:block;">
                            Photo Title *
                        </label>
                        <input name="title" id="galleryTitle" required class="form-control" placeholder="e.g. Ultra-Clean Assembly Facility">
                    </div>

                    <!-- Department / Category -->
                    <div>
                        <label for="galleryCategory" style="font-weight:700; font-size:0.84rem; color:#334155; margin-bottom:6px; display:block;">
                            Department / Category *
                        </label>
                        <input name="category" id="galleryCategory" list="categorySuggestions" required class="form-control" placeholder="e.g. Manufacturing, Testing, R&D...">
                        <datalist id="categorySuggestions">
                            <option value="Manufacturing">
                            <option value="Testing">
                            <option value="R&D">
                            <option value="Industrial">
                            <option value="Jewelry">
                            <option value="Exhibition">
                            <option value="Calibration">
                            <option value="Cleanroom">
                        </datalist>

                        <div style="display:flex; gap:6px; flex-wrap:wrap; margin-top:8px;">
                            <span class="quick-cat-tag" onclick="selectQuickCat('Manufacturing')">+ Manufacturing</span>
                            <span class="quick-cat-tag" onclick="selectQuickCat('Testing')">+ Testing</span>
                            <span class="quick-cat-tag" onclick="selectQuickCat('R&D')">+ R&D</span>
                            <span class="quick-cat-tag" onclick="selectQuickCat('Industrial')">+ Industrial</span>
                            <span class="quick-cat-tag" onclick="selectQuickCat('Jewelry')">+ Jewelry</span>
                        </div>
                    </div>

                    <!-- Image Source Selector: File Upload or URL -->
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <label style="font-weight:700; font-size:0.84rem; color:#334155; margin:0;">
                                Photo Image Source *
                            </label>
                            <div style="display:flex; gap:4px;">
                                <button type="button" id="tabUploadBtn" class="btn-sm btn-primary" onclick="switchImageTab('upload')" style="padding:3px 10px; font-size:0.72rem; border-radius:6px;">Upload</button>
                                <button type="button" id="tabUrlBtn" class="btn-sm btn-outline" onclick="switchImageTab('url')" style="padding:3px 10px; font-size:0.72rem; border-radius:6px;">URL / Path</button>
                            </div>
                        </div>

                        <!-- 1. Dropzone Upload Box -->
                        <div id="uploadSourceWrap">
                            <div class="dropzone-box" id="galleryDropzone">
                                <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.8rem; color:var(--accent); margin-bottom:8px; display:block;"></i>
                                <span style="font-size:0.85rem; font-weight:700; color:var(--primary); display:block;">
                                    Click or Drag & Drop Photo Here
                                </span>
                                <small style="font-size:0.75rem; color:var(--text-muted); display:block; margin-top:4px;">
                                    JPG, PNG, WebP, SVG &bull; Maximum 5 MB
                                </small>
                                <input name="image_file" id="galleryImageFile" type="file" accept=".jpg,.jpeg,.png,.webp,.svg" onchange="handleFileSelect(this)">
                            </div>
                        </div>

                        <!-- 2. Path / URL Input Box -->
                        <div id="urlSourceWrap" style="display:none;">
                            <input name="image" id="galleryImage" class="form-control" placeholder="/images/products/cy224.svg or https://..." oninput="handleUrlInput(this.value)">
                            <small style="color:var(--text-muted); font-size:0.75rem; display:block; margin-top:5px;">
                                Enter static asset path or remote image link.
                            </small>
                        </div>

                        <!-- Live Visual Preview Box -->
                        <div class="preview-box" id="livePreviewBox">
                            <img src="" id="livePreviewImg" alt="Preview" class="preview-thumbnail">
                            <div style="flex:1; min-width:0;">
                                <span style="font-size:0.8rem; font-weight:700; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" id="livePreviewTitle">
                                    Selected image
                                </span>
                                <small style="font-size:0.72rem; color:#94A3B8;" id="livePreviewMeta">
                                    Ready to upload
                                </small>
                            </div>
                            <button type="button" onclick="clearLivePreview()" style="background:none; border:none; color:#EF4444; font-size:1rem; cursor:pointer; padding:4px;" title="Remove">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Order & Visibility -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; align-items:center; background:#F8FAFC; padding:12px 14px; border-radius:10px; border:1px solid var(--border-color);">
                        <div>
                            <label for="galleryOrder" style="font-weight:700; font-size:0.82rem; color:#334155; margin-bottom:4px; display:block;">
                                Display Order
                            </label>
                            <input type="number" min="0" name="sort_order" id="galleryOrder" value="0" class="form-control" style="padding:6px 10px; font-size:0.88rem;">
                        </div>

                        <div style="display:flex; flex-direction:column; justify-content:center;">
                            <span style="font-weight:700; font-size:0.82rem; color:#334155; margin-bottom:4px; display:block;">
                                Live Status
                            </span>
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin:0;">
                                <span class="toggle-switch">
                                    <input type="checkbox" name="is_active" id="galleryActive" value="1" checked>
                                    <span class="toggle-slider"></span>
                                </span>
                                <span style="font-size:0.84rem; font-weight:600; color:var(--primary);" id="activeLabel">
                                    Visible
                                </span>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Submit and Clear buttons -->
                <div style="display:flex; gap:10px; margin-top:24px;">
                    <button class="btn-primary" type="submit" id="gallerySubmitBtn" style="flex:1; padding:11px 20px; font-weight:700; font-size:0.9rem; justify-content:center;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Gallery Photo
                    </button>
                    <button class="btn-sm btn-outline" type="button" onclick="resetGalleryForm()" style="padding:11px 16px; font-weight:600;">
                        Clear
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Lightbox Modal for Full Image Inspection -->
<div class="modal-backdrop" id="lightboxModal" onclick="closeLightbox(event)">
    <div style="max-width:850px; width:100%; text-align:center; position:relative;" onclick="event.stopPropagation()">
        <button type="button" onclick="closeLightbox()" style="position:absolute; top:-40px; right:0; background:none; border:none; color:#FFFFFF; font-size:1.8rem; cursor:pointer;">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div style="background:#0F172A; border-radius:16px; overflow:hidden; padding:20px; box-shadow:0 25px 60px rgba(0,0,0,0.5);">
            <img src="" id="lightboxImg" alt="" style="max-width:100%; max-height:70vh; object-fit:contain; border-radius:8px;">
            <div style="margin-top:16px; color:#FFFFFF; text-align:left;">
                <span id="lightboxCategory" style="background:var(--accent); color:#FFF; font-weight:700; font-size:0.75rem; padding:3px 10px; border-radius:4px; text-transform:uppercase;"></span>
                <h3 id="lightboxTitle" style="font-size:1.25rem; font-weight:800; margin-top:8px; color:#FFFFFF;"></h3>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Category Quick Helper
function selectQuickCat(cat) {
    document.getElementById('galleryCategory').value = cat;
}

// Active toggle label update
document.getElementById('galleryActive').addEventListener('change', function() {
    document.getElementById('activeLabel').textContent = this.checked ? 'Visible' : 'Hidden';
});

// Tab Switcher between File Upload and URL
function switchImageTab(mode) {
    var uploadWrap = document.getElementById('uploadSourceWrap');
    var urlWrap = document.getElementById('urlSourceWrap');
    var uploadBtn = document.getElementById('tabUploadBtn');
    var urlBtn = document.getElementById('tabUrlBtn');

    if (mode === 'upload') {
        uploadWrap.style.display = 'block';
        urlWrap.style.display = 'none';
        uploadBtn.className = 'btn-sm btn-primary';
        urlBtn.className = 'btn-sm btn-outline';
    } else {
        uploadWrap.style.display = 'none';
        urlWrap.style.display = 'block';
        uploadBtn.className = 'btn-sm btn-outline';
        urlBtn.className = 'btn-sm btn-primary';
    }
}

// Live Image Preview Handlers
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('livePreviewImg').src = e.target.result;
            document.getElementById('livePreviewTitle').textContent = file.name;
            document.getElementById('livePreviewMeta').textContent = (file.size / 1024).toFixed(1) + ' KB &bull; ' + file.type;
            document.getElementById('livePreviewBox').classList.add('active');
        };
        reader.readAsDataURL(file);
    }
}

function handleUrlInput(url) {
    if (url && url.trim().length > 3) {
        document.getElementById('livePreviewImg').src = url;
        document.getElementById('livePreviewTitle').textContent = url.split('/').pop() || 'Remote Image';
        document.getElementById('livePreviewMeta').textContent = 'Direct Image Path';
        document.getElementById('livePreviewBox').classList.add('active');
    } else {
        document.getElementById('livePreviewBox').classList.remove('active');
    }
}

function clearLivePreview() {
    document.getElementById('galleryImageFile').value = '';
    document.getElementById('galleryImage').value = '';
    document.getElementById('livePreviewBox').classList.remove('active');
}

// Edit Mode Controller
function openGalleryEditor(item) {
    document.getElementById('galleryFormTitle').innerHTML = '<span style="width:10px; height:10px; background:#38BDF8; border-radius:50%; display:inline-block;"></span> Edit Photo #' + item.id;
    document.getElementById('btnCancelEdit').style.display = 'inline-flex';
    document.getElementById('galleryForm').action = '/admin/galleries/' + item.id + '/update';
    
    document.getElementById('galleryTitle').value = item.title || '';
    document.getElementById('galleryCategory').value = item.category || '';
    document.getElementById('galleryImage').value = item.image || '';
    document.getElementById('galleryOrder').value = item.sort_order || 0;
    document.getElementById('galleryActive').checked = !!item.is_active;
    document.getElementById('activeLabel').textContent = item.is_active ? 'Visible' : 'Hidden';
    
    if (item.image) {
        handleUrlInput(item.image);
    }
    
    document.getElementById('gallerySubmitBtn').innerHTML = '<i class="fa-solid fa-check"></i> Update Photo';
    
    // Highlight active card
    document.querySelectorAll('.gallery-item-card').forEach(c => c.style.outline = 'none');
    var activeCard = document.getElementById('galleryCard-' + item.id);
    if (activeCard) {
        activeCard.style.outline = '2px solid var(--accent)';
    }

    // Smooth scroll to form on mobile devices
    if (window.innerWidth < 992) {
        document.getElementById('galleryForm').scrollIntoView({ behavior: 'smooth' });
    }
}

function resetGalleryForm() {
    document.getElementById('galleryFormTitle').innerHTML = '<span style="width:10px; height:10px; background:var(--accent); border-radius:50%; display:inline-block;"></span> Add Gallery Photo';
    document.getElementById('btnCancelEdit').style.display = 'none';
    document.getElementById('galleryForm').action = '{{ route("admin.galleries.store") }}';
    document.getElementById('galleryForm').reset();
    document.getElementById('galleryActive').checked = true;
    document.getElementById('activeLabel').textContent = 'Visible';
    document.getElementById('galleryOrder').value = 0;
    clearLivePreview();
    document.getElementById('gallerySubmitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Gallery Photo';
    document.querySelectorAll('.gallery-item-card').forEach(c => c.style.outline = 'none');
}

// Client-side Real-time Search
function searchGalleryItems(query) {
    var q = query.toLowerCase().trim();
    var cards = document.querySelectorAll('.gallery-item-card');
    var rows = document.querySelectorAll('.gallery-table-row');

    cards.forEach(function(card) {
        var title = card.getAttribute('data-title') || '';
        var cat = card.getAttribute('data-category') || '';
        if (title.indexOf(q) !== -1 || cat.indexOf(q) !== -1) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });

    rows.forEach(function(row) {
        var title = row.getAttribute('data-title') || '';
        var cat = row.getAttribute('data-category') || '';
        if (title.indexOf(q) !== -1 || cat.indexOf(q) !== -1) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Client-side Category Filter
function filterGalleryItems(catSlug, btn) {
    document.querySelectorAll('.filter-chip-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    var cards = document.querySelectorAll('.gallery-item-card');
    var rows = document.querySelectorAll('.gallery-table-row');

    cards.forEach(function(card) {
        if (catSlug === 'all' || card.getAttribute('data-category') === catSlug) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });

    rows.forEach(function(row) {
        if (catSlug === 'all' || row.getAttribute('data-category') === catSlug) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Switch between Grid and Table View
function setViewMode(mode) {
    var gridView = document.getElementById('galleryGridView');
    var tableView = document.getElementById('galleryTableView');
    var btnGrid = document.getElementById('btnViewGrid');
    var btnTable = document.getElementById('btnViewTable');

    if (mode === 'grid') {
        gridView.style.display = 'grid';
        tableView.style.display = 'none';
        btnGrid.classList.add('active');
        btnTable.classList.remove('active');
    } else {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        btnGrid.classList.remove('active');
        btnTable.classList.add('active');
    }
}

// Lightbox Modal
function openLightbox(src, title, category) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxTitle').textContent = title;
    document.getElementById('lightboxCategory').textContent = category;
    document.getElementById('lightboxModal').classList.add('active');
}

function closeLightbox(e) {
    document.getElementById('lightboxModal').classList.remove('active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>
@endsection
