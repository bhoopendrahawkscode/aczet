@extends('layouts.admin')

@section('title', 'Edit Product & Image - Aczet CMS')
@section('page_title', 'Edit Instrument & Image')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #0F172A;">
                <i class="fa-solid fa-pen-to-square" style="color: var(--accent);"></i> Edit {{ $product->model_number }}: {{ $product->name }}
            </h3>
            <p style="font-size: 0.84rem; color: #64748B;">Update product specifications, photographs, and regional catalog status.</p>
        </div>
        <a href="{{ route('admin.products') }}" class="btn-sm btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Product Image Management Box -->
        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 22px; margin-bottom: 24px;">
            <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
                <i class="fa-solid fa-camera"></i> Product Photograph & Visual Asset
            </span>

            <div style="display: grid; grid-template-columns: 120px 1fr; gap: 24px; align-items: center;">
                <div style="width: 120px; height: 120px; background: #FFFFFF; border: 2px dashed #CBD5E1; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 6px;">
                    <img id="editImagePreview" 
                         src="{{ $product->main_image }}" 
                         alt="{{ $product->name }}" 
                         onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';"
                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>

                <div>
                    <div style="display: flex; gap: 8px; margin-bottom: 12px;">
                        <button type="button" id="tabUploadBtn" onclick="switchImageTab('upload')" style="padding: 6px 14px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; border: none; cursor: pointer; background: var(--primary); color: #FFF;">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Upload New File
                        </button>
                        <button type="button" id="tabUrlBtn" onclick="switchImageTab('url')" style="padding: 6px 14px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; border: 1px solid #CBD5E1; cursor: pointer; background: #FFF; color: #475569;">
                            <i class="fa-solid fa-link"></i> Static URL / Asset Path
                        </button>
                    </div>

                    <div id="uploadBox">
                        <input type="file" name="image_file" accept="image/*,.svg" onchange="previewFile(this, 'editImagePreview')" class="form-control">
                        <span style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px; display: block;">Supports JPG, JPEG, PNG, WEBP, SVG (Max 5MB). Leave empty to retain current image.</span>
                    </div>

                    <div id="urlBox" style="display: none;">
                        <input type="text" name="image" value="{{ $product->getRawOriginal('main_image') }}" placeholder="/images/products/cy224.svg or https://..." oninput="previewUrl(this.value, 'editImagePreview')" class="form-control">
                        <span style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px; display: block;">Relative asset path or external HTTPS link.</span>
                    </div>

                    <div style="margin-top: 10px;">
                        <label style="font-size: 0.82rem; color: #DC2626; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            <input type="checkbox" name="remove_image" value="1" onchange="if(this.checked){ document.getElementById('editImagePreview').src='{{ asset('images/products/noimage.jpeg') }}'; }">
                            Reset to default placeholder (<code>noimage.jpeg</code>)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Form Fields -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Category *</label>
            <select name="category_id" required class="form-control">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Product Name *</label>
                <input type="text" name="name" value="{{ $product->name }}" required class="form-control">
            </div>
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Model Number *</label>
                <input type="text" name="model_number" value="{{ $product->model_number }}" required class="form-control">
            </div>
        </div>

        <div style="margin-bottom: 18px;">
            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Short Summary Description *</label>
            <textarea name="short_description" rows="2" required class="form-control">{{ $product->short_description }}</textarea>
        </div>

        <div style="margin-bottom: 18px;">
            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Full Detailed Description</label>
            <textarea name="full_description" rows="4" class="form-control">{{ $product->full_description }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 24px;">
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">Price (USD)</label>
                <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="form-control">
            </div>
            <div style="display: flex; gap: 20px; align-items: center; margin-top: 24px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                    Featured Instrument
                </label>
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                    Active in Catalog
                </label>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
            <a href="{{ route('admin.products') }}" class="btn-sm btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Update Product & Image
            </button>
        </div>
    </form>
</div>

<script>
    function previewFile(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById(previewId);
                if (preview) preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewUrl(url, previewId) {
        var preview = document.getElementById(previewId);
        if (preview) {
            preview.src = url.trim() ? url.trim() : '{{ asset('images/products/noimage.jpeg') }}';
        }
    }

    function switchImageTab(mode) {
        var uploadBox = document.getElementById('uploadBox');
        var urlBox = document.getElementById('urlBox');
        var uploadBtn = document.getElementById('tabUploadBtn');
        var urlBtn = document.getElementById('tabUrlBtn');

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
</script>
@endsection

