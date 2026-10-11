@extends('layouts.admin')

@section('title', 'Technical Publications & Whitepapers Manager - Aczet CMS')
@section('page_title', 'Publications & Whitepaper Document Manager')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-file-pdf" style="color: var(--accent);"></i> Technical Publications & Compliance Whitepapers
        </h3>
        <p style="font-size: 0.88rem; color: #64748B; margin-top: 4px;">Upload downloadable PDF whitepapers, application notes, and manage localized titles/summaries in 12 languages.</p>
    </div>
    <button class="btn-primary" onclick="openNewPubModal()">
        <i class="fa-solid fa-cloud-arrow-up"></i> Upload New Whitepaper
    </button>
</div>

<!-- Publications Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width: 70px;">ID</th>
                <th>Category</th>
                <th>Title & Summary</th>
                <th>Downloadable PDF</th>
                <th>Languages</th>
                <th style="text-align: right; padding-right: 28px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($publications as $pub)
                <tr>
                    <td><span style="font-weight: 800; color: #94A3B8;">#{{ $pub->id }}</span></td>
                    <td>
                        <span class="badge" style="background: #EFF6FF; color: #1D4ED8; font-size: 0.78rem; font-weight: 700; border: 1px solid #BFDBFE;">
                            {{ $pub->category }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 800; font-size: 0.95rem; color: var(--primary);">{{ $pub->title }}</div>
                        <div style="font-size: 0.82rem; color: #64748B; margin-top: 2px; max-width: 420px;">{{ Str::limit($pub->summary, 85) }}</div>
                    </td>
                    <td>
                        @if($pub->file)
                            <a href="{{ $pub->file }}" target="_blank" download class="btn-sm btn-outline" style="color: var(--accent); border-color: #FED7AA; background: #FFF7ED; padding: 6px 12px;">
                                <i class="fa-solid fa-file-pdf"></i> Download PDF
                            </a>
                        @else
                            <span style="color: #94A3B8; font-size: 0.8rem;">No file</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 4px; flex-wrap: wrap; max-width: 220px;">
                            <span style="background: #0F172A; color: #FFF; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; font-weight: 800;">EN</span>
                            @if(is_array($pub->translations))
                                @foreach(array_keys($pub->translations) as $lCode)
                                    <span style="background: #D1FAE5; color: #065F46; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; font-weight: 700;">{{ strtoupper($lCode) }}</span>
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td style="text-align: right; padding-right: 28px;">
                        <button class="btn-sm btn-outline" onclick="openEditPubModal({{ json_encode($pub) }})" style="padding: 8px 16px;">
                            <i class="fa-solid fa-language" style="color: var(--accent);"></i> Edit & Translate
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Tabbed Edit Publication Modal -->
<div class="modal-backdrop" id="editPubModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;" id="editPubModalTitle">Edit Technical Publication</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Upload updated PDF and manage translations.</p>
            </div>
            <button onclick="closeModal('editPubModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form id="editPubForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Category *</label>
                    <input type="text" name="category" id="edit_pub_category" required class="form-control" placeholder="e.g. Regulatory Metrology">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Upload New PDF Document</label>
                    <input type="file" name="pdf_file" accept="application/pdf" class="form-control" style="font-size: 0.82rem;">
                </div>
            </div>

            <!-- Language Selection Tabs -->
            <div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 12px; margin-bottom: 24px; border-bottom: 1px solid #E2E8F0;">
                <button type="button" class="tab-pub-btn active" onclick="switchPubTab('pub_tab_en', this)" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #CBD5E1; background: #0F172A; color: #FFF; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    English (Default)
                </button>
                @foreach($locales as $code => $label)
                    <button type="button" class="tab-pub-btn" onclick="switchPubTab('pub_tab_{{ $code }}', this)" style="padding: 8px 14px; border-radius: 8px; border: 1px solid #CBD5E1; background: #F8FAFC; color: #334155; font-weight: 600; font-size: 0.85rem; cursor: pointer; white-space: nowrap;">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- English Default -->
            <div id="pub_tab_en" class="pub-tab-content" style="display: block;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Publication Title (English) *</label>
                    <input type="text" name="title" id="edit_pub_title" required class="form-control">
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Summary (English)</label>
                    <textarea name="summary" id="edit_pub_summary" rows="3" class="form-control"></textarea>
                </div>
            </div>

            <!-- Other Languages -->
            @foreach($locales as $code => $label)
                <div id="pub_tab_{{ $code }}" class="pub-tab-content" style="display: none;">
                    <div style="background: #FFF5F0; border-left: 4px solid var(--accent); padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                        <span style="font-weight: 700; color: var(--accent); font-size: 0.85rem;"><i class="fa-solid fa-language"></i> {{ $label }} Translations</span>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Localized Title ({{ $label }})</label>
                        <input type="text" name="translations[{{ $code }}][title]" id="edit_pub_trans_{{ $code }}_title" class="form-control" placeholder="Enter localized title">
                    </div>
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Localized Summary ({{ $label }})</label>
                        <textarea name="translations[{{ $code }}][summary]" id="edit_pub_trans_{{ $code }}_sum" rows="3" class="form-control" placeholder="Enter localized summary"></textarea>
                    </div>
                </div>
            @endforeach

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('editPubModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Save Publication
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Upload New Publication Modal -->
<div class="modal-backdrop" id="newPubModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;">Upload New Technical Publication</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Add whitepapers, compliance guides, or application notes with PDF download attachments.</p>
            </div>
            <button onclick="closeModal('newPubModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form action="{{ route('admin.publications.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Title (English) *</label>
                    <input type="text" name="title" required class="form-control" placeholder="e.g. Microbalance Weighing Dynamics in ISO 17025">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Category *</label>
                    <input type="text" name="category" required class="form-control" placeholder="e.g. Calibration Science">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Upload PDF Document *</label>
                <input type="file" name="pdf_file" accept="application/pdf" class="form-control">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Summary (English)</label>
                <textarea name="summary" rows="3" class="form-control" placeholder="Brief technical abstract..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('newPubModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload & Publish
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchPubTab(tabId, btn) {
        document.querySelectorAll('.pub-tab-content').forEach(function(el) {
            el.style.display = 'none';
        });
        document.querySelectorAll('.tab-pub-btn').forEach(function(el) {
            el.style.background = '#F8FAFC';
            el.style.color = '#334155';
            el.style.fontWeight = '600';
        });
        document.getElementById(tabId).style.display = 'block';
        btn.style.background = '#0F172A';
        btn.style.color = '#FFF';
        btn.style.fontWeight = '700';
    }

    function openEditPubModal(pub) {
        document.getElementById('editPubModalTitle').innerText = 'Edit ' + pub.title;
        document.getElementById('editPubForm').action = '/admin/publications/' + pub.id + '/update';
        document.getElementById('edit_pub_title').value = pub.title || '';
        document.getElementById('edit_pub_category').value = pub.category || '';
        document.getElementById('edit_pub_summary').value = pub.summary || '';

        var translations = pub.translations || {};
        var langs = ['es', 'ar', 'de', 'zh', 'ja', 'th', 'fr', 'it', 'ru', 'pt', 'ko'];
        langs.forEach(function(code) {
            var titleEl = document.getElementById('edit_pub_trans_' + code + '_title');
            var sumEl = document.getElementById('edit_pub_trans_' + code + '_sum');
            if (titleEl) {
                titleEl.value = (translations[code] && translations[code].title) ? translations[code].title : '';
            }
            if (sumEl) {
                sumEl.value = (translations[code] && translations[code].summary) ? translations[code].summary : '';
            }
        });

        var firstTab = document.querySelector('.tab-pub-btn');
        if (firstTab) switchPubTab('pub_tab_en', firstTab);

        document.getElementById('editPubModal').classList.add('active');
    }

    function openNewPubModal() {
        document.getElementById('newPubModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
</script>
@endsection
