@extends('layouts.admin')

@section('title', 'Product Catalog & Image Management - Aczet CMS')
@section('page_title', 'Product Catalog & Image Management')

@section('content')
<!-- Header & Filter Actions -->
<div class="card" style="margin-bottom: 20px; padding: 20px 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-boxes-stacked" style="color: var(--accent);"></i> Product Catalog ({{ number_format($products->total()) }})
            </h3>
            <p style="font-size: 0.84rem; color: #64748B; margin-top: 2px;">
                Manage product images, models, pricing, and regional market availability.
            </p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" class="btn-primary" onclick="openAddProductModal()">
                <i class="fa-solid fa-circle-plus"></i> Add New Product
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn-sm btn-outline" style="padding: 10px 14px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Full Create Form
            </a>
        </div>
    </div>

    <!-- Search and Category Filter -->
    <form method="GET" action="{{ route('admin.products') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; background: #F8FAFC; padding: 14px; border-radius: 10px; border: 1px solid var(--border-color);">
        <div style="flex: 1; min-width: 220px; position: relative;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 0.85rem;"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by product name or model number..." class="form-control" style="padding-left: 36px;">
        </div>
        <div style="min-width: 200px;">
            <select name="category_id" class="form-control">
                <option value="">-- All Categories --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-sm btn-primary" style="padding: 10px 18px;">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['q', 'category_id']))
            <a href="{{ route('admin.products') }}" class="btn-sm btn-outline" style="padding: 10px 14px;" title="Reset filters">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </a>
        @endif
    </form>
</div>

