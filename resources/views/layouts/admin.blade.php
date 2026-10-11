<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Aczet CMS Admin Portal')</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon & Device Icons -->
    <link rel="icon" type="image/x-icon" href="{{ vasset(site_setting('site_favicon', 'favicon.ico')) }}">
    <link rel="shortcut icon" href="{{ vasset(site_setting('site_favicon', 'favicon.ico')) }}">
    <link rel="apple-touch-icon" href="{{ vasset(site_setting('site_favicon', 'favicon.png')) }}">

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Admin Stylesheet -->
    <style>
        :root {
            --primary: #0F172A;
            --primary-light: #1E293B;
            --accent: #0284C7;
            --accent-hover: #0369A1;
            --bg-light: #F8FAFC;
            --surface: #FFFFFF;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
            --sidebar-width: 270px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            width: 100%;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background-color: #0B132B;
            color: #94A3B8;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1050;
        }

        .admin-brand {
            padding: 24px 28px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .admin-menu {
            list-style: none;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
        }

        .admin-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .admin-menu a:hover, .admin-menu a.active {
            background: rgba(229, 86, 4, 0.15);
            color: #FFFFFF;
        }

        .admin-menu a.active {
            border-left: 3px solid var(--accent);
            color: #FF7A30;
            font-weight: 700;
        }

        /* Main Workspace */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            width: 100%;
        }

        .admin-topbar {
            background: #FFFFFF;
            padding: 16px 32px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .admin-content {
            padding: 32px;
            flex: 1;
            min-width: 0;
        }

        /* Card System */
        .card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            border: 1px solid var(--border-color);
            margin-bottom: 24px;
        }

        /* Buttons */
        .btn-primary {
            background: var(--accent);
            color: #FFFFFF;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.88rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(229, 86, 4, 0.25);
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .btn-success { background: #10B981; color: #FFF; }
        .btn-danger { background: #EF4444; color: #FFF; }
        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--border-color);
        }
        .btn-outline:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Tables */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 16px;
            min-width: 600px;
        }

        .table-custom th, .table-custom td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }

        .table-custom th {
            background-color: #F8FAFC;
            color: #64748B;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .table-custom tbody tr:hover td {
            background-color: #F8FAFC;
        }

        /* Badges */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
        }
        .badge-success { background: #D1FAE5; color: #065F46; }
        .badge-warning { background: #FEF3C7; color: #92400E; }
        .badge-danger { background: #FEE2E2; color: #991B1B; }

        /* Form Controls */
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            font-size: 0.9rem;
            font-family: inherit;
            color: #0F172A;
            background: #FFFFFF;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(229, 86, 4, 0.15);
        }

        /* Modern Enterprise Pagination */
        .custom-pagination-container,
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
        }

        .pagination-info {
            font-size: 0.88rem;
            color: #64748B;
            font-weight: 500;
        }

        .pagination-info strong {
            color: #0F172A;
            font-weight: 700;
        }

        .custom-pagination,
        ul.pagination,
        nav .pagination {
            display: inline-flex !important;
            list-style: none !important;
            list-style-type: none !important;
            padding: 0 !important;
            margin: 0 !important;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
        }

        .custom-pagination li,
        ul.pagination li,
        .custom-pagination .page-item,
        ul.pagination .page-item {
            display: inline-flex !important;
            list-style: none !important;
            list-style-type: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .custom-pagination .page-item .page-link,
        .custom-pagination li a,
        .custom-pagination li span,
        ul.pagination .page-item .page-link,
        ul.pagination li a,
        ul.pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border-radius: 8px;
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            color: #1E293B;
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none !important;
            transition: all 0.18s ease;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            user-select: none;
            box-sizing: border-box;
        }

        .custom-pagination .page-item .page-link:hover,
        .custom-pagination li a:hover,
        ul.pagination .page-item .page-link:hover,
        ul.pagination li a:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: #F0F9FF;
            transform: translateY(-1px);
        }

        .custom-pagination .page-item.active .page-link,
        .custom-pagination li.active span,
        .custom-pagination li.active a,
        ul.pagination .page-item.active .page-link,
        ul.pagination li.active span,
        ul.pagination li.active a {
            background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%) !important;
            border-color: #0369A1 !important;
            color: #FFFFFF !important;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
        }

        .custom-pagination .page-item.disabled .page-link,
        .custom-pagination li.disabled span,
        ul.pagination .page-item.disabled .page-link,
        ul.pagination li.disabled span {
            background: #F8FAFC !important;
            border-color: #E2E8F0 !important;
            color: #94A3B8 !important;
            cursor: not-allowed;
            pointer-events: none;
            opacity: 0.6;
        }

        .custom-pagination .page-item .page-link.dots,
        .custom-pagination li span.dots,
        ul.pagination .page-item .page-link.dots,
        ul.pagination li span.dots {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            color: #94A3B8;
            cursor: default;
        }

        /* Modals */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-backdrop.active {
            display: flex;
        }

        .modal-card {
            background: #FFFFFF;
            width: 100%;
            max-width: 860px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
            position: relative;
        }

        .sidebar-toggle-btn {
            display: none;
            background: #F1F5F9;
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            color: var(--primary);
            font-size: 1.1rem;
            margin-right: 12px;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.6);
            z-index: 1040;
        }

        .sidebar-backdrop.active {
            display: block;
        }

        /* Responsive Admin Rules */
        @media (max-width: 992px) {
            .sidebar-toggle-btn { display: inline-flex; align-items: center; justify-content: center; }
            .admin-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100%;
            }
            .admin-sidebar.active {
                left: 0;
            }
            .admin-topbar { padding: 14px 20px; }
            .admin-content { padding: 20px 16px; }
        }

        @media (max-width: 600px) {
            .modal-card { padding: 20px 16px; }
            .admin-topbar { flex-wrap: wrap; gap: 10px; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Mobile Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleAdminSidebar()"></div>

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand">
            <a href="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; text-decoration:none;" title="{{ site_setting('site_name', 'Aczet Metrology') }}">
                <img src="{{ vasset(site_setting('site_logo', 'images/logo.png')) }}" 
                     alt="{{ site_setting('site_name', 'Aczet') }}" 
                     style="max-height: 34px; max-width: 140px; object-fit: contain; filter: brightness(0) invert(1);"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                <span style="color:#FFF; display:none; font-family:'Space Grotesk',sans-serif; font-size:1.4rem; font-weight:800;">aczet<span style="color:var(--accent);">.</span></span>
            </a>
            <span style="font-size:0.75rem; background:rgba(255,255,255,0.1); padding:2px 8px; border-radius:4px; font-family:'Plus Jakarta Sans'; font-weight:700; color:#38BDF8;">CMS</span>
        </div>
        <ul class="admin-menu">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.leads') }}" class="{{ request()->routeIs('admin.leads') ? 'active' : '' }}"><i class="fa-solid fa-envelope-open-text"></i> Lead Inquiries</a></li>
            <li><a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}"><i class="fa-solid fa-boxes-stacked"></i> Products & Regions</a></li>
            <li><a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}"><i class="fa-solid fa-layer-group"></i> Category & Languages</a></li>
            <li><a href="{{ route('admin.faqs') }}" class="{{ request()->routeIs('admin.faqs*') ? 'active' : '' }}"><i class="fa-solid fa-circle-question"></i> Product FAQs</a></li>
            <li><a href="{{ route('admin.publications') }}" class="{{ request()->routeIs('admin.publications*') ? 'active' : '' }}"><i class="fa-solid fa-file-pdf"></i> Publications & Whitepapers</a></li>
            <li><a href="{{ route('admin.exhibitions') }}" class="{{ request()->routeIs('admin.exhibitions*') ? 'active' : '' }}"><i class="fa-solid fa-calendar-days"></i> Global Exhibitions</a></li>
            <li><a href="{{ route('admin.banners') }}" class="{{ request()->routeIs('admin.banners*') ? 'active' : '' }}"><i class="fa-solid fa-images"></i> Homepage Banners</a></li>
            <li><a href="{{ route('admin.galleries') }}" class="{{ request()->routeIs('admin.galleries*') ? 'active' : '' }}"><i class="fa-solid fa-photo-film"></i> Gallery Images</a></li>
            <li><a href="{{ route('admin.blogs') }}" class="{{ request()->routeIs('admin.blogs*') ? 'active' : '' }}"><i class="fa-solid fa-newspaper"></i> Blog & News</a></li>
            <li><a href="{{ route('admin.dealers') }}" class="{{ request()->routeIs('admin.dealers*') ? 'active' : '' }}"><i class="fa-solid fa-handshake"></i> Authorized Dealers</a></li>
            <li><a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="fa-solid fa-sliders"></i> Site Content & Settings</a></li>
            <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i class="fa-solid fa-users-gear"></i> System Users & Staff</a></li>
            <li><a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile*') ? 'active' : '' }}"><i class="fa-solid fa-user-shield"></i> Profile & Security</a></li>
            <li style="margin-top:20px; border-top:1px solid rgba(255,255,255,0.08); padding-top:16px;">
                <a href="{{ route('home') }}" target="_blank"><i class="fa-solid fa-up-right-from-square"></i> Live Website</a>
            </li>
        </ul>
    </aside>

    <!-- Main Section -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div style="display:flex; align-items:center;">
                <button class="sidebar-toggle-btn" onclick="toggleAdminSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 style="font-size:1.25rem; font-weight:800; color:#0F172A;">@yield('page_title', 'Admin Dashboard')</h2>
            </div>
            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <a href="{{ route('admin.profile') }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:8px; background:#F1F5F9; padding:6px 12px; border-radius:30px; transition:all 0.2s;">
                    @if(Auth::user() && Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="" style="width:24px; height:24px; border-radius:50%; object-fit:cover;">
                    @else
                        <i class="fa-solid fa-user-gear" style="color:var(--accent);"></i>
                    @endif
                    <span style="font-size:0.85rem; font-weight:700;">{{ Auth::user()->name ?? 'Administrator' }}</span>
                </a>

                <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-sm btn-danger" style="padding:7px 14px; border-radius:8px;">
                        <i class="fa-solid fa-power-off"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <div class="admin-content">
            @if(session('success'))
                <div style="background:#D1FAE5; color:#065F46; padding:14px 20px; border-radius:10px; margin-bottom:24px; font-weight:700; display:flex; align-items:center; gap:10px; border:1px solid #A7F3D0;">
                    <i class="fa-solid fa-circle-check fa-lg"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background:#FEE2E2; color:#991B1B; padding:14px 20px; border-radius:10px; margin-bottom:24px; font-weight:700; display:flex; align-items:center; gap:10px; border:1px solid #FCA5A5;">
                    <i class="fa-solid fa-triangle-exclamation fa-lg"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleAdminSidebar() {
            var sidebar = document.getElementById('adminSidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.toggle('active');
            if (backdrop) backdrop.classList.toggle('active');
        }

        // Global Enterprise Modal Helpers
        window.openModal = function(id) {
            var el = document.getElementById(id);
            if (el) {
                el.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeModal = function(id) {
            var el = document.getElementById(id);
            if (el) {
                el.classList.remove('active');
                document.body.style.overflow = '';
            }
        };

        // Click outside modal card to close
        document.addEventListener('click', function(e) {
            if (e.target.classList && e.target.classList.contains('modal-backdrop')) {
                e.target.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // ESC key to close any active modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-backdrop.active').forEach(function(m) {
                    m.classList.remove('active');
                });
                document.body.style.overflow = '';
            }
        });
    </script>
    <script src="{{ vasset('js/aczet-validator.js') }}"></script>
    @yield('scripts')
</body>
</html>
