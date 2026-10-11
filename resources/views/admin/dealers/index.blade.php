@extends('layouts.admin')

@section('title', 'Authorized Dealers & Distribution Partners - Aczet CMS')
@section('page_title', 'Authorized Dealers & Channel Partners')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-handshake" style="color: var(--accent);"></i> Authorized Dealers & Partner Extranet
        </h3>
        <p style="font-size: 0.88rem; color: #64748B; margin-top: 4px;">Manage regional distributors, wholesale pricing discount tiers, territory exclusivity, and extranet login access.</p>
    </div>
    <button class="btn-primary" onclick="openNewDealerModal()">
        <i class="fa-solid fa-user-plus"></i> Add New Partner
    </button>
</div>

<!-- Dealers Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th>Company & Contact</th>
                <th>Territory / Country</th>
                <th>Pricing Tier & Margin</th>
                <th>Account Status</th>
                <th style="text-align: right; padding-right: 28px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dealers as $dealer)
                <tr>
                    <td><span style="font-weight: 800; color: #94A3B8;">#{{ $dealer->id }}</span></td>
                    <td>
                        <div style="font-weight: 800; font-size: 0.95rem; color: var(--primary);">{{ $dealer->company_name }}</div>
                        <div style="font-size: 0.82rem; color: #64748B; margin-top: 2px;">
                            <i class="fa-solid fa-user"></i> {{ $dealer->contact_person }} &bull; <i class="fa-solid fa-envelope"></i> {{ $dealer->email }} &bull; <i class="fa-solid fa-phone"></i> {{ $dealer->phone }}
                        </div>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: #0F172A; display: flex; align-items: center; gap: 6px; font-size: 0.9rem;">
                            <i class="fa-solid fa-earth-americas" style="color: var(--accent);"></i> {{ $dealer->country }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #10B981; font-size: 0.95rem;">{{ $dealer->discount_percentage }}% Off MSRP</div>
                        <div style="font-size: 0.78rem; color: #64748B; font-weight: 600;">{{ $dealer->tier }}</div>
                    </td>
                    <td>
                        @if($dealer->status === 'Approved')
                            <span class="badge" style="background: #D1FAE5; color: #065F46; font-size: 0.78rem; font-weight: 800; border: 1px solid #A7F3D0;">
                                <i class="fa-solid fa-circle-check"></i> Approved
                            </span>
                        @elseif($dealer->status === 'Pending')
                            <span class="badge" style="background: #FEF3C7; color: #92400E; font-size: 0.78rem; font-weight: 800; border: 1px solid #FDE68A;">
                                <i class="fa-solid fa-clock"></i> Pending Review
                            </span>
                        @else
                            <span class="badge" style="background: #FEE2E2; color: #991B1B; font-size: 0.78rem; font-weight: 800; border: 1px solid #FECACA;">
                                <i class="fa-solid fa-ban"></i> Suspended
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right; padding-right: 28px;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            @if($dealer->status !== 'Approved')
                                <form action="{{ route('admin.dealers.approve', $dealer->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-primary" style="background: #10B981; padding: 6px 12px; font-size: 0.8rem;" title="Approve Dealer">
                                        <i class="fa-solid fa-check"></i> Approve
                                    </button>
                                </form>
                            @endif

                            <button class="btn-sm btn-outline" onclick="openEditDealerModal({{ json_encode($dealer) }})" style="padding: 6px 12px;">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>

                            <form action="{{ route('admin.dealers.delete', $dealer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this dealer partner?');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-outline" style="color: #EF4444; border-color: #FECACA; padding: 6px 10px;" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Edit Dealer Modal -->
<div class="modal-backdrop" id="editDealerModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;" id="editDealerModalTitle">Edit Dealer Partner</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Update partner status, wholesale discount margin, and account manager.</p>
            </div>
            <button onclick="closeModal('editDealerModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form id="editDealerForm" method="POST" action="">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Company Name *</label>
                    <input type="text" name="company_name" id="edit_dl_company" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Contact Person *</label>
                    <input type="text" name="contact_person" id="edit_dl_contact" required class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Corporate Email *</label>
                    <input type="email" name="email" id="edit_dl_email" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Phone Number *</label>
                    <input type="text" name="phone" id="edit_dl_phone" required class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Account Status *</label>
                    <select name="status" id="edit_dl_status" class="form-control">
                        <option value="Approved">Approved (Active)</option>
                        <option value="Pending">Pending Review</option>
                        <option value="Suspended">Suspended</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Discount % *</label>
                    <input type="number" step="0.01" name="discount_percentage" id="edit_dl_disc" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Partner Tier *</label>
                    <select name="tier" id="edit_dl_tier" class="form-control">
                        <option value="Tier 1 Gold Partner">Tier 1 Gold Partner (35%)</option>
                        <option value="Tier 2 Silver Partner">Tier 2 Silver Partner (25%)</option>
                        <option value="Tier 3 Bronze Partner">Tier 3 Bronze Partner (15%)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Territory / Country *</label>
                    <input type="text" name="country" id="edit_dl_country" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Assigned Account Director</label>
                    <input type="text" name="account_manager" id="edit_dl_mgr" class="form-control">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Reset Password (Leave blank to keep current)</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('editDealerModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Save Partner Settings
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add New Dealer Modal -->
<div class="modal-backdrop" id="newDealerModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;">Add Authorized Dealer Account</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Provision a direct channel partner extranet account with wholesale pricing.</p>
            </div>
            <button onclick="closeModal('newDealerModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form action="{{ route('admin.dealers.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Company Name *</label>
                    <input type="text" name="company_name" required class="form-control" placeholder="e.g. Apex Scientific Supplies">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Contact Person *</label>
                    <input type="text" name="contact_person" required class="form-control" placeholder="e.g. Johnathan Miller">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Corporate Email *</label>
                    <input type="email" name="email" required class="form-control" placeholder="partner@apexscientific.com">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Login Password *</label>
                    <input type="password" name="password" required class="form-control" placeholder="••••••••">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Phone Number *</label>
                    <input type="text" name="phone" required class="form-control" placeholder="+1 555 234 5678">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Discount % *</label>
                    <input type="number" step="0.01" name="discount_percentage" value="35.00" required class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Partner Tier *</label>
                    <select name="tier" class="form-control">
                        <option value="Tier 1 Gold Partner">Tier 1 Gold Partner (35%)</option>
                        <option value="Tier 2 Silver Partner">Tier 2 Silver Partner (25%)</option>
                        <option value="Tier 3 Bronze Partner">Tier 3 Bronze Partner (15%)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Territory / Country *</label>
                    <input type="text" name="country" required class="form-control" placeholder="e.g. United States">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Account Status *</label>
                    <select name="status" class="form-control">
                        <option value="Approved">Approved (Active Extranet)</option>
                        <option value="Pending">Pending Review</option>
                        <option value="Suspended">Suspended</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('newDealerModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-user-plus"></i> Create Partner Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditDealerModal(dealer) {
        document.getElementById('editDealerModalTitle').innerText = 'Edit ' + dealer.company_name;
        document.getElementById('editDealerForm').action = '/admin/dealers/' + dealer.id + '/update';
        document.getElementById('edit_dl_company').value = dealer.company_name || '';
        document.getElementById('edit_dl_contact').value = dealer.contact_person || '';
        document.getElementById('edit_dl_email').value = dealer.email || '';
        document.getElementById('edit_dl_phone').value = dealer.phone || '';
        document.getElementById('edit_dl_country').value = dealer.country || '';
        document.getElementById('edit_dl_disc').value = dealer.discount_percentage || '35.00';
        document.getElementById('edit_dl_tier').value = dealer.tier || 'Tier 1 Gold Partner';
        document.getElementById('edit_dl_status').value = dealer.status || 'Approved';
        document.getElementById('edit_dl_mgr').value = dealer.account_manager || '';

        document.getElementById('editDealerModal').classList.add('active');
    }

    function openNewDealerModal() {
        document.getElementById('newDealerModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
</script>
@endsection
