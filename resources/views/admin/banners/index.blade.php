@extends('layouts.admin')

@section('title', 'Homepage Banners - Aczet CMS')
@section('page_title', 'Homepage Banner Manager')

@section('styles')
<style>
    .banner-hub-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .banner-stat-pill {
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

    .banner-toolbar {
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
    .banner-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 22px;
    }

    .banner-item-card {
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

    .banner-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        border-color: #CBD5E1;
    }

    .banner-img-wrap {
        height: 175px;
        background: radial-gradient(circle at center, #1E293B 0%, #0B132B 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 16px;
        overflow: hidden;
    }

    .banner-img-wrap img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .banner-item-card:hover .banner-img-wrap img {
        transform: scale(1.04);
    }

    .eyebrow-chip {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(6px);
        color: #FF9B60;
        font-weight: 800;
        font-size: 0.68rem;
        padding: 3px 9px;
        border-radius: 6px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid rgba(229, 86, 4, 0.3);
        max-width: 70%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

    .banner-body {
        padding: 16px 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .banner-body h4 {
        font-size: 1rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 6px;
        line-height: 1.35;
    }

    .banner-body p {
        font-size: 0.82rem;
        color: var(--text-muted);
        line-height: 1.45;
        margin-bottom: 12px;
        flex: 1;
    }

    .banner-cta-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #F1F5F9;
        color: var(--primary);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.74rem;
        font-weight: 700;
        width: fit-content;
        margin-bottom: 12px;
    }

    .banner-card-footer {
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
        width: 80px;
        height: 50px;
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
</style>
@endsection

@section('content')

<!-- Header with Live Stats -->
<div class="banner-hub-header">
    <div>
        <div style="font-size:0.8rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">
            Marketing &bull; Homepage Hero Carousel
        </div>
        <h3 style="font-size:1.6rem; font-weight:800; color:var(--primary); margin:0;">
            Homepage Banner Manager
        </h3>
        <p style="margin-top:6px; color:var(--text-muted); font-size:0.9rem;">
            Control the first impression: hero slide photography, marketing headlines, call-to-actions, order sequence, and visibility.
        </p>
    </div>

    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <div class="banner-stat-pill">
            <i class="fa-solid fa-images" style="color:var(--accent);"></i>
            <span>{{ $banners->count() }} Carousel Banners</span>
        </div>
        <div class="banner-stat-pill">
            <i class="fa-solid fa-circle-check" style="color:#10B981;"></i>
            <span>{{ $banners->where('is_active', true)->count() }} Active</span>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="btn-sm btn-outline" style="text-decoration:none; padding:8px 16px; border-radius:8px; font-weight:700;">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Live Homepage
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
<div class="banner-toolbar">
    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
        <span style="font-size:0.82rem; font-weight:700; color:var(--text-muted); margin-right:4px;">Filter:</span>
        <button class="filter-chip-btn active" data-status="all" onclick="filterBannerItems('all', this)">
            All Banners ({{ $banners->count() }})
        </button>
        <button class="filter-chip-btn" data-status="active" onclick="filterBannerItems('active', this)">
            <i class="fa-solid fa-circle-check" style="color:#10B981;"></i> Active ({{ $banners->where('is_active', true)->count() }})
        </button>
        <button class="filter-chip-btn" data-status="hidden" onclick="filterBannerItems('hidden', this)">
            <i class="fa-solid fa-eye-slash" style="color:#F59E0B;"></i> Hidden ({{ $banners->where('is_active', false)->count() }})
        </button>
    </div>

    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <div style="position:relative; width:220px;">
            <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:0.85rem;"></i>
            <input type="text" id="bannerSearchInput" onkeyup="searchBannerItems(this.value)" placeholder="Search banners..." class="form-control" style="padding-left:34px; padding-top:7px; padding-bottom:7px; font-size:0.85rem; border-radius:20px;">
        </div>

        <div style="display:flex; gap:4px;">
            <button class="view-toggle-btn active" id="btnViewGrid" onclick="setBannerViewMode('grid')" title="Grid View">
                <i class="fa-solid fa-table-cells-large"></i>
            </button>
            <button class="view-toggle-btn" id="btnViewTable" onclick="setBannerViewMode('table')" title="Table View">
                <i class="fa-solid fa-list-ul"></i>
            </button>
        </div>
    </div>
</div>

<!-- Main Workspace Layout (2 columns: Banner Items + Sticky Editor Form) -->
<div style="display:grid; grid-template-columns:minmax(0,1.35fr) minmax(340px,0.72fr); gap:28px; align-items:start;">

    <!-- Left Column: Banner Media Display -->
    <div>
        <!-- 1. Grid View Mode -->
        <div class="banner-grid" id="bannerGridView">
            @forelse($banners as $banner)
                <div class="banner-item-card" data-status="{{ $banner->is_active ? 'active' : 'hidden' }}" data-search="{{ strtolower($banner->title . ' ' . $banner->eyebrow . ' ' . $banner->description) }}" id="bannerCard-{{ $banner->id }}">
                    <div class="banner-img-wrap">
                        <img src="{{ vasset($banner->image) }}" alt="{{ $banner->title }}" loading="lazy">
                        
                        @if($banner->eyebrow)
                            <span class="eyebrow-chip" title="{{ $banner->eyebrow }}">{{ $banner->eyebrow }}</span>
                        @endif
                        
                        <span class="status-chip {{ $banner->is_active ? 'active' : 'hidden' }}">
                            {{ $banner->is_active ? 'Active' : 'Hidden' }}
                        </span>

                        <span class="order-badge-pill">
                            <i class="fa-solid fa-sort"></i> Order #{{ $banner->sort_order }}
                        </span>

                        <button type="button" class="zoom-btn" onclick="openBannerLightbox('{{ vasset($banner->image) }}', '{{ addslashes($banner->title) }}', '{{ addslashes($banner->eyebrow ?? 'Homepage Hero') }}')" title="Full Preview">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </button>
                    </div>

                    <div class="banner-body">
                        <h4>{{ $banner->title }}</h4>
                        <p>{{ Str::limit($banner->description, 95) }}</p>

                        @if($banner->cta_label)
                            <div class="banner-cta-chip">
                                <i class="fa-solid fa-arrow-right" style="color:var(--accent); font-size:0.75rem;"></i>
                                {{ $banner->cta_label }} <span style="opacity:0.6; font-weight:400;">({{ $banner->cta_url ?: '/' }})</span>
                            </div>
                        @endif

                        <div class="banner-card-footer">
                            <span style="font-size:0.75rem; color:#94A3B8; font-weight:600;">
                                ID: #{{ $banner->id }}
                            </span>
                            <div style="display:flex; gap:6px;">
                                <button class="btn-sm btn-outline" type="button" onclick='openBannerEditor(@json($banner))' style="padding:5px 10px; font-size:0.75rem; border-radius:6px;">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                <form action="{{ route('admin.banners.delete', $banner->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Remove this homepage banner?');">
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
                    <h4 style="font-size:1.2rem; font-weight:700; color:var(--primary);">No Banners Found</h4>
                    <p style="font-size:0.88rem; margin-top:4px;">Use the editor form on the right to add your first homepage hero slide.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. Table View Mode -->
        <div class="card" id="bannerTableView" style="display:none; padding:0; overflow:hidden;">
            <table class="table-custom" style="margin:0; min-width:700px;">
                <thead>
                    <tr>
                        <th style="width:130px;">Slide Preview</th>
                        <th>Banner Details</th>
                        <th style="width:90px; text-align:center;">Order</th>
                        <th style="width:100px; text-align:center;">Status</th>
                        <th style="width:120px; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($banners as $banner)
                    <tr class="banner-table-row" data-status="{{ $banner->is_active ? 'active' : 'hidden' }}" data-search="{{ strtolower($banner->title . ' ' . $banner->eyebrow . ' ' . $banner->description) }}">
                        <td>
                            <div style="width:110px; height:62px; border-radius:8px; overflow:hidden; background:#0B132B; display:flex; align-items:center; justify-content:center; padding:4px; cursor:pointer;" onclick="openBannerLightbox('{{ vasset($banner->image) }}', '{{ addslashes($banner->title) }}', '{{ addslashes($banner->eyebrow ?? 'Homepage Hero') }}')">
                                <img src="{{ vasset($banner->image) }}" alt="{{ $banner->title }}" style="max-width:100%; max-height:100%; object-fit:contain;">
                            </div>
                        </td>
                        <td>
                            @if($banner->eyebrow)
                                <span style="background:#FFF5F0; color:var(--accent); font-weight:700; padding:2px 8px; border-radius:4px; font-size:0.7rem; text-transform:uppercase; letter-spacing:0.5px;">
                                    {{ $banner->eyebrow }}
                                </span>
                            @endif
                            <div style="font-weight:700; color:var(--primary); font-size:0.95rem; margin-top:4px;">
                                {{ $banner->title }}
                            </div>
                            <p style="font-size:0.8rem; color:var(--text-muted); margin:4px 0 0; line-height:1.4;">
                                {{ Str::limit($banner->description, 80) }}
                            </p>
                            @if($banner->cta_label)
                                <small style="color:#64748B; font-weight:600; font-size:0.75rem; margin-top:3px; display:block;">
                                    CTA: {{ $banner->cta_label }} &rarr; {{ $banner->cta_url ?: '/' }}
                                </small>
                            @endif
                        </td>
                        <td style="text-align:center; font-weight:700; color:var(--primary);">
                            #{{ $banner->sort_order }}
                        </td>
                        <td style="text-align:center;">
                            <span class="badge {{ $banner->is_active ? 'badge-success' : 'badge-warning' }}">
                                {{ $banner->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <button class="btn-sm btn-outline" type="button" onclick='openBannerEditor(@json($banner))' title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.banners.delete', $banner->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Remove this homepage banner?');">
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
                            No homepage banners yet.
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
                <h3 id="bannerFormTitle" style="font-size:1.15rem; font-weight:800; color:var(--primary); margin:0; display:flex; align-items:center; gap:8px;">
                    <span style="width:10px; height:10px; background:var(--accent); border-radius:50%; display:inline-block;"></span>
                    Add Homepage Slide
                </h3>
                <button type="button" id="btnCancelEdit" onclick="resetBannerForm()" style="display:none; background:none; border:none; color:var(--accent); font-size:0.8rem; font-weight:700; cursor:pointer;">
                    <i class="fa-solid fa-rotate-left"></i> Cancel Edit
                </button>
            </div>

            <form id="bannerForm" action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display:grid; gap:16px;">

                    <!-- Eyebrow / Tagline -->
                    <div>
                        <label for="bannerEyebrow" style="font-weight:700; font-size:0.84rem; color:#334155; margin-bottom:6px; display:block;">
                            Eyebrow Tagline
                        </label>
                        <input name="eyebrow" id="bannerEyebrow" class="form-control" placeholder="e.g. Precision instruments since 1986">
                    </div>

                    <!-- Slide Headline / Title -->
                    <div>
                        <label for="bannerTitle" style="font-weight:700; font-size:0.84rem; color:#334155; margin-bottom:6px; display:block;">
                            Headline / Title *
                        </label>
                        <input name="title" id="bannerTitle" required class="form-control" placeholder="e.g. Weigh with confidence.">
                    </div>

                    <!-- Description Body -->
                    <div>
                        <label for="bannerDescription" style="font-weight:700; font-size:0.84rem; color:#334155; margin-bottom:6px; display:block;">
                            Description / Subtitle
                        </label>
                        <textarea name="description" id="bannerDescription" rows="3" class="form-control" placeholder="Short marketing message for the hero banner..."></textarea>
                    </div>

                    <!-- Image Source Selector: File Upload or URL -->
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <label style="font-weight:700; font-size:0.84rem; color:#334155; margin:0;">
                                Slide Image *
                            </label>
                            <div style="display:flex; gap:4px;">
                                <button type="button" id="tabUploadBtn" class="btn-sm btn-primary" onclick="switchBannerImageTab('upload')" style="padding:3px 10px; font-size:0.72rem; border-radius:6px;">Upload</button>
                                <button type="button" id="tabUrlBtn" class="btn-sm btn-outline" onclick="switchBannerImageTab('url')" style="padding:3px 10px; font-size:0.72rem; border-radius:6px;">URL / Path</button>
                            </div>
                        </div>

                        <!-- 1. Dropzone Upload Box -->
                        <div id="uploadSourceWrap">
                            <div class="dropzone-box" id="bannerDropzone">
                                <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.8rem; color:var(--accent); margin-bottom:8px; display:block;"></i>
                                <span style="font-size:0.85rem; font-weight:700; color:var(--primary); display:block;">
                                    Click or Drag & Drop Banner Here
                                </span>
                                <small style="font-size:0.75rem; color:var(--text-muted); display:block; margin-top:4px;">
                                    Wide JPG, PNG, WebP &bull; Maximum 5 MB
                                </small>
                                <input name="image_file" id="bannerImageFile" type="file" accept=".jpg,.jpeg,.png,.webp" onchange="handleBannerFileSelect(this)">
                            </div>
                        </div>

                        <!-- 2. Path / URL Input Box -->
                        <div id="urlSourceWrap" style="display:none;">
                            <input name="image" id="bannerImage" class="form-control" placeholder="/images/products/cy224.svg or https://..." oninput="handleBannerUrlInput(this.value)">
                            <small style="color:var(--text-muted); font-size:0.75rem; display:block; margin-top:5px;">
                                Enter static asset path or remote image URL.
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
                                    Ready to apply
                                </small>
                            </div>
                            <button type="button" onclick="clearBannerLivePreview()" style="background:none; border:none; color:#EF4444; font-size:1rem; cursor:pointer; padding:4px;" title="Remove">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Call to Action (Label & URL) -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                        <div>
                            <label for="bannerCtaLabel" style="font-weight:700; font-size:0.84rem; color:#334155; margin-bottom:6px; display:block;">
                                Button Label
                            </label>
                            <input name="cta_label" id="bannerCtaLabel" class="form-control" placeholder="e.g. Explore Range">
                        </div>
                        <div>
                            <label for="bannerCtaUrl" style="font-weight:700; font-size:0.84rem; color:#334155; margin-bottom:6px; display:block;">
                                Button URL / Link
                            </label>
                            <input name="cta_url" id="bannerCtaUrl" class="form-control" placeholder="e.g. /products">
                        </div>
                    </div>

                    <!-- Order & Visibility -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; align-items:center; background:#F8FAFC; padding:12px 14px; border-radius:10px; border:1px solid var(--border-color);">
                        <div>
                            <label for="bannerOrder" style="font-weight:700; font-size:0.82rem; color:#334155; margin-bottom:4px; display:block;">
                                Slide Sequence
                            </label>
                            <input type="number" min="0" name="sort_order" id="bannerOrder" value="0" class="form-control" style="padding:6px 10px; font-size:0.88rem;">
                        </div>

                        <div style="display:flex; flex-direction:column; justify-content:center;">
                            <span style="font-weight:700; font-size:0.82rem; color:#334155; margin-bottom:4px; display:block;">
                                Homepage Visibility
                            </span>
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin:0;">
                                <span class="toggle-switch">
                                    <input type="checkbox" name="is_active" id="bannerActive" value="1" checked>
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
                    <button class="btn-primary" type="submit" id="bannerSubmitBtn" style="flex:1; padding:11px 20px; font-weight:700; font-size:0.9rem; justify-content:center;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Banner Slide
                    </button>
                    <button class="btn-sm btn-outline" type="button" onclick="resetBannerForm()" style="padding:11px 16px; font-weight:600;">
                        Clear
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Lightbox Modal for Full Banner Inspection -->
<div class="modal-backdrop" id="lightboxModal" onclick="closeBannerLightbox(event)">
    <div style="max-width:900px; width:100%; text-align:center; position:relative;" onclick="event.stopPropagation()">
        <button type="button" onclick="closeBannerLightbox()" style="position:absolute; top:-40px; right:0; background:none; border:none; color:#FFFFFF; font-size:1.8rem; cursor:pointer;">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div style="background:#0F172A; border-radius:16px; overflow:hidden; padding:20px; box-shadow:0 25px 60px rgba(0,0,0,0.5);">
            <img src="" id="lightboxImg" alt="" style="max-width:100%; max-height:70vh; object-fit:contain; border-radius:8px;">
            <div style="margin-top:16px; color:#FFFFFF; text-align:left;">
                <span id="lightboxEyebrow" style="background:var(--accent); color:#FFF; font-weight:700; font-size:0.75rem; padding:3px 10px; border-radius:4px; text-transform:uppercase;"></span>
                <h3 id="lightboxTitle" style="font-size:1.25rem; font-weight:800; margin-top:8px; color:#FFFFFF;"></h3>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Active toggle label update
document.getElementById('bannerActive').addEventListener('change', function() {
    document.getElementById('activeLabel').textContent = this.checked ? 'Visible' : 'Hidden';
});

// Tab Switcher between File Upload and URL
function switchBannerImageTab(mode) {
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
function handleBannerFileSelect(input) {
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

function handleBannerUrlInput(url) {
    if (url && url.trim().length > 3) {
        document.getElementById('livePreviewImg').src = url;
        document.getElementById('livePreviewTitle').textContent = url.split('/').pop() || 'Remote Image';
        document.getElementById('livePreviewMeta').textContent = 'Direct Image Path';
        document.getElementById('livePreviewBox').classList.add('active');
    } else {
        document.getElementById('livePreviewBox').classList.remove('active');
    }
}

function clearBannerLivePreview() {
    document.getElementById('bannerImageFile').value = '';
    document.getElementById('bannerImage').value = '';
    document.getElementById('livePreviewBox').classList.remove('active');
}

// Edit Mode Controller
function openBannerEditor(banner) {
    document.getElementById('bannerFormTitle').innerHTML = '<span style="width:10px; height:10px; background:#38BDF8; border-radius:50%; display:inline-block;"></span> Edit Slide #' + banner.id;
    document.getElementById('btnCancelEdit').style.display = 'inline-flex';
    document.getElementById('bannerForm').action = '/admin/banners/' + banner.id + '/update';
    
    document.getElementById('bannerEyebrow').value = banner.eyebrow || '';
    document.getElementById('bannerTitle').value = banner.title || '';
    document.getElementById('bannerDescription').value = banner.description || '';
    document.getElementById('bannerImage').value = banner.image || '';
    document.getElementById('bannerCtaLabel').value = banner.cta_label || '';
    document.getElementById('bannerCtaUrl').value = banner.cta_url || '';
    document.getElementById('bannerOrder').value = banner.sort_order || 0;
    document.getElementById('bannerActive').checked = !!banner.is_active;
    document.getElementById('activeLabel').textContent = banner.is_active ? 'Visible' : 'Hidden';
    
    if (banner.image) {
        handleBannerUrlInput(banner.image);
    }
    
    document.getElementById('bannerSubmitBtn').innerHTML = '<i class="fa-solid fa-check"></i> Update Slide';
    
    // Highlight active card
    document.querySelectorAll('.banner-item-card').forEach(c => c.style.outline = 'none');
    var activeCard = document.getElementById('bannerCard-' + banner.id);
    if (activeCard) {
        activeCard.style.outline = '2px solid var(--accent)';
    }

    // Smooth scroll to form on mobile devices
    if (window.innerWidth < 992) {
        document.getElementById('bannerForm').scrollIntoView({ behavior: 'smooth' });
    }
}

function resetBannerForm() {
    document.getElementById('bannerFormTitle').innerHTML = '<span style="width:10px; height:10px; background:var(--accent); border-radius:50%; display:inline-block;"></span> Add Homepage Slide';
    document.getElementById('btnCancelEdit').style.display = 'none';
    document.getElementById('bannerForm').action = '{{ route("admin.banners.store") }}';
    document.getElementById('bannerForm').reset();
    document.getElementById('bannerActive').checked = true;
    document.getElementById('activeLabel').textContent = 'Visible';
    document.getElementById('bannerOrder').value = 0;
    clearBannerLivePreview();
    document.getElementById('bannerSubmitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Banner Slide';
    document.querySelectorAll('.banner-item-card').forEach(c => c.style.outline = 'none');
}

// Client-side Real-time Search
function searchBannerItems(query) {
    var q = query.toLowerCase().trim();
    var cards = document.querySelectorAll('.banner-item-card');
    var rows = document.querySelectorAll('.banner-table-row');

    cards.forEach(function(card) {
        var text = card.getAttribute('data-search') || '';
        if (text.indexOf(q) !== -1) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });

    rows.forEach(function(row) {
        var text = row.getAttribute('data-search') || '';
        if (text.indexOf(q) !== -1) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Client-side Status Filter
function filterBannerItems(status, btn) {
    document.querySelectorAll('.filter-chip-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    var cards = document.querySelectorAll('.banner-item-card');
    var rows = document.querySelectorAll('.banner-table-row');

    cards.forEach(function(card) {
        if (status === 'all' || card.getAttribute('data-status') === status) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });

    rows.forEach(function(row) {
        if (status === 'all' || row.getAttribute('data-status') === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Switch between Grid and Table View
function setBannerViewMode(mode) {
    var gridView = document.getElementById('bannerGridView');
    var tableView = document.getElementById('bannerTableView');
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
function openBannerLightbox(src, title, eyebrow) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxTitle').textContent = title;
    document.getElementById('lightboxEyebrow').textContent = eyebrow;
    document.getElementById('lightboxModal').classList.add('active');
}

function closeBannerLightbox(e) {
    document.getElementById('lightboxModal').classList.remove('active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBannerLightbox();
    }
});
</script>
@endsection
