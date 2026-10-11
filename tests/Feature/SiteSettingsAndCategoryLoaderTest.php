<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\SiteSetting;
use App\Models\Blog;
use App\Models\ProductRegionVisibility;
use Illuminate\Http\UploadedFile;

class SiteSettingsAndCategoryLoaderTest extends TestCase
{
    protected function authenticateAdmin(): User
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@aczet.com'],
            ['name' => 'Admin Tester', 'password' => bcrypt('password123')]
        );
        $this->actingAs($admin);
        return $admin;
    }

    public function test_admin_products_has_open_modal_and_edit_functions(): void
    {
        $this->authenticateAdmin();

        $response = $this->get('/admin/products');
        $response->assertStatus(200);

        // Check global and local openModal & closeModal functions exist
        $response->assertSee('function openModal', false);
        $response->assertSee('function closeModal', false);
        $response->assertSee('openEditProductModal', false);
        $response->assertSee('editProductModal', false);
        $response->assertSee('Quick Edit Product & Image', false);
    }

    public function test_homepage_renders_category_loader_after_10_categories(): void
    {
        // Ensure at least 15 categories exist so > 10 categories trigger loader
        for ($i = Category::count(); $i < 15; $i++) {
            Category::create([
                'name' => "Precision Category {$i}",
                'slug' => "precision-category-{$i}",
                'description' => "Precision metrology equipment category {$i}",
            ]);
        }

        $response = $this->get('/');
        $response->assertStatus(200);

        // Check category grid and automatic scroll loader elements
        $response->assertSee('categoryCardsGrid', false);
        $response->assertSee('cat-item-extra cat-item-hidden', false);
        $response->assertSee('categoryLoaderSection', false);
        $response->assertSee('categoryScrollSentinel', false);
        $response->assertSee('catAutoLoadingIndicator', false);
        $response->assertSee('IntersectionObserver', false);
        $response->assertSee('catProgressBar', false);
    }

    public function test_admin_settings_requires_authentication(): void
    {
        $response = $this->get('/admin/settings');
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_view_settings_page_with_all_tabs(): void
    {
        $this->authenticateAdmin();

        $response = $this->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('Website Content & Configuration CMS', false);
        $response->assertSee('General & Header', false);
        $response->assertSee('Contact & Helpdesk', false);
        $response->assertSee('Global Offices', false);
        $response->assertSee('Homepage Metrics', false);
        $response->assertSee('Feature Pillars', false);
        $response->assertSee('Footer & Legal', false);
        $response->assertSee('About Us Page CMS', false);
        $response->assertSee('Social Links', false);
    }

    public function test_admin_can_update_settings_and_changes_reflect_on_frontend(): void
    {
        $this->authenticateAdmin();

        $testPhone = '+91 99 8877 6655';
        $testAnnouncement = 'Custom ISO 17025 Certified Metrology Notice 2026';
        $testHq = 'Aczet Innovation Center, Suite 900, Metrology Park, Mumbai';

        $response = $this->post('/admin/settings/update', [
            'phone_primary' => $testPhone,
            'header_topbar_text' => $testAnnouncement,
            'address_hq' => $testHq,
            'footer_copyright' => '© 2026 Custom Aczet Copyright Testing Notice',
        ]);

        $response->assertSessionHas('success');

        // Check database
        $this->assertEquals($testPhone, SiteSetting::get('phone_primary'));
        $this->assertEquals($testAnnouncement, SiteSetting::get('header_topbar_text'));
        $this->assertEquals($testHq, SiteSetting::get('address_hq'));

        // Check frontend home page reflects the updated announcement and helpline
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($testAnnouncement);
        $homeResponse->assertSee($testPhone);
        $homeResponse->assertSee('Custom Aczet Copyright Testing Notice');

        // Check contact page reflects the updated address
        $contactResponse = $this->get('/contact');
        $contactResponse->assertStatus(200);
        $contactResponse->assertSee($testHq);
        $contactResponse->assertSee($testPhone);
    }

    public function test_admin_can_update_about_us_content_and_it_reflects_on_about_page(): void
    {
        $this->authenticateAdmin();

        $testTitle = 'Engineered for Science. Trusted by Global Labs.';
        $testStat1 = '88+';
        $testTrustCard = 'NABL Accredited Calibration Standard';

        $response = $this->post('/admin/settings/update', [
            'about_overview_title' => $testTitle,
            'about_stat_1_val' => $testStat1,
            'about_trust_card_title' => $testTrustCard,
        ]);

        $response->assertSessionHas('success');

        $this->assertEquals($testTitle, SiteSetting::get('about_overview_title'));
        $this->assertEquals($testStat1, SiteSetting::get('about_stat_1_val'));
        $this->assertEquals($testTrustCard, SiteSetting::get('about_trust_card_title'));

        // Check /about-us reflects the updated content
        $aboutResponse = $this->get('/about-us');
        $aboutResponse->assertStatus(200);
        $aboutResponse->assertSee($testTitle);
        $aboutResponse->assertSee($testStat1);
        $aboutResponse->assertSee($testTrustCard);
    }

    public function test_frontend_products_renders_styled_pagination_and_navigates_pages(): void
    {
        $category = Category::create([
            'name' => 'Lab Analytical Balances',
            'slug' => 'lab-analytical-balances',
            'description' => 'High precision analytical balances',
        ]);

        for ($i = 1; $i <= 15; $i++) {
            $prod = Product::create([
                'category_id' => $category->id,
                'name' => "Aczet Analytical Balance CY-{$i}",
                'model_number' => "CY-{$i}",
                'slug' => "aczet-analytical-balance-cy-{$i}",
                'short_description' => "Precision balance CY-{$i} with 0.1mg readability",
                'price' => 1200 + $i,
                'is_active' => true,
            ]);

            ProductRegionVisibility::create([
                'product_id' => $prod->id,
                'region' => 'India',
                'is_visible' => true,
            ]);
        }

        $totalProducts = Product::where('is_active', true)
            ->whereHas('regionVisibilities', function($q) {
                $q->where('region', 'India')->where('is_visible', true);
            })->count();

        // Page 1 should display first 12 items and custom pagination
        $response = $this->withSession(['user_region' => 'India'])->get('/products');
        $response->assertStatus(200);
        $response->assertSee('custom-pagination-container', false);
        $response->assertSee('custom-pagination', false);
        $response->assertSee("Showing <strong>1</strong> to <strong>12</strong> of <strong>{$totalProducts}</strong> results", false);
        $response->assertSee('page-link', false);
        $response->assertSee('Next &raquo;', false);

        // Verify global pagination stylesheet is loaded in header and contains styles
        $response->assertSee('css/app.css', false);
        $this->assertFileExists(public_path('css/app.css'));
        $appCss = file_get_contents(public_path('css/app.css'));
        $this->assertStringContainsString('.custom-pagination-container', $appCss);
        $this->assertStringContainsString('list-style: none !important', $appCss);

        // Page 2 should display second page and Prev button
        $page2Response = $this->withSession(['user_region' => 'India'])->get('/products?page=2');
        $page2Response->assertStatus(200);
        $page2Response->assertSee("Showing <strong>13</strong> to <strong>", false);
        $page2Response->assertSee("&laquo; Prev", false);
    }

    public function test_frontend_blogs_renders_styled_pagination(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Blog::create([
                'title' => "Metrology Innovation Article {$i}",
                'slug' => "metrology-innovation-article-{$i}",
                'type' => 'Blog',
                'summary' => "Testing summary for article {$i}",
                'content' => "Detailed testing content for article {$i}",
                'author' => 'Aczet Metrology Lab',
                'published_at' => now()->subDays($i),
                'is_published' => true,
            ]);
        }

        $totalBlogs = Blog::count();

        // 12 items with 9 per page should trigger pagination
        $response = $this->get('/blogs');
        $response->assertStatus(200);
        $response->assertSee('custom-pagination-container', false);
        $response->assertSee('custom-pagination', false);
        $response->assertSee("Showing <strong>1</strong> to <strong>9</strong> of <strong>{$totalBlogs}</strong> results", false);
    }

    public function test_admin_can_update_technology_content_and_upload_photos_and_it_reflects_on_frontend(): void
    {
        $this->authenticateAdmin();

        $testIntroTitle = 'Pioneering Analytical Accuracy & Photonics';
        $testCard1Title = 'Ultra-Micro & Analytical EMFC Balances';
        $fakeImage = UploadedFile::fake()->image('custom_tech_photo.jpg', 600, 400);

        $response = $this->post('/admin/settings/update', [
            'tech_intro_title' => $testIntroTitle,
            'tech_card_1_title' => $testCard1Title,
            'tech_card_1_image_file' => $fakeImage,
            'tech_card_1_specs' => 'Monobloc EMFC, 0.0001mg Ultra-Resolution, isoCAL Automated',
        ]);

        $response->assertSessionHas('success');

        $this->assertEquals($testIntroTitle, SiteSetting::get('tech_intro_title'));
        $this->assertEquals($testCard1Title, SiteSetting::get('tech_card_1_title'));
        
        $savedImgPath = SiteSetting::get('tech_card_1_image');
        $this->assertNotEmpty($savedImgPath);
        $this->assertStringContainsString('/uploads/cms/tech_card_1_image_', $savedImgPath);

        // Verify the physical file exists on disk
        $this->assertFileExists(public_path(ltrim($savedImgPath, '/')));

        // Check /about-us/technology renders updated title, specs, and uploaded photo
        $techResponse = $this->get('/about-us/technology');
        $techResponse->assertStatus(200);
        $techResponse->assertSee($testIntroTitle);
        $techResponse->assertSee($testCard1Title);
        $techResponse->assertSee('0.0001mg Ultra-Resolution');
        $techResponse->assertSee($savedImgPath);
    }

    public function test_admin_can_upload_and_manage_logo_and_favicon(): void
    {
        $this->authenticateAdmin();

        // 1. Initial view has Logo & Favicon management inputs and preview elements
        $settingsView = $this->get('/admin/settings');
        $settingsView->assertStatus(200);
        $settingsView->assertSee('Website Logo & Browser Favicon Management', false);
        $settingsView->assertSee('name="site_logo_file"', false);
        $settingsView->assertSee('name="site_favicon_file"', false);
        $settingsView->assertSee('name="site_logo"', false);
        $settingsView->assertSee('name="site_favicon"', false);
        $settingsView->assertSee('id="site_logo_preview"', false);
        $settingsView->assertSee('id="site_favicon_preview"', false);

        // 2. Default state: frontend renders favicon and logo
        $frontendView = $this->get('/');
        $frontendView->assertStatus(200);
        $frontendView->assertSee('rel="icon"', false);
        $frontendView->assertSee('favicon.ico', false);
        $frontendView->assertSee('images/logo.png', false);

        // 3. Upload new custom logo and favicon files
        $fakeLogo = UploadedFile::fake()->image('custom_aczet_logo.png', 400, 150);
        $fakeFavicon = UploadedFile::fake()->create('custom_favicon.ico', 32, 'image/x-icon');

        $response = $this->post('/admin/settings/update', [
            'site_name' => 'Aczet Precision Metrology',
            'site_logo_file' => $fakeLogo,
            'site_favicon_file' => $fakeFavicon,
        ]);

        $response->assertSessionHas('success');

        $savedLogo = SiteSetting::get('site_logo');
        $savedFavicon = SiteSetting::get('site_favicon');

        $this->assertNotEmpty($savedLogo);
        $this->assertStringContainsString('/uploads/cms/site_logo_', $savedLogo);
        $this->assertFileExists(public_path(ltrim($savedLogo, '/')));

        $this->assertNotEmpty($savedFavicon);
        $this->assertStringContainsString('/uploads/cms/site_favicon_', $savedFavicon);
        $this->assertFileExists(public_path(ltrim($savedFavicon, '/')));

        // 4. Verify frontend now renders the newly uploaded logo and favicon
        $updatedFrontend = $this->get('/');
        $updatedFrontend->assertStatus(200);
        $updatedFrontend->assertSee($savedLogo, false);
        $updatedFrontend->assertSee($savedFavicon, false);

        // 5. Verify admin panel renders the newly uploaded logo and favicon
        $adminView = $this->get('/admin/dashboard');
        $adminView->assertStatus(200);
        $adminView->assertSee($savedLogo, false);
        $adminView->assertSee($savedFavicon, false);

        // 6. Verify admin can also set custom static paths
        $updatePathResponse = $this->post('/admin/settings/update', [
            'site_logo' => '/images/logo.png',
            'site_favicon' => '/favicon.ico',
        ]);
        $updatePathResponse->assertSessionHas('success');

        $this->assertEquals('/images/logo.png', SiteSetting::get('site_logo'));
        $this->assertEquals('/favicon.ico', SiteSetting::get('site_favicon'));
    }
}

