@extends('layouts.admin')

@section('title', 'Site Content & Settings - Aczet CMS')
@section('page_title', 'Site Content & Global Settings')

@section('content')
<div class="card" style="max-width: 1100px; margin: 0 auto; padding: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 18px; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h3 style="font-size: 1.35rem; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">
                <i class="fa-solid fa-sliders" style="color: var(--accent);"></i> Website Content & Configuration CMS
            </h3>
            <p style="font-size: 0.86rem; color: #64748B; margin: 0;">
                Control every piece of public website text: contact numbers, emails, corporate addresses, header announcements, homepage metrics, and footer notices.
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('home') }}" target="_blank" class="btn-sm btn-outline" style="text-decoration: none;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Preview Live Site
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div style="display: flex; gap: 8px; border-bottom: 1.5px solid #E2E8F0; margin-bottom: 24px; overflow-x: auto; padding-bottom: 4px;">
        <button type="button" class="settings-tab-btn active" onclick="switchSettingsTab('general', this)">
            <i class="fa-solid fa-globe"></i> General & Header
        </button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('contact', this)">
            <i class="fa-solid fa-headset"></i> Contact & Helpdesk
        </button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('address', this)">
            <i class="fa-solid fa-location-dot"></i> Global Offices
        </button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('metrics', this)">
            <i class="fa-solid fa-chart-line"></i> Homepage Metrics
        </button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('features', this)">
            <i class="fa-solid fa-microchip"></i> Feature Pillars
        </button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('footer', this)">
            <i class="fa-solid fa-shoe-prints"></i> Footer & Legal
        </button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('about', this)">
            <i class="fa-solid fa-address-card"></i> About Us Page CMS
        </button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('social', this)">
            <i class="fa-solid fa-share-nodes"></i> Social Links
        </button>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- ============================================================== -->
        <!-- TAB 1: GENERAL & HEADER                                        -->
        <!-- ============================================================== -->
        <div id="tab-general" class="settings-tab-pane active">
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                <i class="fa-solid fa-circle-info" style="color: var(--accent);"></i> Identity & Header Announcement Bar
            </h4>

            <!-- Branding: Official Logo & Browser Favicon Management -->
            <div style="background: linear-gradient(135deg, #F0F9FF 0%, #FFFFFF 100%); border: 1.5px solid #BAE6FD; border-radius: 14px; padding: 22px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.05);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.06em; display: block;">
                            <i class="fa-solid fa-palette"></i> Brand Visual Identity
                        </span>
                        <h5 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin: 2px 0 0 0;">Website Logo & Browser Favicon Management</h5>
                    </div>
                    <span style="font-size: 0.76rem; background: #E0F2FE; color: #0369A1; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                        <i class="fa-solid fa-check-double"></i> Live Dynamic Update
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Logo Card -->
                    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <label style="font-size: 0.88rem; font-weight: 700; color: #0F172A; margin: 0;">
                                    <i class="fa-solid fa-image" style="color: var(--accent);"></i> Website Logo
                                </label>
                                <span style="font-size: 0.72rem; color: #64748B;">Header, Footer & Admin</span>
                            </div>

                            <!-- Live Preview Box -->
                            <div style="background: #F8FAFC; border: 1.5px dashed #CBD5E1; border-radius: 10px; padding: 14px; text-align: center; margin-bottom: 14px; min-height: 90px; display: flex; align-items: center; justify-content: center;">
                                @php
                                    $currentLogo = site_setting('site_logo', 'images/logo.png');
                                @endphp
                                <img id="site_logo_preview" 
                                     src="{{ vasset($currentLogo) }}" 
                                     alt="Website Logo Preview" 
                                     style="max-height: 52px; max-width: 220px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));"
                                     onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                            </div>

                            <div style="margin-bottom: 12px;">
                                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #334155; margin-bottom: 4px;">
                                    <i class="fa-solid fa-cloud-arrow-up" style="color: var(--accent);"></i> Upload New Logo File
                                </label>
                                <input type="file" 
                                       name="site_logo_file" 
                                       id="site_logo_file" 
                                       accept="image/png,image/jpeg,image/svg+xml,image/webp" 
                                       class="form-control" 
                                       style="font-size: 0.82rem; padding: 7px 10px;"
                                       onchange="previewUploadedImage(this, 'site_logo_preview')">
                                <span style="font-size: 0.72rem; color: #64748B; margin-top: 3px; display: block;">
                                    Transparent PNG, SVG, or high-res JPG. Recommended width: 300–500px.
                                </span>
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.76rem; font-weight: 600; color: #64748B; margin-bottom: 3px;">
                                    Or Logo File Path / URL
                                </label>
                                <input type="text" 
                                       name="site_logo" 
                                       value="{{ $allSettings['site_logo'] ?? '' }}" 
                                       placeholder="e.g. /images/logo.png" 
                                       class="form-control" 
                                       style="font-size: 0.82rem;">
                            </div>
                        </div>
                    </div>

                    <!-- Favicon Card -->
                    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <label style="font-size: 0.88rem; font-weight: 700; color: #0F172A; margin: 0;">
                                    <i class="fa-solid fa-star" style="color: var(--accent);"></i> Browser Favicon Icon
                                </label>
                                <span style="font-size: 0.72rem; color: #64748B;">Browser Tab & Bookmarks</span>
                            </div>

                            <!-- Live Preview Box -->
                            <div style="background: #F8FAFC; border: 1.5px dashed #CBD5E1; border-radius: 10px; padding: 14px; text-align: center; margin-bottom: 14px; min-height: 90px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;">
                                @php
                                    $currentFavicon = site_setting('site_favicon', 'favicon.ico');
                                @endphp
                                <div style="display: flex; align-items: center; gap: 10px; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 6px 14px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                                    <img id="site_favicon_preview" 
                                         src="{{ vasset($currentFavicon) }}" 
                                         alt="Favicon Preview" 
                                         style="width: 32px; height: 32px; object-fit: contain; display: block;"
                                         onerror="this.onerror=null; this.src='{{ asset('favicon.ico') }}';">
                                    <span style="font-size: 0.82rem; font-weight: 700; color: #1E293B;">aczet.com</span>
                                </div>
                                <span style="font-size: 0.72rem; color: #94A3B8;">Live Browser Tab Simulation</span>
                            </div>

                            <div style="margin-bottom: 12px;">
                                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #334155; margin-bottom: 4px;">
                                    <i class="fa-solid fa-cloud-arrow-up" style="color: var(--accent);"></i> Upload New Favicon Icon
                                </label>
                                <input type="file" 
                                       name="site_favicon_file" 
                                       id="site_favicon_file" 
                                       accept=".ico,image/x-icon,image/png,image/svg+xml" 
                                       class="form-control" 
                                       style="font-size: 0.82rem; padding: 7px 10px;"
                                       onchange="previewUploadedImage(this, 'site_favicon_preview')">
                                <span style="font-size: 0.72rem; color: #64748B; margin-top: 3px; display: block;">
                                    Standard .ico, .png, or .svg icon. Sizes: 16x16, 32x32, 64x64, 128x128.
                                </span>
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.76rem; font-weight: 600; color: #64748B; margin-bottom: 3px;">
                                    Or Favicon File Path / URL
                                </label>
                                <input type="text" 
                                       name="site_favicon" 
                                       value="{{ $allSettings['site_favicon'] ?? '' }}" 
                                       placeholder="e.g. /favicon.ico" 
                                       class="form-control" 
                                       style="font-size: 0.82rem;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Company / Brand Name</label>
                    <input type="text" name="site_name" value="{{ $allSettings['site_name'] ?? 'Aczet Metrology' }}" class="form-control" required>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Tagline / Baseline</label>
                    <input type="text" name="site_tagline" value="{{ $allSettings['site_tagline'] ?? 'Precision Metrology & High Accuracy Weighing Systems' }}" class="form-control">
                </div>
            </div>

            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
                    <i class="fa-solid fa-bullhorn"></i> Header Topbar Announcement Strip
                </span>
                
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Badge Highlight Text</label>
                        <input type="text" name="header_topbar_badge" value="{{ $allSettings['header_topbar_badge'] ?? 'WORLDWIDE DISPATCH' }}" class="form-control" placeholder="e.g. WORLDWIDE DISPATCH">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Announcement Message</label>
                        <input type="text" name="header_topbar_text" value="{{ $allSettings['header_topbar_text'] ?? 'ISO 9001:2015 & OIML Certified Metrology Systems | Direct Factory Dispatch' }}" class="form-control">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Announcement Link Destination</label>
                    <input type="text" name="header_topbar_link" value="{{ $allSettings['header_topbar_link'] ?? '/products' }}" class="form-control" placeholder="/products or https://...">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Header Call to Action Button Text</label>
                    <input type="text" name="header_cta_label" value="{{ $allSettings['header_cta_label'] ?? 'Request RFQ' }}" class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Header Call to Action Link</label>
                    <input type="text" name="header_cta_link" value="{{ $allSettings['header_cta_link'] ?? '/quote-cart' }}" class="form-control">
                </div>
            </div>

            <!-- Service Manual Password Management (Document Requirement) -->
            <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 8px;">
                    <i class="fa-solid fa-lock"></i> Service Manual Access Security
                </span>
                <p style="font-size: 0.82rem; color: #64748B; margin: 0 0 14px 0;">
                    Manage the global authorization passcode required to unlock confidential service manuals across all precision equipment.
                </p>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Service Manual Master Passcode</label>
                    <input type="text" name="service_manual_password" value="{{ $allSettings['service_manual_password'] ?? 'Aczet@Service2026' }}" class="form-control" style="max-width: 400px;" placeholder="e.g. Aczet@Service2026">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TAB 2: CONTACT & HELPDESK                                      -->
        <!-- ============================================================== -->
        <div id="tab-contact" class="settings-tab-pane" style="display: none;">
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                <i class="fa-solid fa-headset" style="color: var(--accent);"></i> Customer Inquiries, Sales & Support Desks
            </h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Primary Sales Helpline</label>
                    <input type="text" name="phone_primary" value="{{ $allSettings['phone_primary'] ?? '+91 22 4141 5555' }}" class="form-control" required>
                    <span style="font-size: 0.74rem; color: #94A3B8; margin-top: 4px; display: block;">Displays on the header helpline, sticky support bar, and contact page.</span>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Technical Support Helpline</label>
                    <input type="text" name="phone_support" value="{{ $allSettings['phone_support'] ?? '+91 22 4141 5500' }}" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Sales Inquiries Email</label>
                    <input type="email" name="email_sales" value="{{ $allSettings['email_sales'] ?? 'sales@aczet.com' }}" class="form-control" required>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Customer Support Email</label>
                    <input type="email" name="email_support" value="{{ $allSettings['email_support'] ?? 'support@aczet.com' }}" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">WhatsApp Support Number</label>
                    <input type="text" name="whatsapp_number" value="{{ $allSettings['whatsapp_number'] ?? '+919820000000' }}" class="form-control" placeholder="+919820000000">
                    <span style="font-size: 0.74rem; color: #94A3B8; margin-top: 4px; display: block;">Include international country code without spaces for instant click-to-chat.</span>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Operating / Support Hours</label>
                    <input type="text" name="working_hours" value="{{ $allSettings['working_hours'] ?? 'Monday - Friday: 9:00 AM - 6:30 PM (IST)' }}" class="form-control">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TAB 3: GLOBAL OFFICES & ADDRESSES                              -->
        <!-- ============================================================== -->
        <div id="tab-address" class="settings-tab-pane" style="display: none;">
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                <i class="fa-solid fa-building" style="color: var(--accent);"></i> Physical Locations & Regional Hubs
            </h4>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Global Corporate Headquarters (India)</label>
                <textarea name="address_hq" rows="2" class="form-control">{{ $allSettings['address_hq'] ?? 'Aczet Metrology Pvt. Ltd., 201, Commercial Center, Mumbai 400069, Maharashtra, India' }}</textarea>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">European Technical Center (Switzerland)</label>
                <textarea name="address_technical_center" rows="2" class="form-control">{{ $allSettings['address_technical_center'] ?? 'Aczet European Technical Center, Neugasse 29, 8005 Zürich, Switzerland' }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Americas Regional Office (USA)</label>
                    <textarea name="address_usa" rows="2" class="form-control">{{ $allSettings['address_usa'] ?? 'Aczet America Inc., 1250 E Business Center Dr, Mount Prospect, IL 60056, USA' }}</textarea>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Southeast Asia Hub (Thailand)</label>
                    <textarea name="address_thailand" rows="2" class="form-control">{{ $allSettings['address_thailand'] ?? 'Aczet Southeast Asia, 88/12 Rama IX Road, Huai Khwang, Bangkok 10310, Thailand' }}</textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TAB 4: HOMEPAGE METRICS & STATISTICS                           -->
        <!-- ============================================================== -->
        <div id="tab-metrics" class="settings-tab-pane" style="display: none;">
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                <i class="fa-solid fa-chart-pie" style="color: var(--accent);"></i> Homepage Statistical Bento Counters
            </h4>
            <p style="font-size: 0.84rem; color: #64748B; margin-bottom: 20px;">
                These four figures dynamically drive the high-impact trust metrics displayed on the homepage.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <!-- Metric 1 -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Metric #1: Experience Lineage</span>
                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Value (e.g. 40+)</label>
                        <input type="text" name="metric_experience_years" value="{{ $allSettings['metric_experience_years'] ?? '40+' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Caption Label</label>
                        <input type="text" name="metric_experience_label" value="{{ $allSettings['metric_experience_label'] ?? 'Years of Metrology Excellence' }}" class="form-control">
                    </div>
                </div>

                <!-- Metric 2 -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Metric #2: Export Footprint</span>
                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Value (e.g. 95+)</label>
                        <input type="text" name="metric_countries_count" value="{{ $allSettings['metric_countries_count'] ?? '95+' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Caption Label</label>
                        <input type="text" name="metric_countries_label" value="{{ $allSettings['metric_countries_label'] ?? 'Worldwide Export Destinations' }}" class="form-control">
                    </div>
                </div>

                <!-- Metric 3 -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Metric #3: Installed Active Base</span>
                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Value (e.g. 150,000+)</label>
                        <input type="text" name="metric_installed_units" value="{{ $allSettings['metric_installed_units'] ?? '150,000+' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Caption Label</label>
                        <input type="text" name="metric_installed_label" value="{{ $allSettings['metric_installed_label'] ?? 'Global Active Installations' }}" class="form-control">
                    </div>
                </div>

                <!-- Metric 4 -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Metric #4: Service & Calibration Network</span>
                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Value (e.g. 12+)</label>
                        <input type="text" name="metric_calibration_centers" value="{{ $allSettings['metric_calibration_centers'] ?? '12+' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Caption Label</label>
                        <input type="text" name="metric_calibration_label" value="{{ $allSettings['metric_calibration_label'] ?? 'ISO 17025 Certified Centers' }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TAB 5: FEATURE PILLARS & HIGHLIGHTS                            -->
        <!-- ============================================================== -->
        <div id="tab-features" class="settings-tab-pane" style="display: none;">
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                <i class="fa-solid fa-microchip" style="color: var(--accent);"></i> Why Choose Aczet - 4 Core Engineering Pillars
            </h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <!-- Pillar 1 -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Pillar #1</span>
                    <div style="margin: 10px 0;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Title</label>
                        <input type="text" name="pillar_1_title" value="{{ $allSettings['pillar_1_title'] ?? 'Monobloc EMFR Technology' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Description</label>
                        <textarea name="pillar_1_desc" rows="2" class="form-control">{{ $allSettings['pillar_1_desc'] ?? 'Single-block electromagnetic force restoration sensor delivering nanogram-level repeatability and thermal stability.' }}</textarea>
                    </div>
                </div>

                <!-- Pillar 2 -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Pillar #2</span>
                    <div style="margin: 10px 0;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Title</label>
                        <input type="text" name="pillar_2_title" value="{{ $allSettings['pillar_2_title'] ?? 'isoCAL Automated Calibration' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Description</label>
                        <textarea name="pillar_2_desc" rows="2" class="form-control">{{ $allSettings['pillar_2_desc'] ?? 'Internal calibration weights trigger automatically based on temperature changes and scheduled intervals.' }}</textarea>
                    </div>
                </div>

                <!-- Pillar 3 -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Pillar #3</span>
                    <div style="margin: 10px 0;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Title</label>
                        <input type="text" name="pillar_3_title" value="{{ $allSettings['pillar_3_title'] ?? '21 CFR Part 11 Compliance' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Description</label>
                        <textarea name="pillar_3_desc" rows="2" class="form-control">{{ $allSettings['pillar_3_desc'] ?? 'Integrated audit trail, tamper-proof user management, and electronic signatures for pharmaceutical compliance.' }}</textarea>
                    </div>
                </div>

                <!-- Pillar 4 -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Pillar #4</span>
                    <div style="margin: 10px 0;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Title</label>
                        <input type="text" name="pillar_4_title" value="{{ $allSettings['pillar_4_title'] ?? 'Universal Connectivity' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Description</label>
                        <textarea name="pillar_4_desc" rows="2" class="form-control">{{ $allSettings['pillar_4_desc'] ?? 'Ethernet, RS232, USB, and Wi-Fi connectivity for direct LIMS, ERP, and precision PLC automation networks.' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TAB 6: FOOTER & LEGAL                                          -->
        <!-- ============================================================== -->
        <div id="tab-footer" class="settings-tab-pane" style="display: none;">
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                <i class="fa-solid fa-shoe-prints" style="color: var(--accent);"></i> Footer Paragraph, Copyright & Certification Marks
            </h4>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Footer About Company Paragraph</label>
                <textarea name="footer_about_text" rows="3" class="form-control">{{ $allSettings['footer_about_text'] ?? 'Aczet has pioneered the future of precision metrology for over 35 years. Engineered with Swiss-grade monobloc load cells and German electromagnetic force restoration technology, our instruments serve pharmaceutical, research, industrial, and precious metals laboratories in over 95 nations worldwide.' }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">Footer Copyright Notice</label>
                    <input type="text" name="footer_copyright" value="{{ $allSettings['footer_copyright'] ?? '© 2026 Aczet Metrology Pvt. Ltd. All Rights Reserved. ISO 9001:2015 & OIML Certified Metrology Systems.' }}" class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">ISO Certification Badge Text</label>
                    <input type="text" name="footer_iso_badge" value="{{ $allSettings['footer_iso_badge'] ?? 'ISO 9001:2015 & OIML R76 Certified' }}" class="form-control">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TAB 7: SOCIAL MEDIA PROFILES                                   -->
        <!-- ============================================================== -->
        <div id="tab-social" class="settings-tab-pane" style="display: none;">
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                <i class="fa-solid fa-share-nodes" style="color: var(--accent);"></i> Official Social Channels
            </h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">
                        <i class="fa-brands fa-linkedin" style="color: #0A66C2;"></i> LinkedIn URL
                    </label>
                    <input type="url" name="social_linkedin" value="{{ $allSettings['social_linkedin'] ?? 'https://www.linkedin.com/company/aczet' }}" class="form-control" placeholder="https://linkedin.com/company/aczet">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">
                        <i class="fa-brands fa-youtube" style="color: #FF0000;"></i> YouTube Channel URL
                    </label>
                    <input type="url" name="social_youtube" value="{{ $allSettings['social_youtube'] ?? 'https://www.youtube.com/@aczetmetrology' }}" class="form-control" placeholder="https://youtube.com/@aczet">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">
                        <i class="fa-brands fa-x-twitter"></i> Twitter / X
                    </label>
                    <input type="url" name="social_twitter" value="{{ $allSettings['social_twitter'] ?? 'https://twitter.com/aczet_metrology' }}" class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">
                        <i class="fa-brands fa-facebook" style="color: #1877F2;"></i> Facebook URL
                    </label>
                    <input type="url" name="social_facebook" value="{{ $allSettings['social_facebook'] ?? 'https://www.facebook.com/aczetmetrology' }}" class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px;">
                        <i class="fa-brands fa-instagram" style="color: #E1306C;"></i> Instagram URL
                    </label>
                    <input type="url" name="social_instagram" value="{{ $allSettings['social_instagram'] ?? 'https://www.instagram.com/aczetmetrology' }}" class="form-control">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TAB 8: ABOUT US PAGE CMS                                       -->
        <!-- ============================================================== -->
        <div id="tab-about" class="settings-tab-pane" style="display: none;">
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                <i class="fa-solid fa-address-card" style="color: var(--accent);"></i> About Us Page Content Management
            </h4>
            <p style="font-size: 0.84rem; color: #64748B; margin-bottom: 20px;">
                Control all headlines, descriptive narratives, the 4 key statistical counters, and the sidebar accreditation card shown on the <a href="{{ route('about.index') }}" target="_blank" style="color: var(--accent); font-weight: 700;">About Us page</a>.
            </p>

            <!-- Hero Section -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px; margin-bottom: 20px;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
                    <i class="fa-solid fa-heading"></i> About Page Hero Header
                </span>
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Hero Main Heading</label>
                    <input type="text" name="about_hero_heading" value="{{ $allSettings['about_hero_heading'] ?? 'About Aczet' }}" class="form-control">
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Hero Subheading / Tagline</label>
                    <textarea name="about_hero_subheading" rows="2" class="form-control">{{ $allSettings['about_hero_subheading'] ?? 'Decades of engineering excellence delivering certified precision weighing, analytical instruments, XRF metal analysis, and laser processing technology to global industries.' }}</textarea>
                </div>
            </div>

            <!-- Corporate Overview Block -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px; margin-bottom: 20px;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
                    <i class="fa-solid fa-building"></i> Corporate Overview Block
                </span>
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Eyebrow Tag</label>
                        <input type="text" name="about_overview_eyebrow" value="{{ $allSettings['about_overview_eyebrow'] ?? 'Corporate Overview' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Section Title</label>
                        <input type="text" name="about_overview_title" value="{{ $allSettings['about_overview_title'] ?? 'Precision Engineering. Global Trust.' }}" class="form-control">
                    </div>
                </div>
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Primary Narrative Paragraph</label>
                    <textarea name="about_overview_p1" rows="3" class="form-control">{{ $allSettings['about_overview_p1'] ?? 'Aczet is a premier manufacturer and global supplier of high-precision weighing balances, laboratory instruments, XRF gold testing and coating thickness analyzers, and precision laser systems. Combining German metrological concepts with world-class production capabilities, Aczet equips pharmaceutical laboratories, jewellery manufacturing clusters, universities, and heavy industrial plants with dependable accuracy.' }}</textarea>
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Secondary Narrative Paragraph</label>
                    <textarea name="about_overview_p2" rows="3" class="form-control">{{ $allSettings['about_overview_p2'] ?? 'Our instruments are deployed in over 65 countries, supported by an established international distributor network, dedicated application engineering centers, and ISO/IEC 17025 accredited calibration labs. Whether measuring a fraction of a microgram in formulation research or screening alloy composition in precious metal refineries, Aczet delivers confidence in every measurement.' }}</textarea>
                </div>
            </div>

            <!-- The 4 Statistical Metrics -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px; margin-bottom: 20px;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 14px;">
                    <i class="fa-solid fa-chart-simple"></i> The 4 Corporate Key Statistics
                </span>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <!-- Stat 1 -->
                    <div style="background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 8px; padding: 14px;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: #0EA5E9; text-transform: uppercase;">Stat 1</span>
                        <div style="margin: 8px 0;">
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Value</label>
                            <input type="text" name="about_stat_1_val" value="{{ $allSettings['about_stat_1_val'] ?? '65+' }}" class="form-control">
                        </div>
                        <div style="margin-bottom: 8px;">
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Label</label>
                            <input type="text" name="about_stat_1_label" value="{{ $allSettings['about_stat_1_label'] ?? 'Countries Served' }}" class="form-control">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Subtitle</label>
                            <input type="text" name="about_stat_1_sub" value="{{ $allSettings['about_stat_1_sub'] ?? 'Across 5 continents' }}" class="form-control">
                        </div>
                    </div>

                    <!-- Stat 2 -->
                    <div style="background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 8px; padding: 14px;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: #0284C7; text-transform: uppercase;">Stat 2</span>
                        <div style="margin: 8px 0;">
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Value</label>
                            <input type="text" name="about_stat_2_val" value="{{ $allSettings['about_stat_2_val'] ?? '500+' }}" class="form-control">
                        </div>
                        <div style="margin-bottom: 8px;">
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Label</label>
                            <input type="text" name="about_stat_2_label" value="{{ $allSettings['about_stat_2_label'] ?? 'Certified Models' }}" class="form-control">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Subtitle</label>
                            <input type="text" name="about_stat_2_sub" value="{{ $allSettings['about_stat_2_sub'] ?? 'Lab & industrial catalog' }}" class="form-control">
                        </div>
                    </div>

                    <!-- Stat 3 -->
                    <div style="background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 8px; padding: 14px;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: #10B981; text-transform: uppercase;">Stat 3</span>
                        <div style="margin: 8px 0;">
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Value</label>
                            <input type="text" name="about_stat_3_val" value="{{ $allSettings['about_stat_3_val'] ?? '17025' }}" class="form-control">
                        </div>
                        <div style="margin-bottom: 8px;">
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Label</label>
                            <input type="text" name="about_stat_3_label" value="{{ $allSettings['about_stat_3_label'] ?? 'ISO Accredited' }}" class="form-control">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Subtitle</label>
                            <input type="text" name="about_stat_3_sub" value="{{ $allSettings['about_stat_3_sub'] ?? 'Traceable calibration labs' }}" class="form-control">
                        </div>
                    </div>

                    <!-- Stat 4 -->
                    <div style="background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 8px; padding: 14px;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: #8B5CF6; text-transform: uppercase;">Stat 4</span>
                        <div style="margin: 8px 0;">
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Value</label>
                            <input type="text" name="about_stat_4_val" value="{{ $allSettings['about_stat_4_val'] ?? '25+' }}" class="form-control">
                        </div>
                        <div style="margin-bottom: 8px;">
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Label</label>
                            <input type="text" name="about_stat_4_label" value="{{ $allSettings['about_stat_4_label'] ?? 'Years Innovation' }}" class="form-control">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; margin-bottom: 2px;">Subtitle</label>
                            <input type="text" name="about_stat_4_sub" value="{{ $allSettings['about_stat_4_sub'] ?? 'Patented metrology' }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Trust Card -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px; margin-bottom: 10px;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
                    <i class="fa-solid fa-certificate"></i> Sidebar ISO 17025 Trust Card
                </span>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 12px;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Card Title</label>
                        <input type="text" name="about_trust_card_title" value="{{ $allSettings['about_trust_card_title'] ?? 'ISO 17025 Calibrated' }}" class="form-control">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Action Button Text</label>
                        <input type="text" name="about_trust_card_btn" value="{{ $allSettings['about_trust_card_btn'] ?? 'View Accreditations →' }}" class="form-control">
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Card Description</label>
                    <textarea name="about_trust_card_desc" rows="2" class="form-control">{{ $allSettings['about_trust_card_desc'] ?? 'All Aczet weighing and metrology systems ship with factory calibration traceable to national & international standards.' }}</textarea>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SECTION B: OUR TECHNOLOGY CONTENT & PHOTOS CMS                  -->
            <!-- ============================================================== -->
            <div style="border-top: 2px solid #E2E8F0; padding-top: 28px; margin-top: 32px;">
                <h4 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
                    <i class="fa-solid fa-microchip" style="color: var(--accent);"></i> Our Technology Page Content & Product Photos
                </h4>
                <p style="font-size: 0.84rem; color: #64748B; margin-bottom: 22px;">
                    Upload custom photography and manage technical details for all 4 technology pillars displayed on the 
                    <a href="{{ route('about.technology') }}" target="_blank" style="color: var(--accent); font-weight: 700;">
                        Our Technology page <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.75rem;"></i>
                    </a>.
                </p>

                <!-- Philosophy Introduction -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px; margin-bottom: 24px;">
                    <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--accent); letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
                        <i class="fa-solid fa-lightbulb"></i> Philosophy & Intro Block
                    </span>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px; margin-bottom: 14px;">
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Eyebrow Tag</label>
                            <input type="text" name="tech_intro_eyebrow" value="{{ $allSettings['tech_intro_eyebrow'] ?? 'Engineering Philosophy' }}" class="form-control">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Philosophy Title</label>
                            <input type="text" name="tech_intro_title" value="{{ $allSettings['tech_intro_title'] ?? 'Where Engineering Meets Application' }}" class="form-control">
                        </div>
                    </div>
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Primary Paragraph</label>
                        <textarea name="tech_intro_p1" rows="2" class="form-control">{{ $allSettings['tech_intro_p1'] ?? 'We combine precision engineering, advanced electronics, and intelligent software to develop high-performance solutions serving laboratory, industrial, and jewellery applications.' }}</textarea>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 4px;">Secondary Paragraph</label>
                        <textarea name="tech_intro_p2" rows="3" class="form-control">{{ $allSettings['tech_intro_p2'] ?? 'Our technology portfolio brings together specialised solutions designed to address different measurement, analysis, and production requirements. From sub-microgram mass determination in sterile cleanrooms to high-energy non-destructive gold purity screening, each Aczet instrument is conceived to minimize measurement uncertainty and maximize workflow productivity.' }}</textarea>
                    </div>
                </div>

                <!-- 4 Technology Cards Grid -->
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    @php
                        $techCardsData = [
                            1 => ['color' => '#0EA5E9', 'default_title' => 'Precision Weighing Technology', 'default_sub' => 'Built around accuracy.', 'default_img' => '/images/products/micro-balance-real.jpg'],
                            2 => ['color' => '#0284C7', 'default_title' => 'XRF Metal Analysis Technology', 'default_sub' => 'Fast analysis. Reliable insight.', 'default_img' => '/images/products/gold-compact-eco.jpg'],
                            3 => ['color' => '#10B981', 'default_title' => 'Laser Technology Solutions', 'default_sub' => 'Precision beyond measurement.', 'default_img' => '/images/products/handheld-xrf.jpg'],
                            4 => ['color' => '#8B5CF6', 'default_title' => 'Moisture Analysis Technology', 'default_sub' => 'Measure what cannot always be seen.', 'default_img' => '/images/products/moisture-analyzer-real.jpg'],
                        ];
                    @endphp

                    @foreach($techCardsData as $cIdx => $meta)
                        @php
                            $curImg = $allSettings["tech_card_{$cIdx}_image"] ?? $meta['default_img'];
                        @endphp
                        <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 20px; border-left: 4px solid {{ $meta['color'] }};">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                                <span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: {{ $meta['color'] }}; letter-spacing: 0.05em;">
                                    <i class="fa-solid fa-cube"></i> Technology Pillar 0{{ $cIdx }}
                                </span>
                                <input type="text" name="tech_card_{{ $cIdx }}_badge" value="{{ $allSettings["tech_card_{$cIdx}_badge"] ?? 'Technology 0' . $cIdx }}" class="form-control" style="width: 160px; font-size: 0.78rem; font-weight: 700;">
                            </div>

                            <!-- Photo Management Box (Live Preview + File Upload + Path) -->
                            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 16px; background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 10px; padding: 16px;">
                                <div style="flex-shrink: 0; text-align: center;">
                                    <img src="{{ vasset($curImg) }}" 
                                         alt="Preview Card {{ $cIdx }}" 
                                         style="width: 130px; height: 95px; object-fit: contain; background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0; padding: 4px; display: block; margin-bottom: 6px;"
                                         onerror="this.onerror=null; this.src='{{ asset('images/products/noimage.jpeg') }}';">
                                    <span style="font-size: 0.72rem; color: #64748B; font-weight: 600;">Current Photo</span>
                                </div>
                                <div style="flex: 1;">
                                    <div style="margin-bottom: 10px;">
                                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0F172A; margin-bottom: 4px;">
                                            <i class="fa-solid fa-cloud-arrow-up" style="color: var(--accent);"></i> Upload New Photo
                                        </label>
                                        <input type="file" name="tech_card_{{ $cIdx }}_image_file" accept="image/*" class="form-control" style="font-size: 0.82rem; padding: 6px 10px;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-size: 0.76rem; font-weight: 600; color: #64748B; margin-bottom: 3px;">Or Static Photo Path / URL</label>
                                        <input type="text" name="tech_card_{{ $cIdx }}_image" value="{{ $curImg }}" class="form-control" placeholder="/images/products/..." style="font-size: 0.82rem;">
                                    </div>
                                </div>
                            </div>

                            <!-- Title and Subtitle -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; margin-bottom: 3px;">Card Title</label>
                                    <input type="text" name="tech_card_{{ $cIdx }}_title" value="{{ $allSettings["tech_card_{$cIdx}_title"] ?? $meta['default_title'] }}" class="form-control">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; margin-bottom: 3px;">Tagline / Subtitle</label>
                                    <input type="text" name="tech_card_{{ $cIdx }}_subtitle" value="{{ $allSettings["tech_card_{$cIdx}_subtitle"] ?? $meta['default_sub'] }}" class="form-control">
                                </div>
                            </div>

                            <!-- Description -->
                            <div style="margin-bottom: 14px;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; margin-bottom: 3px;">Detailed Description</label>
                                <textarea name="tech_card_{{ $cIdx }}_desc" rows="3" class="form-control">{{ $allSettings["tech_card_{$cIdx}_desc"] ?? '' }}</textarea>
                            </div>

                            <!-- Tech Specs Chips -->
                            <div style="margin-bottom: 14px;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; margin-bottom: 3px;">
                                    <i class="fa-solid fa-microchip" style="color: {{ $meta['color'] }};"></i> Technical Specs Chips (Comma-separated)
                                </label>
                                <input type="text" name="tech_card_{{ $cIdx }}_specs" value="{{ $allSettings["tech_card_{$cIdx}_specs"] ?? '' }}" class="form-control" placeholder="e.g. Monobloc EMFC, 0.0001g Readability, isoCAL">
                            </div>

                            <!-- CTA Link -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; margin-bottom: 3px;">Action Link Text</label>
                                    <input type="text" name="tech_card_{{ $cIdx }}_link_text" value="{{ $allSettings["tech_card_{$cIdx}_link_text"] ?? 'Explore Catalog →' }}" class="form-control">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; margin-bottom: 3px;">Target URL / Route</label>
                                    <input type="text" name="tech_card_{{ $cIdx }}_link_url" value="{{ $allSettings["tech_card_{$cIdx}_link_url"] ?? '/products' }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div style="display: flex; justify-content: flex-end; gap: 14px; border-top: 1.5px solid #E2E8F0; padding-top: 20px; margin-top: 24px;">
            <button type="submit" class="btn-primary" style="padding: 12px 28px; font-size: 0.95rem;">
                <i class="fa-solid fa-floppy-disk"></i> Save & Publish Site Content
            </button>
        </div>
    </form>
</div>

<style>
    .settings-tab-btn {
        padding: 10px 18px;
        background: transparent;
        border: none;
        border-bottom: 2.5px solid transparent;
        font-size: 0.88rem;
        font-weight: 700;
        color: #64748B;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .settings-tab-btn:hover {
        color: var(--primary);
    }
    .settings-tab-btn.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
    }
</style>

<script>
    function switchSettingsTab(tabId, btn) {
        document.querySelectorAll('.settings-tab-pane').forEach(function(pane) {
            pane.style.display = 'none';
        });
        document.querySelectorAll('.settings-tab-btn').forEach(function(b) {
            b.classList.remove('active');
        });

        var targetPane = document.getElementById('tab-' + tabId);
        if (targetPane) {
            targetPane.style.display = 'block';
        }
        btn.classList.add('active');
    }

    function previewUploadedImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var el = document.getElementById(previewId);
                if (el) {
                    el.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection

