<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Aczet CMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0A192F 0%, #0F2C59 60%, #1A3E74 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: #FFFFFF;
            width: 100%;
            max-width: 440px;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-logo {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #0F2C59;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            font-size: 0.95rem;
        }

        .btn-submit {
            background: #0284C7;
            color: #FFFFFF;
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.35);
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="brand-header">
        <div class="brand-logo">aczet<span style="color:#0284C7;">.</span></div>
        <h3 style="font-size: 1.25rem; color: #0F172A; margin-top: 10px; font-weight: 700;">Password Recovery</h3>
        <p style="font-size: 0.85rem; color: #64748B; margin-top: 4px;">Enter your admin email to receive secure password reset instructions.</p>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            <div style="margin-top: 10px;">
                <a href="{{ route('admin.reset_password', ['email' => old('email', 'admin@aczet.com')]) }}" style="color: #065F46; font-weight: 700; text-decoration: underline;">
                    Click here to set new password &rarr;
                </a>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.forgot_password.submit') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label><i class="fa-solid fa-envelope"></i> Administrator Email</label>
            <input type="email" name="email" value="{{ old('email', 'admin@aczet.com') }}" class="form-control" placeholder="admin@aczet.com" required>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-paper-plane"></i> Send Recovery Link
        </button>
    </form>

    <div style="margin-top: 24px; text-align: center; border-top: 1px solid #E2E8F0; padding-top: 16px;">
        <a href="{{ route('admin.login') }}" style="font-size: 0.85rem; color: #64748B; text-decoration: none; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Back to Sign In
        </a>
    </div>
</div>

    <script src="{{ vasset('js/aczet-validator.js') }}"></script>
</body>
</html>
