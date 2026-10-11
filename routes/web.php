<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\QuoteCartController;
use App\Http\Controllers\DealerPortalController;
use App\Http\Controllers\CustomerAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Website Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');

// About Us Section
Route::prefix('about-us')->group(function () {
    Route::get('/', [FrontendController::class, 'aboutUs'])->name('about.index');
    Route::get('/technology', [FrontendController::class, 'aboutTechnology'])->name('about.technology');
    Route::get('/global-presence', [FrontendController::class, 'aboutGlobalPresence'])->name('about.global_presence');
    Route::get('/vision-mission', [FrontendController::class, 'aboutVisionMission'])->name('about.vision_mission');
    Route::get('/management', [FrontendController::class, 'aboutManagement'])->name('about.management');
    Route::get('/history', [FrontendController::class, 'aboutHistory'])->name('about.history');
    Route::get('/why-choose-aczet', [FrontendController::class, 'aboutWhyChooseAczet'])->name('about.why_choose_aczet');
});
Route::redirect('/about', '/about-us');
Route::get('/why-choose-aczet', [FrontendController::class, 'aboutWhyChooseAczet']);

// Industries Section
Route::prefix('industries')->group(function () {
    Route::get('/', [FrontendController::class, 'industries'])->name('industries.index');
    Route::get('/{slug}', [FrontendController::class, 'industryDetail'])->name('industries.show');
});

Route::get('/products', [FrontendController::class, 'products'])->name('products.index');
Route::get('/products/{slug}', [FrontendController::class, 'productDetail'])->name('products.show');
Route::post('/products/{id}/service-manual-access', [FrontendController::class, 'verifyServiceManualPassword'])->name('products.service_manual');
Route::post('/service-manual/verify', [FrontendController::class, 'verifyServiceManualPassword'])->name('service_manual.verify');
Route::get('/compare', [FrontendController::class, 'compare'])->name('compare');
Route::get('/compare/pdf', [FrontendController::class, 'comparePdf'])->name('compare.pdf');
Route::get('/gallery', [FrontendController::class, 'gallery'])->name('gallery');
Route::get('/technical-publications', [FrontendController::class, 'technicalPublications'])->name('technical_publications');
Route::get('/exhibitions', [FrontendController::class, 'exhibitions'])->name('exhibitions');
Route::get('/webinars', [FrontendController::class, 'webinars'])->name('webinars');
Route::get('/blogs', [FrontendController::class, 'blogs'])->name('blogs.index');
Route::get('/blog/{slug}', [FrontendController::class, 'blogDetail'])->name('blogs.show');
Route::get('/careers', [FrontendController::class, 'careers'])->name('careers');
Route::get('/dealer-portal', [FrontendController::class, 'dealerPortal'])->name('dealer_portal');
Route::post('/dealer-portal/register', [FrontendController::class, 'registerDealer'])->name('dealer.register');
Route::get('/service-registration', [FrontendController::class, 'serviceRegistration'])->name('service_registration');
Route::post('/service-registration/store', [FrontendController::class, 'storeServiceTicket'])->name('service.store');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy_policy');
Route::get('/terms-and-conditions', [FrontendController::class, 'termsAndConditions'])->name('terms_and_conditions');
Route::get('/faqs', [FrontendController::class, 'faqs'])->name('faqs.index');

// Quote Cart / Consolidated RFQ Routes
Route::get('/quote-cart', [QuoteCartController::class, 'index'])->name('quote_cart.index');
Route::post('/quote-cart/add', [QuoteCartController::class, 'add'])->name('quote_cart.add');
Route::post('/quote-cart/remove/{id}', [QuoteCartController::class, 'remove'])->name('quote_cart.remove');
Route::post('/quote-cart/submit', [QuoteCartController::class, 'submitConsolidatedQuote'])->name('quote_cart.submit');

// Lead Capture API / POST
Route::post('/leads/store', [LeadController::class, 'store'])->name('leads.store');

// Document Downloads
Route::get('/docs/{filename}', [FrontendController::class, 'downloadDocument'])->name('docs.download');

// SEO & AI routes
Route::get('/sitemap.xml', [FrontendController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [FrontendController::class, 'robots'])->name('robots');

// Customer Portal, Login & Sign Up Routes
Route::get('/customer/login', [CustomerAuthController::class, 'showAuth'])->name('customer.login');
Route::get('/login', [CustomerAuthController::class, 'showAuth'])->name('login');
Route::get('/register', [CustomerAuthController::class, 'showAuth'])->name('register');
Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login.submit');
Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register.submit');
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
Route::get('/customer/dashboard', [CustomerAuthController::class, 'dashboard'])->middleware('auth')->name('customer.dashboard');

// Authorized Dealer Extranet & Login Routes
Route::prefix('dealer')->group(function () {
    Route::get('/login', [DealerPortalController::class, 'showLogin'])->name('dealer.login');
    Route::post('/login', [DealerPortalController::class, 'login'])->name('dealer.login.submit');
    Route::post('/logout', [DealerPortalController::class, 'logout'])->name('dealer.logout');

    Route::middleware(['auth:dealer'])->group(function () {
        Route::get('/dashboard', [DealerPortalController::class, 'dashboard'])->name('dealer.dashboard');
        Route::post('/bulk-order', [DealerPortalController::class, 'submitBulkOrder'])->name('dealer.bulk_order');
    });
});

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.forgot_password');
    Route::post('/forgot-password', [AdminAuthController::class, 'sendResetLink'])->name('admin.forgot_password.submit');
    Route::get('/reset-password', [AdminAuthController::class, 'showResetPasswordForm'])->name('admin.reset_password');
    Route::post('/reset-password', [AdminAuthController::class, 'resetPassword'])->name('admin.reset_password.submit');
});

// Protected Admin CMS Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/leads', [AdminController::class, 'leads'])->name('admin.leads');
    Route::post('/leads/{id}/status', [AdminController::class, 'updateLeadStatus'])->name('admin.leads.update_status');
    Route::delete('/leads/{id}', [AdminController::class, 'deleteLead'])->name('admin.leads.delete');
    Route::get('/leads/export', [LeadController::class, 'exportCsv'])->name('admin.leads.export');

    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::post('/products/{id}/update', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::post('/products/{id}/toggle-region', [AdminController::class, 'toggleRegionVisibility'])->name('admin.products.toggle_region');
    
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories/store', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::post('/categories/{id}/update', [AdminController::class, 'updateCategory'])->name('admin.categories.update');

    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/profile/change-password', [AdminController::class, 'changePassword'])->name('admin.profile.change_password');

    Route::get('/publications', [AdminController::class, 'publications'])->name('admin.publications');
    Route::post('/publications/store', [AdminController::class, 'storePublication'])->name('admin.publications.store');
    Route::post('/publications/{id}/update', [AdminController::class, 'updatePublication'])->name('admin.publications.update');

    Route::get('/exhibitions', [AdminController::class, 'exhibitions'])->name('admin.exhibitions');
    Route::post('/exhibitions/store', [AdminController::class, 'storeExhibition'])->name('admin.exhibitions.store');
    Route::post('/exhibitions/{id}/update', [AdminController::class, 'updateExhibition'])->name('admin.exhibitions.update');

    Route::get('/banners', [AdminController::class, 'banners'])->name('admin.banners');
    Route::post('/banners/store', [AdminController::class, 'storeBanner'])->name('admin.banners.store');
    Route::post('/banners/{id}/update', [AdminController::class, 'updateBanner'])->name('admin.banners.update');
    Route::delete('/banners/{id}', [AdminController::class, 'deleteBanner'])->name('admin.banners.delete');

    // Gallery Management
    Route::get('/galleries', [AdminController::class, 'galleries'])->name('admin.galleries');
    Route::post('/galleries/store', [AdminController::class, 'storeGallery'])->name('admin.galleries.store');
    Route::post('/galleries/{id}/update', [AdminController::class, 'updateGallery'])->name('admin.galleries.update');
    Route::delete('/galleries/{id}', [AdminController::class, 'deleteGallery'])->name('admin.galleries.delete');

    // Blog & News Management
    Route::get('/blogs', [AdminController::class, 'blogs'])->name('admin.blogs');
    Route::post('/blogs/store', [AdminController::class, 'storeBlog'])->name('admin.blogs.store');
    Route::post('/blogs/{id}/update', [AdminController::class, 'updateBlog'])->name('admin.blogs.update');
    Route::delete('/blogs/{id}', [AdminController::class, 'deleteBlog'])->name('admin.blogs.delete');

    // Dealers Management
    Route::get('/dealers', [AdminController::class, 'dealers'])->name('admin.dealers');
    Route::post('/dealers/store', [AdminController::class, 'storeDealer'])->name('admin.dealers.store');
    Route::post('/dealers/{id}/update', [AdminController::class, 'updateDealer'])->name('admin.dealers.update');
    Route::post('/dealers/{id}/approve', [AdminController::class, 'approveDealer'])->name('admin.dealers.approve');
    Route::delete('/dealers/{id}', [AdminController::class, 'deleteDealer'])->name('admin.dealers.delete');

    // Product FAQs Management
    Route::get('/faqs', [AdminController::class, 'faqs'])->name('admin.faqs');
    Route::post('/faqs/store', [AdminController::class, 'storeFaq'])->name('admin.faqs.store');
    Route::post('/faqs/{id}/update', [AdminController::class, 'updateFaq'])->name('admin.faqs.update');
    Route::delete('/faqs/{id}', [AdminController::class, 'deleteFaq'])->name('admin.faqs.delete');
    Route::post('/faqs/{id}/toggle-status', [AdminController::class, 'toggleFaqStatus'])->name('admin.faqs.toggle_status');

    // Site Content & Global Settings CMS
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // System Users & Staff Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

// Document & Presentation Notes Download Route
Route::get('/docs/download/{filename}', function ($filename) {
    $cleanName = basename($filename);
    $path = public_path('docs/' . $cleanName);
    if (file_exists($path)) {
        return response()->download($path, $cleanName, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    abort(404, 'Document not found.');
})->name('docs.download');