<!-- Products Table Card -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 70px; text-align: center;">Image</th>
                    <th>Product & Model</th>
                    <th>Category</th>
                    <th style="width: 95px;">Price</th>
                    <th>Regional Visibility Matrix</th>
                    <th style="width: 140px; text-align: right; padding-right: 24px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $prod)
                    <tr>
                        <!-- Image Column with Fallback & Quick Edit Trigger -->
                        <td style="text-align: center; vertical-align: middle;">
                            <div style="width: 52px; height: 52px; background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 8px; overflow: hidden; display: inline-flex; align-items: center; justify-content: center; padding: 3px; cursor: pointer;"
                                 onclick='openEditProductModal(@json($prod))'
                                 title="Click to update image">
                                <img src="{{ $prod->main_image }}" 
                                     alt="{{ $prod->name }}" 
                                     onerror="this.onerror=null;this.src='{{ vasset('images/products/noimage.jpeg') }}';"
                                     style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                        </td>

                        <!-- Product Title & Model -->
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 3px;">
                                <a href="javascript:void(0)" onclick='openEditProductModal(@json($prod))' style="font-weight: 700; font-size: 0.92rem; color: var(--primary); text-decoration: none;">
                                    {{ $prod->name }}
                                </a>
                                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                    <span class="badge" style="background: #0F172A; color: #FFF; font-size: 0.72rem; padding: 2px 7px;">
                                        {{ $prod->model_number }}
                                    </span>
                                    @if($prod->is_featured)
                                        <span class="badge" style="background: #FEF3C7; color: #92400E; font-size: 0.70rem; padding: 2px 6px;">
                                            <i class="fa-solid fa-star"></i> Featured
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Category -->
                        <td>
                            <span style="font-size: 0.85rem; font-weight: 600; color: #334155;">
                                {{ $prod->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>

                        <!-- Price -->
                        <td>
                            <strong style="font-size: 0.88rem; color: #0F172A;">
                                ${{ number_format($prod->price, 2) }}
                            </strong>
                        </td>

                        <!-- Regional Visibility Badges -->
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap; max-width: 320px;">
                                @foreach($regions as $reg)
                                    @php
                                        $isVis = $prod->isVisibleInRegion($reg);
                                    @endphp
                                    <form action="{{ route('admin.products.toggle_region', $prod->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="region" value="{{ $reg }}">
                                        <button type="submit" class="badge" style="border: none; cursor: pointer; background: {{ $isVis ? '#D1FAE5' : '#FEE2E2' }}; color: {{ $isVis ? '#065F46' : '#991B1B' }}; font-size: 0.68rem; padding: 3px 6px; transition: all 0.15s;" title="Toggle visibility for {{ $reg }}">
                                            {{ $reg }}: {{ $isVis ? 'ON' : 'OFF' }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </td>

                        <!-- Action Buttons -->
                        <td style="text-align: right; padding-right: 24px; white-space: nowrap;">
                            <button type="button" class="btn-sm btn-outline" onclick='openEditProductModal(@json($prod))' style="padding: 7px 12px;" title="Quick Edit Product & Image">
                                <i class="fa-solid fa-image" style="color: var(--accent);"></i> Quick Edit
                            </button>
                            <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn-sm btn-outline" style="padding: 7px 10px;" title="Full Product Editor">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.products.delete', $prod->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete product {{ $prod->model_number }}?');" style="display: inline-block; margin-left: 4px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger" style="padding: 7px 10px;" title="Delete Product">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px 20px; color: #94A3B8;">
                            <i class="fa-solid fa-box-open fa-2xl" style="margin-bottom: 12px; display: block; color: #CBD5E1;"></i>
                            <strong>No products found matching the criteria.</strong>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Container -->
    <div style="padding: 16px 24px;">
        {{ $products->links() }}
    </div>
</div>

<!-- ============================================================== -->
<!-- EDIT PRODUCT & UPDATE IMAGE MODAL                              -->
<!-- ============================================================== -->
<div class="modal-backdrop" id="editProductModal">
    <div class="modal-card" style="max-width: 780px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 20px;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A;" id="editModalHeading">
                    <i class="fa-solid fa-pen-to-square" style="color: var(--accent);"></i> Edit Product & Update Image
                </h3>
                <p style="font-size: 0.84rem; color: #64748B;">Upload a product photograph or enter a static image URL/path.</p>
            </div>
            <button onclick="closeModal('editProductModal')" style="background: #F1F5F9; border: none; width: 34px; height: 34px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
            @csrf

            <!-- Product Image Management Box -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 10px;">
                    <i class="fa-solid fa-camera"></i> Product Image Settings
                </span>

                <div style="display: grid; grid-template-columns: 100px 1fr; gap: 20px; align-items: center;">
                    <!-- Image Preview Stage -->
                    <div style="width: 100px; height: 100px; background: #FFFFFF; border: 2px dashed #CBD5E1; border-radius: 10px; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px;">
                        <img id="editProdImagePreview" 
                             src="{{ vasset('images/products/noimage.jpeg') }}" 
                             alt="Preview" 
                             onerror="this.onerror=null;this.src='{{ vasset('images/products/noimage.jpeg') }}';"
                             style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>

                    <!-- Source Options: Upload or URL -->
                    <div>
                        <!-- Tabs -->
                        <div style="display: flex; gap: 8px; margin-bottom: 10px;">
                            <button type="button" id="editTabUploadBtn" onclick="switchEditImageTab('upload')" style="padding: 5px 14px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; border: none; cursor: pointer; background: var(--primary); color: #FFF;">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Upload File
                            </button>
                            <button type="button" id="editTabUrlBtn" onclick="switchEditImageTab('url')" style="padding: 5px 14px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; border: 1px solid #CBD5E1; cursor: pointer; background: #FFF; color: #475569;">
                                <i class="fa-solid fa-link"></i> Static URL / Path
                            </button>
                            <button type="button" onclick="setEditImageToDefault()" style="padding: 5px 14px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; border: 1px solid #CBD5E1; cursor: pointer; background: #FFF; color: #DC2626;">
                                <i class="fa-solid fa-rotate-left"></i> Reset to noimage.jpeg
                            </button>
                        </div>

                        <!-- Upload File Input -->
                        <div id="editImageUploadBox">
                            <input type="file" name="image_file" id="editImageFileInput" accept="image/*,.svg" onchange="previewFile(this, 'editProdImagePreview')" class="form-control" style="padding: 8px;">
                            <span style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px; display: block;">Supports JPG, JPEG, PNG, WEBP, SVG (Max 5MB).</span>
                        </div>

                        <!-- URL / Path Input -->
                        <div id="editImageUrlBox" style="display: none;">
                            <input type="text" name="image" id="editImageUrlInput" placeholder="/images/products/cy224.svg or https://..." oninput="previewUrl(this.value, 'editProdImagePreview')" class="form-control">
                            <span style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px; display: block;">Enter relative asset path or remote image URL.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Standard Details Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Product Name *</label>
                    <input type="text" name="name" id="editProdName" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Model Number *</label>
                    <input type="text" name="model_number" id="editProdModel" required class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Category *</label>
                    <select name="category_id" id="editProdCategory" required class="form-control">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Price (USD)</label>
                    <input type="number" step="0.01" name="price" id="editProdPrice" class="form-control">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Short Summary Description *</label>
                <textarea name="short_description" id="editProdShortDesc" rows="2" required class="form-control"></textarea>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Full Technical Description</label>
                <textarea name="full_description" id="editProdFullDesc" rows="3" class="form-control"></textarea>
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 24px; padding: 12px 16px; background: #F8FAFC; border-radius: 8px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="is_featured" id="editProdFeatured" value="1">
                    Featured on Homepage
                </label>
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="editProdActive" value="1">
                    Active in Catalog
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 18px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('editProductModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Save & Update Product Image
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- ADD NEW PRODUCT QUICK MODAL                                    -->
<!-- ============================================================== -->
<div class="modal-backdrop" id="addProductModal">
    <div class="modal-card" style="max-width: 780px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 20px;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A;">
                    <i class="fa-solid fa-circle-plus" style="color: var(--accent);"></i> Add New Product Record
                </h3>
                <p style="font-size: 0.84rem; color: #64748B;">Upload image and setup a new precision catalog instrument.</p>
            </div>
            <button onclick="closeModal('addProductModal')" style="background: #F1F5F9; border: none; width: 34px; height: 34px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Product Image Upload Box -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 10px;">
                    <i class="fa-solid fa-camera"></i> Product Image (Optional - Defaults to noimage.jpeg)
                </span>

                <div style="display: grid; grid-template-columns: 100px 1fr; gap: 20px; align-items: center;">
                    <div style="width: 100px; height: 100px; background: #FFFFFF; border: 2px dashed #CBD5E1; border-radius: 10px; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px;">
                        <img id="addProdImagePreview" 
                             src="{{ vasset('images/products/noimage.jpeg') }}" 
                             alt="Preview" 
                             onerror="this.onerror=null;this.src='{{ vasset('images/products/noimage.jpeg') }}';"
                             style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>

                    <div>
                        <div style="display: flex; gap: 8px; margin-bottom: 10px;">
                            <button type="button" id="addTabUploadBtn" onclick="switchAddImageTab('upload')" style="padding: 5px 14px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; border: none; cursor: pointer; background: var(--primary); color: #FFF;">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Upload File
                            </button>
                            <button type="button" id="addTabUrlBtn" onclick="switchAddImageTab('url')" style="padding: 5px 14px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; border: 1px solid #CBD5E1; cursor: pointer; background: #FFF; color: #475569;">
                                <i class="fa-solid fa-link"></i> Static URL / Path
                            </button>
                        </div>

                        <div id="addImageUploadBox">
                            <input type="file" name="image_file" accept="image/*,.svg" onchange="previewFile(this, 'addProdImagePreview')" class="form-control" style="padding: 8px;">
                            <span style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px; display: block;">Supports JPG, PNG, WEBP, SVG (Max 5MB).</span>
                        </div>

                        <div id="addImageUrlBox" style="display: none;">
                            <input type="text" name="image" placeholder="/images/products/cy224.svg or https://..." oninput="previewUrl(this.value, 'addProdImagePreview')" class="form-control">
                            <span style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px; display: block;">Leave empty to use noimage.jpeg fallback automatically.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Product Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Aczet Analytical Balance CY-224C" class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Model Number *</label>
                    <input type="text" name="model_number" required placeholder="e.g. CY-224C" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Category *</label>
                    <select name="category_id" required class="form-control">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Price (USD)</label>
                    <input type="number" step="0.01" name="price" placeholder="0.00" class="form-control">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Short Summary Description *</label>
                <textarea name="short_description" rows="2" required placeholder="Brief executive summary..." class="form-control"></textarea>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Full Description</label>
                <textarea name="full_description" rows="3" placeholder="Detailed metrology description..." class="form-control"></textarea>
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 24px; padding: 12px 16px; background: #F8FAFC; border-radius: 8px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="is_featured" value="1">
                    Featured on Homepage
                </label>
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked>
                    Active in Catalog
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 18px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('addProductModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Create Product Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewFile(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById(previewId);
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewUrl(url, previewId) {
        var preview = document.getElementById(previewId);
        if (preview) {
            preview.src = url.trim() ? url.trim() : '{{ vasset('images/products/noimage.jpeg') }}';
        }
    }

    function switchEditImageTab(mode) {
        var uploadBox = document.getElementById('editImageUploadBox');
        var urlBox = document.getElementById('editImageUrlBox');
        var uploadBtn = document.getElementById('editTabUploadBtn');
        var urlBtn = document.getElementById('editTabUrlBtn');

        if (mode === 'upload') {
            uploadBox.style.display = 'block';
            urlBox.style.display = 'none';
            uploadBtn.style.background = 'var(--primary)';
            uploadBtn.style.color = '#FFF';
            urlBtn.style.background = '#FFF';
            urlBtn.style.color = '#475569';
        } else {
            uploadBox.style.display = 'none';
            urlBox.style.display = 'block';
            urlBtn.style.background = 'var(--primary)';
            urlBtn.style.color = '#FFF';
            uploadBtn.style.background = '#FFF';
            uploadBtn.style.color = '#475569';
        }
    }

    function setEditImageToDefault() {
        document.getElementById('editImageUrlInput').value = '/images/products/noimage.jpeg';
        document.getElementById('editImageFileInput').value = '';
        previewUrl('/images/products/noimage.jpeg', 'editProdImagePreview');
    }

    function switchAddImageTab(mode) {
        var uploadBox = document.getElementById('addImageUploadBox');
        var urlBox = document.getElementById('addImageUrlBox');
        var uploadBtn = document.getElementById('addTabUploadBtn');
        var urlBtn = document.getElementById('addTabUrlBtn');

        if (mode === 'upload') {
            uploadBox.style.display = 'block';
            urlBox.style.display = 'none';
            uploadBtn.style.background = 'var(--primary)';
            uploadBtn.style.color = '#FFF';
            urlBtn.style.background = '#FFF';
            urlBtn.style.color = '#475569';
        } else {
            uploadBox.style.display = 'none';
            urlBox.style.display = 'block';
            urlBtn.style.background = 'var(--primary)';
            urlBtn.style.color = '#FFF';
            uploadBtn.style.background = '#FFF';
            uploadBtn.style.color = '#475569';
        }
    }

    function openModal(id) {
        var el = document.getElementById(id);
        if (el) {
            el.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(id) {
        var el = document.getElementById(id);
        if (el) {
            el.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function openEditProductModal(product) {
        document.getElementById('editModalHeading').innerHTML = '<i class="fa-solid fa-pen-to-square" style="color: var(--accent);"></i> Edit ' + (product.model_number || product.name) + ' & Image';
        document.getElementById('editProductForm').action = '{{ url('/admin/products') }}/' + product.id + '/update';
        var updateUrl = "{{ route('admin.products.update', ':id') }}";
        document.getElementById('editProductForm').action = updateUrl.replace(':id', product.id);
        document.getElementById('editProdName').value = product.name || '';
        document.getElementById('editProdModel').value = product.model_number || '';
        document.getElementById('editProdCategory').value = product.category_id || '';
        document.getElementById('editProdPrice').value = product.price || '';
        document.getElementById('editProdShortDesc').value = product.short_description || '';
        document.getElementById('editProdFullDesc').value = product.full_description || '';
        document.getElementById('editProdFeatured').checked = Boolean(product.is_featured);
        document.getElementById('editProdActive').checked = Boolean(product.is_active);

        var preview = document.getElementById('editProdImagePreview');
        var currentImg = product.main_image || '{{ vasset('images/products/noimage.jpeg') }}';
        preview.src = currentImg;
        document.getElementById('editImageUrlInput').value = product.raw_main_image || (product.main_image ? product.main_image.split('?')[0] : '');
        document.getElementById('editImageFileInput').value = '';

        switchEditImageTab('upload');
        openModal('editProductModal');
    }

    function openAddProductModal() {
        document.getElementById('addProdImagePreview').src = '{{ vasset('images/products/noimage.jpeg') }}';
        switchAddImageTab('upload');
        openModal('addProductModal');
    }
</script>
@endsection
