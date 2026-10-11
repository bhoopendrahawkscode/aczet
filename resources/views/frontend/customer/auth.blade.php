@extends('layouts.app')

@section('title', 'Customer Sign In & Sign Up - Aczet Precision Metrology')
@section('meta_description', 'Sign in or create your Aczet customer account to track quotations, view calibration certificates, and access priority support.')

@section('content')
<!-- Breadcrumb Navigation -->
<div style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; padding: 12px 0;">
    <div style="width: min(1200px, calc(100% - 48px)); margin: 0 auto;">
        <nav aria-label="breadcrumb">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #00A3C4; flex-wrap: wrap;">
                <li><a href="{{ route('home') }}" style="color: #00A3C4; text-decoration: none; font-weight: 500;">Home</a></li>
                <li style="color: #94A3B8; font-size: 11px;">&gt;</li>
                <li style="color: #0F172A; font-weight: 600;">Customer Portal</li>
            </ol>
        </nav>
    </div>
</div>

<div style="background: linear-gradient(135deg, #F8FAFC 0%, #F0F9FF 50%, #E6F6F9 100%); color: #0F172A; padding: clamp(40px, 5vw, 65px) 5%; min-height: calc(100vh - 180px); display: flex; align-items: center; position: relative; overflow: hidden; border-bottom: 1px solid #E2E8F0;">
    <!-- Ambient Blue Glow Matching Aczet Metrology Theme -->
    <div style="position: absolute; top: -60px; right: -60px; width: 450px; height: 450px; background: radial-gradient(circle, rgba(0, 163, 196, 0.12) 0%, rgba(2, 132, 199, 0.05) 50%, transparent 70%); border-radius: 50%; filter: blur(50px); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -80px; left: -80px; width: 420px; height: 420px; background: radial-gradient(circle, rgba(0, 163, 196, 0.09) 0%, transparent 70%); border-radius: 50%; filter: blur(50px); pointer-events: none;"></div>

    <div class="container-responsive" style="margin: 0 auto; padding: 0; width: 100%; max-width: 1200px; position: relative; z-index: 2;">
        <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: clamp(30px, 5vw, 60px); align-items: center;" class="auth-grid-layout">
            
            <!-- Left Side: Customer Account Value & Benefits -->
            <div>
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(0, 163, 196, 0.1); border: 1px solid rgba(0, 163, 196, 0.28); color: #00A3C4; padding: 6px 14px; border-radius: 30px; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 16px;">
                    <i class="fa-solid fa-circle-user"></i> Customer &amp; Client Portal
                </div>

                <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: clamp(2rem, 3.6vw, 2.75rem); font-weight: 800; line-height: 1.25; margin-bottom: 16px; color: #0F172A;">
                    Precision Measurement &amp; Client Services
                </h1>

                <p style="color: #475569; font-size: clamp(0.95rem, 1.8vw, 1.05rem); line-height: 1.65; margin-bottom: 28px;">
                    Access your quotations, order updates, calibration records, and priority laboratory assistance in one unified portal.
                </p>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div style="display: flex; align-items: flex-start; gap: 14px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px 18px; box-shadow: 0 4px 14px rgba(0, 163, 196, 0.04);">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(0, 163, 196, 0.12); display: flex; align-items: center; justify-content: center; color: #00A3C4; font-size: 1rem; flex-shrink: 0; margin-top: 2px;">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 0.98rem; font-weight: 700; color: #0F172A; margin: 0 0 3px;">Track RFQs &amp; Orders</h4>
                            <p style="font-size: 0.84rem; color: #64748B; margin: 0; line-height: 1.5;">Real-time progress on your commercial proposals, test samples, and equipment dispatches.</p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 14px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px 18px; box-shadow: 0 4px 14px rgba(0, 163, 196, 0.04);">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: center; color: #10B981; font-size: 1rem; flex-shrink: 0; margin-top: 2px;">
                            <i class="fa-solid fa-certificate"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 0.98rem; font-weight: 700; color: #0F172A; margin: 0 0 3px;">Calibration &amp; Technical Manuals</h4>
                            <p style="font-size: 0.84rem; color: #64748B; margin: 0; line-height: 1.5;">Direct access to ISO/IEC 17025 certificates, 21 CFR Part 11 documentation, and software drivers.</p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 14px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px 18px; box-shadow: 0 4px 14px rgba(0, 163, 196, 0.04);">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(139, 92, 246, 0.12); display: flex; align-items: center; justify-content: center; color: #8B5CF6; font-size: 1rem; flex-shrink: 0; margin-top: 2px;">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 0.98rem; font-weight: 700; color: #0F172A; margin: 0 0 3px;">Priority Technical Assistance</h4>
                            <p style="font-size: 0.84rem; color: #64748B; margin: 0; line-height: 1.5;">Direct access to Aczet metrology engineers for fast troubleshooting, IQ/OQ support, and service bookings.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Dual-Mode Auth Card (Login & Sign Up) -->
            <div style="width: 100%;">
                <div style="background: #FFFFFF; border-radius: 16px; padding: clamp(24px, 4vw, 36px); box-shadow: 0 16px 40px rgba(0, 163, 196, 0.1), 0 4px 16px rgba(15, 23, 42, 0.05); border: 1px solid #E2E8F0; width: 100%; box-sizing: border-box; color: #0F172A;">
                    
                    <!-- Tab Switcher -->
                    <div style="display: flex; background: #F1F5F9; border-radius: 10px; padding: 4px; margin-bottom: 24px;">
                        <button type="button" 
                                id="btnTabLogin"
                                onclick="switchAuthTab('login')" 
                                style="flex: 1; padding: 10px 16px; border: none; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; background: {{ ($activeTab ?? 'login') === 'login' ? 'linear-gradient(135deg, #0284C7 0%, #0369A1 100%)' : 'transparent' }}; color: {{ ($activeTab ?? 'login') === 'login' ? '#FFFFFF' : '#64748B' }}; box-shadow: {{ ($activeTab ?? 'login') === 'login' ? '0 4px 12px rgba(2, 132, 199, 0.28)' : 'none' }};">
                            <i class="fa-solid fa-arrow-right-to-bracket" style="margin-right: 6px;"></i> Customer Login
                        </button>
                        <button type="button" 
                                id="btnTabRegister"
                                onclick="switchAuthTab('register')" 
                                style="flex: 1; padding: 10px 16px; border: none; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; background: {{ ($activeTab ?? 'login') === 'register' ? 'linear-gradient(135deg, #0284C7 0%, #0369A1 100%)' : 'transparent' }}; color: {{ ($activeTab ?? 'login') === 'register' ? '#FFFFFF' : '#64748B' }}; box-shadow: {{ ($activeTab ?? 'login') === 'register' ? '0 4px 12px rgba(2, 132, 199, 0.28)' : 'none' }};">
                            <i class="fa-solid fa-user-plus" style="margin-right: 6px;"></i> Sign Up
                        </button>
                    </div>

                    @if($errors->any())
                        <div style="background: #FEF2F2; border-left: 4px solid #EF4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; color: #991B1B; font-size: 0.85rem; font-weight: 600;">
                            <i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div style="background: #F0FDF4; border-left: 4px solid #10B981; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; color: #166534; font-size: 0.85rem; font-weight: 600;">
                            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                        </div>
                    @endif

                    <!-- ======================= 1. LOGIN FORM ======================= -->
                    <div id="authPaneLogin" style="display: {{ ($activeTab ?? 'login') === 'login' ? 'block' : 'none' }};">
                        <div style="margin-bottom: 20px;">
                            <h2 style="font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">
                                Welcome Back
                            </h2>
                            <p style="color: #64748B; font-size: 0.88rem; margin: 0;">Sign in to your Aczet customer account</p>
                        </div>

                        <form action="{{ route('customer.login.submit') }}" method="POST">
                            @csrf

                            <div style="margin-bottom: 16px;">
                                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Email Address *</label>
                                <div style="position: relative;">
                                    <i class="fa-solid fa-envelope" style="position: absolute; left: 16px; top: 14px; color: #94A3B8; font-size: 0.95rem;"></i>
                                    <input type="email" name="email" data-label="Email Address" value="{{ old('email') }}" required class="form-control" style="padding-left: 44px; width: 100%; height: 44px; border: 1px solid #CBD5E1; border-radius: 8px; box-sizing: border-box;" placeholder="e.g. john.smith@company.com">
                                </div>
                            </div>

                            <div style="margin-bottom: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; flex-wrap: wrap; gap: 4px;">
                                    <label style="font-size: 0.85rem; font-weight: 700; color: #0F172A; margin: 0;">Password *</label>
                                    <a href="{{ route('contact') }}" style="color: #00A3C4; font-size: 0.8rem; font-weight: 700; text-decoration: none;">Forgot Password?</a>
                                </div>
                                <div style="position: relative;">
                                    <i class="fa-solid fa-lock" style="position: absolute; left: 16px; top: 14px; color: #94A3B8; font-size: 0.95rem;"></i>
                                    <input type="password" name="password" data-label="Password" required class="form-control" style="padding-left: 44px; width: 100%; height: 44px; border: 1px solid #CBD5E1; border-radius: 8px; box-sizing: border-box;" placeholder="••••••••">
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; margin-bottom: 22px;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #475569; font-size: 0.85rem; font-weight: 600;">
                                    <input type="checkbox" name="remember" checked style="accent-color: #0284C7; width: 16px; height: 16px;"> Keep me signed in on this device
                                </label>
                            </div>

                            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-size: 0.96rem; font-weight: 800; border-radius: 10px; background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%); color: #FFFFFF; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.28);">
                                <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In to Customer Account
                            </button>
                        </form>

                        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #F1F5F9; text-align: center; font-size: 0.86rem; color: #64748B;">
                            Don't have an account yet? 
                            <a href="javascript:void(0)" onclick="switchAuthTab('register')" style="color: #00A3C4; font-weight: 700; text-decoration: none;">Create Free Account</a>
                        </div>
                    </div>

                    <!-- ======================= 2. REGISTER / SIGN UP FORM ======================= -->
                    <div id="authPaneRegister" style="display: {{ ($activeTab ?? 'login') === 'register' ? 'block' : 'none' }};">
                        <div style="margin-bottom: 20px;">
                            <h2 style="font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0 0 4px; font-family: 'Space Grotesk', sans-serif;">
                                Create Customer Account
                            </h2>
                            <p style="color: #64748B; font-size: 0.88rem; margin: 0;">Register to manage inquiries and metrology services</p>
                        </div>

                        <form action="{{ route('customer.register.submit') }}" method="POST">
                            @csrf

                            <div style="margin-bottom: 14px;">
                                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Full Name *</label>
                                <div style="position: relative;">
                                    <i class="fa-solid fa-user" style="position: absolute; left: 16px; top: 14px; color: #94A3B8; font-size: 0.95rem;"></i>
                                    <input type="text" name="name" data-label="Full Name" value="{{ old('name') }}" required class="form-control" style="padding-left: 44px; width: 100%; height: 44px; border: 1px solid #CBD5E1; border-radius: 8px; box-sizing: border-box;" placeholder="e.g. Dr. John Smith">
                                </div>
                            </div>

                            <div style="margin-bottom: 14px;">
                                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Email Address *</label>
                                <div style="position: relative;">
                                    <i class="fa-solid fa-envelope" style="position: absolute; left: 16px; top: 14px; color: #94A3B8; font-size: 0.95rem;"></i>
                                    <input type="email" name="email" data-label="Email Address" value="{{ old('email') }}" required class="form-control" style="padding-left: 44px; width: 100%; height: 44px; border: 1px solid #CBD5E1; border-radius: 8px; box-sizing: border-box;" placeholder="e.g. john.smith@company.com">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                                <div>
                                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Company / Org</label>
                                    <div style="position: relative;">
                                        <i class="fa-solid fa-building" style="position: absolute; left: 14px; top: 14px; color: #94A3B8; font-size: 0.9rem;"></i>
                                        <input type="text" name="company" data-label="Company Name" value="{{ old('company') }}" class="form-control" style="padding-left: 40px; width: 100%; height: 44px; border: 1px solid #CBD5E1; border-radius: 8px; box-sizing: border-box;" placeholder="e.g. Pharma Lab Ltd">
                                    </div>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Phone</label>
                                    <div style="position: relative;">
                                        <i class="fa-solid fa-phone" style="position: absolute; left: 14px; top: 14px; color: #94A3B8; font-size: 0.9rem;"></i>
                                        <input type="text" name="phone" data-label="Phone Number" value="{{ old('phone') }}" class="form-control" style="padding-left: 40px; width: 100%; height: 44px; border: 1px solid #CBD5E1; border-radius: 8px; box-sizing: border-box;" placeholder="e.g. +91 98765 43210">
                                    </div>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                                <div>
                                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Password *</label>
                                    <div style="position: relative;">
                                        <i class="fa-solid fa-lock" style="position: absolute; left: 14px; top: 14px; color: #94A3B8; font-size: 0.9rem;"></i>
                                        <input type="password" name="password" data-label="Password" required class="form-control" style="padding-left: 40px; width: 100%; height: 44px; border: 1px solid #CBD5E1; border-radius: 8px; box-sizing: border-box;" placeholder="••••••••">
                                    </div>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Confirm *</label>
                                    <div style="position: relative;">
                                        <i class="fa-solid fa-lock" style="position: absolute; left: 14px; top: 14px; color: #94A3B8; font-size: 0.9rem;"></i>
                                        <input type="password" name="password_confirmation" data-label="Confirm Password" required class="form-control" style="padding-left: 40px; width: 100%; height: 44px; border: 1px solid #CBD5E1; border-radius: 8px; box-sizing: border-box;" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-size: 0.96rem; font-weight: 800; border-radius: 10px; background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%); color: #FFFFFF; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.28);">
                                <i class="fa-solid fa-user-plus"></i> Create Customer Account
                            </button>
                        </form>

                        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #F1F5F9; text-align: center; font-size: 0.86rem; color: #64748B;">
                            Already registered? 
                            <a href="javascript:void(0)" onclick="switchAuthTab('login')" style="color: #00A3C4; font-weight: 700; text-decoration: none;">Sign In</a>
                        </div>
                    </div>

                    <!-- Subtle Distributor Extranet Option -->
                    <div style="margin-top: 18px; padding-top: 14px; border-top: 1px dashed #E2E8F0; text-align: center; font-size: 0.8rem; color: #94A3B8;">
                        Are you an authorized distributor? 
                        <a href="{{ route('dealer_portal') }}" style="color: #00A3C4; font-weight: 600; text-decoration: none;">Apply or Access Extranet</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function switchAuthTab(tab) {
        var paneLogin = document.getElementById('authPaneLogin');
        var paneRegister = document.getElementById('authPaneRegister');
        var btnLogin = document.getElementById('btnTabLogin');
        var btnRegister = document.getElementById('btnTabRegister');

        if (tab === 'register') {
            paneLogin.style.display = 'none';
            paneRegister.style.display = 'block';
            btnRegister.style.background = 'linear-gradient(135deg, #0284C7 0%, #0369A1 100%)';
            btnRegister.style.color = '#FFFFFF';
            btnRegister.style.boxShadow = '0 4px 12px rgba(2, 132, 199, 0.28)';
            btnLogin.style.background = 'transparent';
            btnLogin.style.color = '#64748B';
            btnLogin.style.boxShadow = 'none';
        } else {
            paneRegister.style.display = 'none';
            paneLogin.style.display = 'block';
            btnLogin.style.background = 'linear-gradient(135deg, #0284C7 0%, #0369A1 100%)';
            btnLogin.style.color = '#FFFFFF';
            btnLogin.style.boxShadow = '0 4px 12px rgba(2, 132, 199, 0.28)';
            btnRegister.style.background = 'transparent';
            btnRegister.style.color = '#64748B';
            btnRegister.style.boxShadow = 'none';
        }
    }
</script>

<style>
    @media (max-width: 900px) {
        .auth-grid-layout {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

