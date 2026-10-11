# AGENT.md - Aczet Enterprise Platform Architecture & Agent Guide

## Overview
This repository contains the complete enterprise website and CMS for **Aczet** (Manufacturer of Precision Balances, Micro-Balances, Moisture Analyzers, Industrial Weighing Systems, and Technical Instruments).

The application is built using:
- **Framework**: Laravel 11.x
- **Templating**: Laravel Blade Templates (Modern, Responsive, SEO & AI-Friendly UI)
- **Database**: MySQL (`aczet_db` on 127.0.0.1:3306)
- **Styling**: Modern CSS design system with corporate industrial aesthetic, SVG iconography, glassmorphic navigation, sticky enquiry CTAs, and interactive comparison tools.

---

## Administrator Credentials
- **Admin Login URL**: `/admin/login`
- **Email**: `admin@aczet.com`
- **Password**: `password`
- **Forgot Password**: `/admin/forgot-password`

---

## Core Requirements & Features
Refer to [`Pre-Requisites-Aczet.pdf`](file:///d:/wamp64/www/aczet/Pre-Requisites-Aczet.pdf) for full business and technical requirements.

### Key Functional Modules
1. **Admin Authentication & CMS Security**:
   - Secure Admin Login, Session Management, Logout, and Forgot/Reset Password flows.
   - Protected routes under `/admin/*` via `auth` middleware.
2. **Multi-Language Support System**:
   - 12 Languages: English, Arabic (with RTL direction), Spanish, Chinese, Thai, French, German, Italian, Japanese, Russian, Portuguese, Korean.
   - Dynamic locale switcher, language translation dictionary, and `hreflang` meta tags.
3. **Country & Region-Wise Listing**:
   - 6 Regions: India, GCC, Europe, Asia Pacific, Thailand, USA.
   - IP Auto-Detection simulation & regional override selector.
   - Region-based product visibility rules (Products can be toggle-activated per region).
4. **Product Catalog & Technical Specs**:
   - Categories, Subcategories, Products, Detailed Specifications tables, Application Notes, User Manuals, Technical Datasheets, Downloadable Brochures.
   - AI-friendly Q&A / FAQ structured section on product pages with JSON-LD Schema.
5. **Product Comparison Module**:
   - Side-by-side comparison for up to 4 products.
   - Printable PDF/Export view and CSV/Excel download.
6. **Consolidated Quote Cart (RFQ)**:
   - Add multiple instruments to Quote Cart with quantities (`/quote-cart`).
   - 1-click consolidated RFQ submission.
7. **Inquiry & Lead Management System**:
   - Product quote modal, general inquiry form, service registration form, contact page.
   - Stored in MySQL `leads` table with product tags, region tags, source tags, timestamps.
   - Admin Lead Management CMS: Filtering, status management (New, Contacted, Closed), and CSV export.
8. **Dealer / Distributor Portal**:
   - Dealer registration request and admin approval workflow (`/dealer-portal`).
9. **Service & Warranty Registration**:
   - Online product registration and service ticket tracking (`/service-registration`).
10. **Media Gallery & Technical Publications**:
    - Manufacturing plant & testing lab gallery (`/gallery`).
    - Compliance whitepapers, USP 41 guides, and OIML standard papers (`/technical-publications`).
11. **Contact & Global Presence**:
    - Global Headquarters and branch hubs (India, UAE, Germany, USA, Thailand) with contact form (`/contact`).
12. **Conversion & Compliance**:
    - Sticky Floating Quote Drawer.
    - Floating WhatsApp Chat trigger (`https://wa.me/912228474800`).
    - Cookie Consent banner with local preference memory.

---

## Directory Architecture
```
d:/wamp64/www/aczet/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── FrontendController.php
│   │   │   ├── LeadController.php
│   │   │   ├── AdminController.php
│   │   │   ├── AdminAuthController.php
│   │   │   └── QuoteCartController.php
│   │   └── Middleware/
│   │       └── SetLocaleAndRegion.php
│   └── Models/
│       ├── Product.php
│       ├── Category.php
│       ├── ProductSpecification.php
│       ├── Lead.php
│       ├── Exhibition.php
│       ├── Blog.php
│       ├── Career.php
│       ├── Dealer.php
│       └── ServiceRegistration.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── public/
│   └── images/
│       └── products/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── admin.blade.php
│   │   ├── frontend/
│   │   │   ├── home.blade.php
│   │   │   ├── contact.blade.php
│   │   │   ├── gallery.blade.php
│   │   │   ├── technical_publications.blade.php
│   │   │   ├── quote_cart.blade.php
│   │   │   ├── products/
│   │   │   ├── compare.blade.php
│   │   │   ├── exhibitions.blade.php
│   │   │   ├── dealer_portal.blade.php
│   │   │   ├── service_registration.blade.php
│   │   │   ├── careers.blade.php
│   │   │   └── blogs/
│   │   └── admin/
│   │       ├── auth/
│   │       │   ├── login.blade.php
│   │       │   ├── forgot_password.blade.php
│   │       │   └── reset_password.blade.php
│   │       ├── dashboard.blade.php
│   │       ├── leads/
│   │       └── products/
└── routes/
    └── web.php
```

---

## Database Configuration & Setup
- Database Name: `aczet_db`
- Host: `127.0.0.1`
- Port: `3306`
- Username: `root`
- Password: `` (empty)

### Commands for Setup & Reset
```bash
php artisan migrate:fresh --seed
php artisan serve --port=8000
```
