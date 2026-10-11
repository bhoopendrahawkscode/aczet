@extends('layouts.admin')

@section('title', 'Admin Profile & Security Settings - Aczet CMS')
@section('page_title', 'Admin Profile & Security')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    
    <!-- Profile Header Info Banner -->
    <div class="card" style="background: linear-gradient(135deg, #0F2C59 0%, #0A192F 100%); color: #FFF; padding: 32px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 24px; margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.1); border: 3px solid var(--accent); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="fa-solid fa-user-shield fa-2xl" style="color: #FFF;"></i>
                @endif
            </div>
            <div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: #FFF;">{{ $user->name }}</h3>
                <p style="color: #94A3B8; font-size: 0.9rem;"><i class="fa-solid fa-envelope"></i> {{ $user->email }}</p>
                <span class="badge" style="background: rgba(229,86,4,0.25); color: #FF9F66; margin-top: 6px; border: 1px solid rgba(229,86,4,0.4);">
                    <i class="fa-solid fa-crown"></i> Super Administrator
                </span>
            </div>
        </div>
        <div>
            <span style="font-size: 0.82rem; color: #94A3B8;">Last Login: Today</span>
        </div>
    </div>

    @if($errors->any())
        <div style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px;">
            <div style="font-weight: 700; margin-bottom: 4px;"><i class="fa-solid fa-circle-exclamation"></i> Please correct the following:</div>
            <ul style="margin-left: 20px; font-size: 0.88rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px;">
        
        <!-- Profile Information Card -->
        <div class="card">
            <h4 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-id-card" style="color: var(--accent);"></i> Update Profile Details
            </h4>
            <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 24px;">Change your administrator name, primary email, and profile photo.</p>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Avatar Upload with Live Preview -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 16px;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #FFF; border: 2px dashed #CBD5E1; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <img id="avatarPreview" src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0F2C59&color=fff' }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Profile Avatar</label>
                        <input type="file" name="avatar_file" accept="image/*" onchange="previewAvatar(this)" style="font-size: 0.8rem; color: #64748B;">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-control" placeholder="Administrator Name">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-control" placeholder="admin@aczet.com">
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-floppy-disk"></i> Save Profile Details
                </button>
            </form>
        </div>

        <!-- Change Password Card -->
        <div class="card">
            <h4 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-lock" style="color: var(--accent);"></i> Change Password
            </h4>
            <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 24px;">Ensure your account uses a strong password of at least 8 characters.</p>

            <form action="{{ route('admin.profile.change_password') }}" method="POST">
                @csrf

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Current Password *</label>
                    <div style="position: relative;">
                        <input type="password" name="current_password" id="current_pass" required class="form-control" placeholder="••••••••">
                        <button type="button" onclick="togglePass('current_pass', 'eye1')" style="position: absolute; right: 12px; top: 12px; background: none; border: none; color: #94A3B8; cursor: pointer;">
                            <i class="fa-solid fa-eye" id="eye1"></i>
                        </button>
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">New Password (Min. 8 characters) *</label>
                    <div style="position: relative;">
                        <input type="password" name="new_password" id="new_pass" required minlength="8" class="form-control" placeholder="••••••••">
                        <button type="button" onclick="togglePass('new_pass', 'eye2')" style="position: absolute; right: 12px; top: 12px; background: none; border: none; color: #94A3B8; cursor: pointer;">
                            <i class="fa-solid fa-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Confirm New Password *</label>
                    <div style="position: relative;">
                        <input type="password" name="new_password_confirmation" id="confirm_pass" required minlength="8" class="form-control" placeholder="••••••••">
                        <button type="button" onclick="togglePass('confirm_pass', 'eye3')" style="position: absolute; right: 12px; top: 12px; background: none; border: none; color: #94A3B8; cursor: pointer;">
                            <i class="fa-solid fa-eye" id="eye3"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-shield-halved"></i> Update Password
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePass(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
