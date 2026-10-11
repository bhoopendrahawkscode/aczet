@extends('layouts.admin')

@section('title', 'Category & Multilingual Content Manager - Aczet CMS')
@section('page_title', 'Category & Multilingual Content Manager')

@section('content')
<!-- Header Stats & Action Bar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-layer-group" style="color: var(--accent);"></i> Product Categories & Language Translations
        </h3>
        <p style="font-size: 0.88rem; color: #64748B; margin-top: 4px;">Manage catalog categories, upload category icons/images, and localize text across all 12 supported international languages.</p>
    </div>
    <button class="btn-primary" onclick="openNewCategoryModal()">
        <i class="fa-solid fa-circle-plus"></i> Add New Category
    </button>
</div>

<!-- Category Management Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width: 70px;">ID</th>
                <th style="width: 80px;">Icon</th>
                <th>Category & Description</th>
                <th>URL Slug</th>
                <th>Models</th>
                <th>Language Coverage</th>
                <th style="text-align: right; padding-right: 28px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
                <tr>
                    <td><span style="font-weight: 800; color: #94A3B8;">#{{ $cat->id }}</span></td>
                    <td>
                        <div style="width: 48px; height: 48px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; display: flex; align-items: center; justify-content: center; padding: 4px; overflow: hidden; cursor: pointer;" onclick='openEditModal(@json($cat))' title="Click to update image">
                            <img src="{{ asset($cat->image ?: 'images/products/noimage.jpeg') }}" 
                                 alt="{{ $cat->name }}" 
                                 onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';"
                                 style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 800; font-size: 0.95rem; color: var(--primary); cursor: pointer;" onclick='openEditModal(@json($cat))'>{{ $cat->name }}</div>
                        <div style="font-size: 0.82rem; color: #64748B; margin-top: 2px; max-width: 380px;">{{ Str::limit($cat->description, 75) }}</div>
                    </td>
                    <td><code style="background: #F1F5F9; color: #0F172A; padding: 4px 8px; border-radius: 6px; font-size: 0.8rem;">{{ $cat->slug }}</code></td>
                    <td>
                        <span class="badge badge-success" style="font-size: 0.8rem; padding: 5px 12px;">
                            <i class="fa-solid fa-microchip"></i> {{ $cat->products_count }} Models
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 4px; flex-wrap: wrap; max-width: 240px;">
                            <span style="background: #0F172A; color: #FFF; padding: 2px 7px; border-radius: 4px; font-size: 0.7rem; font-weight: 800;">EN</span>
                            @if(is_array($cat->translations))
                                @foreach(['es', 'ar', 'de', 'zh', 'th', 'fr', 'it', 'ja', 'ru', 'pt', 'ko'] as $lCode)
                                    @if(isset($cat->translations[$lCode]) && !empty($cat->translations[$lCode]['name']))
                                        <span style="background: #D1FAE5; color: #065F46; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; font-weight: 700;">{{ strtoupper($lCode) }}</span>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td style="text-align: right; padding-right: 28px;">
                        <button class="btn-sm btn-outline" onclick='openEditModal(@json($cat))' style="padding: 8px 16px;">
                            <i class="fa-solid fa-pen-to-square" style="color: var(--accent);"></i> Edit Category & Image
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modern Tabbed Edit Translations Modal -->
<div class="modal-backdrop" id="editCategoryModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;" id="editModalTitle">Edit Category & Image</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Upload image and update names/descriptions across international languages.</p>
            </div>
            <button onclick="closeModal('editCategoryModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form id="editCategoryForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            
            <!-- Category Image Upload Section -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 18px; margin-bottom: 24px; display: flex; align-items: center; gap: 20px;">
                <div style="width: 80px; height: 80px; background: #FFFFFF; border: 2px dashed #CBD5E1; border-radius: 10px; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px;" id="edit_img_preview_box">
                    <img id="edit_img_preview" src="{{ asset('images/products/noimage.jpeg') }}" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Category Image / SVG</label>
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
                        <input type="file" name="image_file" accept="image/*,.svg" onchange="previewImage(this, 'edit_img_preview')" style="font-size: 0.85rem; color: #64748B;">
                    </div>
                    <div style="margin-top: 6px;">
                        <label style="display: block; font-size: 0.75rem; color: #64748B; margin-bottom: 2px;">Or specify Image URL / Asset Path:</label>
                        <input type="text" name="image" id="edit_cat_image_url" class="form-control" placeholder="images/products/cy224.svg or https://..." oninput="var val = this.value.trim(); document.getElementById('edit_img_preview').src = val ? ((val.startsWith('http://') || val.startsWith('https://')) ? val : ('{{ asset('') }}' + val.replace(/^\/+/, ''))) : '{{ asset('images/products/noimage.jpeg') }}';" style="font-size: 0.82rem; padding: 6px 10px;">
                    </div>
                    <div style="margin-top: 8px;">
                        <label style="font-size: 0.78rem; color: #DC2626; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="remove_image" value="1" onchange="if(this.checked){ document.getElementById('edit_img_preview').src='{{ asset('images/products/noimage.jpeg') }}'; document.getElementById('edit_cat_image_url').value=''; }">
                            Reset to default placeholder (<code>noimage.jpeg</code>)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Language Selection Tabs -->
            <div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 12px; margin-bottom: 24px; border-bottom: 1px solid #E2E8F0;">
                <button type="button" class="tab-btn active" onclick="switchTab('tab_en', this)" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #CBD5E1; background: #0F172A; color: #FFF; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    English (Default)
                </button>
                @php
                    $tabLangs = [
                        'es' => 'Español',
                        'ar' => 'العربية (Arabic)',
                        'de' => 'Deutsch',
                        'zh' => '中文 (Chinese)',
                        'ja' => '日本語',
                        'th' => 'ไทย (Thai)',
                        'fr' => 'Français',
                        'it' => 'Italiano',
                        'ru' => 'Русский',
                        'pt' => 'Português',
                        'ko' => '한국어',
                    ];
                @endphp
                @foreach($tabLangs as $code => $label)
                    <button type="button" class="tab-btn" onclick="switchTab('tab_{{ $code }}', this)" style="padding: 8px 14px; border-radius: 8px; border: 1px solid #CBD5E1; background: #F8FAFC; color: #334155; font-weight: 600; font-size: 0.85rem; cursor: pointer; white-space: nowrap;">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- English Default Tab Content -->
            <div id="tab_en" class="tab-content" style="display: block;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Category Name (English) *</label>
                    <input type="text" name="name" id="edit_name" required class="form-control" style="font-weight: 600;">
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Description (English)</label>
                    <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                </div>
            </div>

            <!-- Other Language Tabs -->
            @foreach($tabLangs as $code => $label)
                <div id="tab_{{ $code }}" class="tab-content" style="display: none;">
                    <div style="background: #FFF5F0; border-left: 4px solid var(--accent); padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                        <span style="font-weight: 700; color: var(--accent); font-size: 0.85rem;"><i class="fa-solid fa-language"></i> {{ $label }} Translation Settings</span>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Localized Name ({{ $label }})</label>
                        <input type="text" name="translations[{{ $code }}][name]" id="edit_trans_{{ $code }}_name" class="form-control" placeholder="Enter localized category name">
                    </div>
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Localized Description ({{ $label }})</label>
                        <textarea name="translations[{{ $code }}][description]" id="edit_trans_{{ $code }}_desc" rows="3" class="form-control" placeholder="Enter localized category description"></textarea>
                    </div>
                </div>
            @endforeach

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('editCategoryModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Save & Publish Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modern New Category Modal with Image Upload -->
<div class="modal-backdrop" id="newCategoryModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;">Create New Category</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Upload icon/image and setup precision category with multilingual capabilities.</p>
            </div>
            <button onclick="closeModal('newCategoryModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 6px;">Category Name (English) *</label>
                <input type="text" name="name" required class="form-control" placeholder="e.g. Dynamic Density Balances">
            </div>

            <!-- Image Upload Dropzone -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 20px;">
                <div style="width: 70px; height: 70px; background: #FFFFFF; border: 2px dashed #CBD5E1; border-radius: 10px; display: flex; align-items: center; justify-content: center; overflow: hidden;" id="create_img_preview_box">
                    <i class="fa-solid fa-cloud-arrow-up fa-xl" style="color: var(--accent);" id="create_img_icon"></i>
                    <img id="create_img_preview" src="" style="width: 100%; height: 100%; object-fit: contain; display: none;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Upload Category Image / SVG</label>
                    <input type="file" name="image_file" accept="image/*,.svg" onchange="previewImage(this, 'create_img_preview', 'create_img_icon')" style="font-size: 0.85rem; color: #64748B;">
                    <p style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px;">Or provide existing URL below if not uploading a file.</p>
                    <input type="text" name="image" class="form-control" placeholder="/images/products/cy224.svg" style="margin-top: 6px; padding: 6px 10px; font-size: 0.82rem;">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 6px;">Description (English)</label>
                <textarea name="description" rows="3" class="form-control" placeholder="Describe the series features..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('newCategoryModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Create Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input, previewId, iconId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewEl = document.getElementById(previewId);
                previewEl.src = e.target.result;
                previewEl.style.display = 'block';
                if (iconId) {
                    var iconEl = document.getElementById(iconId);
                    if (iconEl) iconEl.style.display = 'none';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(function(el) {
            el.style.display = 'none';
        });
        document.querySelectorAll('.tab-btn').forEach(function(el) {
            el.style.background = '#F8FAFC';
            el.style.color = '#334155';
            el.style.fontWeight = '600';
        });
        document.getElementById(tabId).style.display = 'block';
        btn.style.background = '#0F172A';
        btn.style.color = '#FFF';
        btn.style.fontWeight = '700';
    }

    function openEditModal(category) {
        document.getElementById('editModalTitle').innerText = 'Edit ' + category.name + ' & Image';
        var updateUrl = "{{ route('admin.categories.update', ':id') }}";
        document.getElementById('editCategoryForm').action = updateUrl.replace(':id', category.id);
        document.getElementById('edit_name').value = category.name || '';
        document.getElementById('edit_description').value = category.description || '';

        var previewEl = document.getElementById('edit_img_preview');
        var defaultImg = "{{ asset('images/products/noimage.jpeg') }}";
        var catImg = category.image_url || defaultImg;
        if (!category.image_url && category.image) {
            catImg = (category.image.startsWith('http://') || category.image.startsWith('https://') || category.image.startsWith('//'))
                ? category.image
                : ("{{ asset('') }}".replace(/\/$/, '') + '/' + category.image.replace(/^\//, ''));
        }
        previewEl.src = catImg;
        document.getElementById('edit_cat_image_url').value = category.image || '';

        var translations = category.translations || {};
        var langs = ['es', 'ar', 'de', 'zh', 'ja', 'th', 'fr', 'it', 'ru', 'pt', 'ko'];
        langs.forEach(function(code) {
            var nameEl = document.getElementById('edit_trans_' + code + '_name');
            var descEl = document.getElementById('edit_trans_' + code + '_desc');
            if (nameEl) {
                nameEl.value = (translations[code] && translations[code].name) ? translations[code].name : '';
            }
            if (descEl) {
                descEl.value = (translations[code] && translations[code].description) ? translations[code].description : '';
            }
        });

        // Switch to English tab by default
        var firstTabBtn = document.querySelector('.tab-btn');
        if (firstTabBtn) {
            switchTab('tab_en', firstTabBtn);
        }

        document.getElementById('editCategoryModal').classList.add('active');
    }

    function openNewCategoryModal() {
        document.getElementById('newCategoryModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
</script>
@endsection
