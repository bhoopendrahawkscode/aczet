@extends('layouts.admin')

@section('title', 'Global Exhibitions & Trade Fairs Manager - Aczet CMS')
@section('page_title', 'Global Exhibitions & Event Manager')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-calendar-star" style="color: var(--accent);"></i> Global Exhibitions & Trade Fairs
        </h3>
        <p style="font-size: 0.88rem; color: #64748B; margin-top: 4px;">Manage global metrology expos, venue locations, booth numbers, and multi-language event listings.</p>
    </div>
    <button class="btn-primary" onclick="openNewExModal()">
        <i class="fa-solid fa-calendar-plus"></i> Add New Exhibition
    </button>
</div>

<!-- Exhibitions Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width: 70px;">ID</th>
                <th>Event Date</th>
                <th>Exhibition Title & Description</th>
                <th>Location & Booth</th>
                <th>Languages</th>
                <th style="text-align: right; padding-right: 28px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exhibitions as $ex)
                <tr>
                    <td><span style="font-weight: 800; color: #94A3B8;">#{{ $ex->id }}</span></td>
                    <td>
                        <span class="badge" style="background: #EFF6FF; color: #1D4ED8; font-size: 0.8rem; font-weight: 700; border: 1px solid #BFDBFE;">
                            <i class="fa-solid fa-calendar-day"></i> {{ $ex->event_date->format('M d, Y') }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 800; font-size: 0.95rem; color: var(--primary);">{{ $ex->title }}</div>
                        <div style="font-size: 0.82rem; color: #64748B; margin-top: 2px; max-width: 380px;">{{ Str::limit($ex->description, 80) }}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.88rem; font-weight: 600; color: #0F172A;"><i class="fa-solid fa-location-dot" style="color: var(--accent);"></i> {{ $ex->location }}</div>
                        <div style="font-size: 0.8rem; color: #64748B;">{{ $ex->hall_booth }}</div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 4px; flex-wrap: wrap; max-width: 220px;">
                            <span style="background: #0F172A; color: #FFF; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; font-weight: 800;">EN</span>
                            @if(is_array($ex->translations))
                                @foreach(array_keys($ex->translations) as $lCode)
                                    <span style="background: #D1FAE5; color: #065F46; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; font-weight: 700;">{{ strtoupper($lCode) }}</span>
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td style="text-align: right; padding-right: 28px;">
                        <button class="btn-sm btn-outline" onclick="openEditExModal({{ json_encode($ex) }})" style="padding: 8px 16px;">
                            <i class="fa-solid fa-language" style="color: var(--accent);"></i> Edit & Translate
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Tabbed Edit Exhibition Modal -->
<div class="modal-backdrop" id="editExModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;" id="editExModalTitle">Edit Exhibition & Translations</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Update event details and localized titles/descriptions across languages.</p>
            </div>
            <button onclick="closeModal('editExModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form id="editExForm" method="POST" action="">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Event Date *</label>
                    <input type="date" name="event_date" id="edit_ex_date" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Location / City *</label>
                    <input type="text" name="location" id="edit_ex_loc" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Hall / Booth Number</label>
                    <input type="text" name="hall_booth" id="edit_ex_booth" class="form-control">
                </div>
            </div>

            <!-- Language Selection Tabs -->
            <div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 12px; margin-bottom: 24px; border-bottom: 1px solid #E2E8F0;">
                <button type="button" class="tab-ex-btn active" onclick="switchExTab('ex_tab_en', this)" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #CBD5E1; background: #0F172A; color: #FFF; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    English (Default)
                </button>
                @foreach($locales as $code => $label)
                    <button type="button" class="tab-ex-btn" onclick="switchExTab('ex_tab_{{ $code }}', this)" style="padding: 8px 14px; border-radius: 8px; border: 1px solid #CBD5E1; background: #F8FAFC; color: #334155; font-weight: 600; font-size: 0.85rem; cursor: pointer; white-space: nowrap;">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- English Default -->
            <div id="ex_tab_en" class="ex-tab-content" style="display: block;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Exhibition Title (English) *</label>
                    <input type="text" name="title" id="edit_ex_title" required class="form-control">
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Description (English)</label>
                    <textarea name="description" id="edit_ex_desc" rows="3" class="form-control"></textarea>
                </div>
            </div>

            <!-- Other Languages -->
            @foreach($locales as $code => $label)
                <div id="ex_tab_{{ $code }}" class="ex-tab-content" style="display: none;">
                    <div style="background: #FFF5F0; border-left: 4px solid var(--accent); padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                        <span style="font-weight: 700; color: var(--accent); font-size: 0.85rem;"><i class="fa-solid fa-language"></i> {{ $label }} Translation</span>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Localized Title ({{ $label }})</label>
                        <input type="text" name="translations[{{ $code }}][title]" id="edit_ex_trans_{{ $code }}_title" class="form-control" placeholder="Enter localized title">
                    </div>
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Localized Description ({{ $label }})</label>
                        <textarea name="translations[{{ $code }}][description]" id="edit_ex_trans_{{ $code }}_desc" rows="3" class="form-control" placeholder="Enter localized description"></textarea>
                    </div>
                </div>
            @endforeach

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('editExModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Save Exhibition
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add New Exhibition Modal -->
<div class="modal-backdrop" id="newExModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;">Add Global Exhibition / Trade Show</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Schedule upcoming trade fairs, expos, and scientific conferences.</p>
            </div>
            <button onclick="closeModal('newExModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form action="{{ route('admin.exhibitions.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Exhibition Name (English) *</label>
                <input type="text" name="title" required class="form-control" placeholder="e.g. Analytica Munich 2026">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Event Date *</label>
                    <input type="date" name="event_date" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Location / City *</label>
                    <input type="text" name="location" required class="form-control" placeholder="e.g. Munich, Germany">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Hall / Booth</label>
                    <input type="text" name="hall_booth" class="form-control" placeholder="e.g. Hall A1, Stand 402">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Event Description</label>
                <textarea name="description" rows="3" class="form-control" placeholder="Describe exhibition focus..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('newExModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Create Exhibition
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchExTab(tabId, btn) {
        document.querySelectorAll('.ex-tab-content').forEach(function(el) {
            el.style.display = 'none';
        });
        document.querySelectorAll('.tab-ex-btn').forEach(function(el) {
            el.style.background = '#F8FAFC';
            el.style.color = '#334155';
            el.style.fontWeight = '600';
        });
        document.getElementById(tabId).style.display = 'block';
        btn.style.background = '#0F172A';
        btn.style.color = '#FFF';
        btn.style.fontWeight = '700';
    }

    function openEditExModal(ex) {
        document.getElementById('editExModalTitle').innerText = 'Edit ' + ex.title;
        document.getElementById('editExForm').action = '/admin/exhibitions/' + ex.id + '/update';
        document.getElementById('edit_ex_title').value = ex.title || '';
        document.getElementById('edit_ex_loc').value = ex.location || '';
        document.getElementById('edit_ex_booth').value = ex.hall_booth || '';
        document.getElementById('edit_ex_date').value = ex.event_date ? ex.event_date.substring(0, 10) : '';
        document.getElementById('edit_ex_desc').value = ex.description || '';

        var translations = ex.translations || {};
        var langs = ['es', 'ar', 'de', 'zh', 'ja', 'th', 'fr', 'it', 'ru', 'pt', 'ko'];
        langs.forEach(function(code) {
            var titleEl = document.getElementById('edit_ex_trans_' + code + '_title');
            var descEl = document.getElementById('edit_ex_trans_' + code + '_desc');
            if (titleEl) {
                titleEl.value = (translations[code] && translations[code].title) ? translations[code].title : '';
            }
            if (descEl) {
                descEl.value = (translations[code] && translations[code].description) ? translations[code].description : '';
            }
        });

        var firstTab = document.querySelector('.tab-ex-btn');
        if (firstTab) switchExTab('ex_tab_en', firstTab);

        document.getElementById('editExModal').classList.add('active');
    }

    function openNewExModal() {
        document.getElementById('newExModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
</script>
@endsection
