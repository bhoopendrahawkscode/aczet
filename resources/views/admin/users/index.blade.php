@extends('layouts.admin')

@section('title', 'System Users & Staff Accounts - Aczet CMS')
@section('page_title', 'System Administrators & Staff')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-users-gear" style="color: var(--accent);"></i> System Users & Administrator Accounts
        </h3>
        <p style="font-size: 0.88rem; color: #64748B; margin-top: 4px;">Manage back-office administrative users, staff permissions, and security credentials.</p>
    </div>
    <button class="btn-primary" onclick="openNewUserModal()">
        <i class="fa-solid fa-user-plus"></i> Add Administrator
    </button>
</div>

<!-- Users Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th>Administrator</th>
                <th>Email Address</th>
                <th>Account Role</th>
                <th>Created Date</th>
                <th style="text-align: right; padding-right: 28px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
                <tr>
                    <td><span style="font-weight: 800; color: #94A3B8;">#{{ $u->id }}</span></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($u->avatar)
                                <img src="{{ $u->avatar }}" alt="{{ $u->name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid #E2E8F0;">
                            @else
                                <div style="width: 38px; height: 38px; border-radius: 50%; background: #0284C7; color: #FFF; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.95rem;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 800; color: var(--primary);">{{ $u->name }}</div>
                                @if($u->id === auth()->id())
                                    <span style="background: #E0E7FF; color: #3730A3; font-size: 0.72rem; font-weight: 800; padding: 2px 6px; border-radius: 4px;">YOU (CURRENT)</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td><span style="color: #475569; font-weight: 600;">{{ $u->email }}</span></td>
                    <td>
                        <span class="badge" style="background: #EDE9FE; color: #5B21B6; font-weight: 800; font-size: 0.8rem; border: 1px solid #DDD6FE;">
                            <i class="fa-solid fa-shield"></i> Super Admin
                        </span>
                    </td>
                    <td><span style="font-size: 0.85rem; color: #64748B;">{{ $u->created_at ? $u->created_at->format('M d, Y') : 'N/A' }}</span></td>
                    <td style="text-align: right; padding-right: 28px;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <button class="btn-sm btn-outline" onclick="openEditUserModal({{ json_encode($u) }})" style="padding: 6px 12px;">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>

                            @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this administrator?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-outline" style="color: #EF4444; border-color: #FECACA; padding: 6px 10px;" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Edit User Modal -->
<div class="modal-backdrop" id="editUserModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;" id="editUserModalTitle">Edit User</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Update administrator name, email or password.</p>
            </div>
            <button onclick="closeModal('editUserModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form id="editUserForm" method="POST" action="">
            @csrf
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Full Name *</label>
                <input type="text" name="name" id="edit_u_name" required class="form-control">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Email Address *</label>
                <input type="email" name="email" id="edit_u_email" required class="form-control">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">New Password (Leave blank to keep unchanged)</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('editUserModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add New User Modal -->
<div class="modal-backdrop" id="newUserModal">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0F172A;">Add System Administrator</h3>
                <p style="font-size: 0.85rem; color: #64748B;">Grant back-office access to manage products, leads, and dealers.</p>
            </div>
            <button onclick="closeModal('newUserModal')" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Full Name *</label>
                <input type="text" name="name" required class="form-control" placeholder="e.g. Sarah Jenkins">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Email Address *</label>
                <input type="email" name="email" required class="form-control" placeholder="sarah@aczet.com">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Password *</label>
                <input type="password" name="password" required class="form-control" placeholder="••••••••">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeModal('newUserModal')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-user-plus"></i> Create Administrator
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditUserModal(user) {
        document.getElementById('editUserModalTitle').innerText = 'Edit ' + user.name;
        document.getElementById('editUserForm').action = '/admin/users/' + user.id + '/update';
        document.getElementById('edit_u_name').value = user.name || '';
        document.getElementById('edit_u_email').value = user.email || '';

        document.getElementById('editUserModal').classList.add('active');
    }

    function openNewUserModal() {
        document.getElementById('newUserModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
</script>
@endsection
