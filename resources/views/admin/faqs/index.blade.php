@extends('layouts.admin')

@section('title', 'Product FAQs Manager - Aczet CMS')
@section('page_title', 'Product FAQs & Knowledge Base Manager')

@section('content')
<!-- Header Stats & Action Bar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-question" style="color: var(--accent);"></i> Product FAQs & Knowledge Base
        </h3>
        <p style="font-size: 0.88rem; color: #64748B; margin-top: 4px;">
            Manage technical questions and answers extracted from official Aczet product documentation across all 25 categories.
        </p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <a href="{{ route('faqs.index') }}" target="_blank" class="btn-sm btn-outline" style="text-decoration: none; padding: 10px 16px; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live FAQ Hub
        </a>
        <button class="btn-primary" onclick="openCreateFaqModal()">
            <i class="fa-solid fa-circle-plus"></i> Add New FAQ
        </button>
    </div>
</div>

<!-- KPI Metrics Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 18px 22px; margin-bottom: 0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 46px; height: 46px; border-radius: 12px; background: rgba(14, 165, 233, 0.12); color: #0EA5E9; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
            <i class="fa-solid fa-list-check"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Total FAQs</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #0F172A;">{{ $totalFaqs }}</div>
        </div>
    </div>
    <div class="card" style="padding: 18px 22px; margin-bottom: 0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 46px; height: 46px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Active Online</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #0F172A;">{{ $activeFaqs }}</div>
        </div>
    </div>
    <div class="card" style="padding: 18px 22px; margin-bottom: 0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 46px; height: 46px; border-radius: 12px; background: rgba(249, 115, 22, 0.12); color: #F97316; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Product Categories</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #0F172A;">{{ $totalCategories }}</div>
        </div>
    </div>
    <div class="card" style="padding: 18px 22px; margin-bottom: 0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 46px; height: 46px; border-radius: 12px; background: rgba(139, 92, 246, 0.12); color: #8B5CF6; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
            <i class="fa-solid fa-network-wired"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; color: #64748B; font-weight: 700; text-transform: uppercase;">Business Groups</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #0F172A;">{{ $groups->count() }}</div>
        </div>
    </div>
</div>

<!-- Filters & Search Toolbar -->
<div class="card" style="padding: 18px 22px; margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.faqs') }}" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
        <!-- Search Input -->
        <div style="flex: 2; min-width: 240px; position: relative;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by question, answer, category..." class="form-control" style="padding-left: 38px;">
        </div>

        <!-- Business Group Filter -->
        <div style="flex: 1; min-width: 180px;">
            <select name="group" class="form-control" onchange="this.form.submit()">
                <option value="">All Business Groups</option>
                @foreach($groups as $grp)
                    <option value="{{ $grp }}" {{ request('group') == $grp ? 'selected' : '' }}>{{ $grp }}</option>
                @endforeach
            </select>
        </div>

        <!-- Category Filter -->
        <div style="flex: 1; min-width: 180px;">
            <select name="category" class="form-control" onchange="this.form.submit()">
                <option value="">All Categories ({{ $categoriesList->count() }})</option>
                @foreach($categoriesList as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div style="width: 130px;">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Submit & Reset Buttons -->
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-primary" style="padding: 10px 18px;">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'group', 'category', 'status']))
                <a href="{{ route('admin.faqs') }}" class="btn-sm btn-outline" style="text-decoration: none; padding: 10px 14px; border-radius: 8px; font-weight: 700;">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- FAQ Items Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center;">Order</th>
                <th style="width: 200px;">Group & Category</th>
                <th>Question & Answer Details</th>
                <th style="width: 170px;">Linked Catalog</th>
                <th style="width: 100px; text-align: center;">Status</th>
                <th style="width: 130px; text-align: right; padding-right: 24px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($faqs as $faq)
                <tr>
                    <td style="text-align: center;">
                        <span style="display: inline-block; width: 28px; height: 28px; line-height: 28px; border-radius: 6px; background: #F1F5F9; color: #475569; font-weight: 800; font-size: 0.8rem;">
                            {{ $faq->sort_order }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 800; font-size: 0.9rem; color: #0F172A; margin-bottom: 4px;">
                            {{ $faq->category_name }}
                        </div>
                        <span style="font-size: 0.72rem; padding: 3px 8px; border-radius: 12px; font-weight: 700; background: #EFF6FF; color: #1E40AF; border: 1px solid #DBEAFE; display: inline-block;">
                            {{ $faq->group_name }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 700; font-size: 0.92rem; color: #0F172A; margin-bottom: 6px; line-height: 1.4;">
                            <span style="color: var(--accent); margin-right: 4px;">Q:</span> {{ $faq->question }}
                        </div>
                        <div style="font-size: 0.84rem; color: #64748B; line-height: 1.5; white-space: pre-line; max-height: 52px; overflow: hidden; position: relative;">
                            {{ Str::limit($faq->answer, 180) }}
                        </div>
                    </td>
                    <td>
                        @if($faq->category)
                            <div style="display: inline-flex; align-items: center; gap: 6px; background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">
                                <i class="fa-solid fa-link fa-xs"></i> {{ $faq->category->name }}
                            </div>
                        @else
                            <span style="color: #94A3B8; font-size: 0.78rem; font-style: italic;">Auto Category Match</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <form action="{{ route('admin.faqs.toggle_status', $faq->id) }}" method="POST" style="margin: 0; display: inline-block;">
                            @csrf
                            <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;" title="Click to toggle status">
                                @if($faq->is_active)
                                    <span class="badge badge-success" style="cursor: pointer; padding: 5px 10px;">
                                        <i class="fa-solid fa-check"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-danger" style="cursor: pointer; padding: 5px 10px;">
                                        <i class="fa-solid fa-ban"></i> Inactive
                                    </span>
                                @endif
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right; padding-right: 24px;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <button class="btn-sm btn-outline" onclick='openEditFaqModal(@json($faq))' style="padding: 6px 12px; font-size: 0.8rem;" title="Edit FAQ">
                                <i class="fa-solid fa-pen-to-square" style="color: var(--accent);"></i>
                            </button>
                            <form action="{{ route('admin.faqs.delete', $faq->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger" style="padding: 6px 12px; font-size: 0.8rem;" title="Delete FAQ">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 48px 20px; color: #64748B;">
                        <div style="font-size: 2.5rem; color: #CBD5E1; margin-bottom: 12px;"><i class="fa-solid fa-circle-question"></i></div>
                        <div style="font-weight: 700; font-size: 1.05rem; color: #1E293B;">No FAQs Found</div>
                        <p style="font-size: 0.88rem; margin-top: 4px;">Try modifying your search query or filter parameters.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Footer -->
    @if($faqs->hasPages())
        <div style="padding: 0 24px 20px;">
            {{ $faqs->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>

<!-- Add New FAQ Modal -->
<div class="modal-backdrop" id="createFaqModal">
    <div class="modal-card" style="max-width: 720px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;">
                    <i class="fa-solid fa-circle-plus" style="color: var(--accent);"></i> Add New Product FAQ
                </h3>
                <p style="font-size: 0.85rem; color: #64748B;">Add a technical question and comprehensive answer to the product knowledge base.</p>
            </div>
            <button onclick="closeModal('createFaqModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Business Group <span style="color: red;">*</span></label>
                    <select name="group_name" class="form-control" required id="create_group_name">
                        <option value="Laboratory Weighing">Laboratory Weighing</option>
                        <option value="Jewellery & Precious-Metal Solutions">Jewellery & Precious-Metal Solutions</option>
                        <option value="Laser Solutions">Laser Solutions</option>
                        <option value="Industrial Weighing">Industrial Weighing</option>
                        <option value="Laboratory & Analytical Instruments">Laboratory & Analytical Instruments</option>
                        <option value="General">General</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Product Category Name <span style="color: red;">*</span></label>
                    <input type="text" name="category_name" class="form-control" placeholder="e.g. Microbalance, Analytical Balance..." required list="categoryListOptions">
                    <datalist id="categoryListOptions">
                        @foreach($categoriesList as $cName)
                            <option value="{{ $cName }}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Link to Catalog Category (Optional)</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- No Direct Link (Display on FAQ Hub) --</option>
                        @foreach($allCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0" min="0">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Question <span style="color: red;">*</span></label>
                <input type="text" name="question" class="form-control" placeholder="e.g. What is the difference between a microbalance and an analytical balance?" required>
            </div>

            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="font-size: 0.85rem; font-weight: 700; color: #0F172A;">Detailed Answer <span style="color: red;">*</span></label>
                    <small style="color: #64748B;">You can use bullet points (• ) for lists</small>
                </div>
                <textarea name="answer" class="form-control" rows="5" placeholder="Enter the clear technical answer here..." required style="resize: vertical;"></textarea>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid #E2E8F0;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.88rem; font-weight: 700;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: var(--accent);">
                    Publish immediately (Active online)
                </label>
                <div style="display: flex; gap: 12px;">
                    <button type="button" class="btn-sm btn-outline" onclick="closeModal('createFaqModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Save & Publish FAQ</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit FAQ Modal -->
<div class="modal-backdrop" id="editFaqModal">
    <div class="modal-card" style="max-width: 720px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;">
                    <i class="fa-solid fa-pen-to-square" style="color: var(--accent);"></i> Edit Product FAQ
                </h3>
                <p style="font-size: 0.85rem; color: #64748B;" id="editModalSubtitle">Update question, answer text, or category assignment.</p>
            </div>
            <button onclick="closeModal('editFaqModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form id="editFaqForm" action="" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Business Group <span style="color: red;">*</span></label>
                    <select name="group_name" id="edit_group_name" class="form-control" required>
                        <option value="Laboratory Weighing">Laboratory Weighing</option>
                        <option value="Jewellery & Precious-Metal Solutions">Jewellery & Precious-Metal Solutions</option>
                        <option value="Laser Solutions">Laser Solutions</option>
                        <option value="Industrial Weighing">Industrial Weighing</option>
                        <option value="Laboratory & Analytical Instruments">Laboratory & Analytical Instruments</option>
                        <option value="General">General</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Product Category Name <span style="color: red;">*</span></label>
                    <input type="text" name="category_name" id="edit_category_name" class="form-control" required list="categoryListOptions">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Link to Catalog Category (Optional)</label>
                    <select name="category_id" id="edit_category_id" class="form-control">
                        <option value="">-- No Direct Link (Display on FAQ Hub) --</option>
                        @foreach($allCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Sort Order</label>
                    <input type="number" name="sort_order" id="edit_sort_order" class="form-control" min="0">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Question <span style="color: red;">*</span></label>
                <input type="text" name="question" id="edit_question" class="form-control" required>
            </div>

            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="font-size: 0.85rem; font-weight: 700; color: #0F172A;">Detailed Answer <span style="color: red;">*</span></label>
                    <small style="color: #64748B;">You can use bullet points (• ) for lists</small>
                </div>
                <textarea name="answer" id="edit_answer" class="form-control" rows="5" required style="resize: vertical;"></textarea>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid #E2E8F0;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.88rem; font-weight: 700;">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="width: 18px; height: 18px; accent-color: var(--accent);">
                    Active online
                </label>
                <div style="display: flex; gap: 12px;">
                    <button type="button" class="btn-sm btn-outline" onclick="closeModal('editFaqModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Update FAQ</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateFaqModal() {
    document.getElementById('createFaqModal').classList.add('active');
}

function openEditFaqModal(faq) {
    const form = document.getElementById('editFaqForm');
    form.action = `/admin/faqs/${faq.id}/update`;

    document.getElementById('edit_group_name').value = faq.group_name || 'General';
    document.getElementById('edit_category_name').value = faq.category_name || '';
    document.getElementById('edit_category_id').value = faq.category_id || '';
    document.getElementById('edit_sort_order').value = faq.sort_order ?? 0;
    document.getElementById('edit_question').value = faq.question || '';
    document.getElementById('edit_answer').value = faq.answer || '';
    document.getElementById('edit_is_active').checked = !!faq.is_active;

    document.getElementById('editModalSubtitle').innerText = `Editing FAQ #${faq.id} (${faq.category_name})`;
    document.getElementById('editFaqModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Close on backdrop click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
        e.target.classList.remove('active');
    }
});
</script>
@endsection

