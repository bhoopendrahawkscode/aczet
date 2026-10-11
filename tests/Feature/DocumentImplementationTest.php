<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class DocumentImplementationTest extends TestCase
{
    public function test_about_us_six_pages_and_navigation_render_successfully(): void
    {
        // 1. Overview
        $response = $this->get('/about-us');
        $response->assertStatus(200);
        $response->assertSee('Corporate Overview');
        $response->assertSee('Precision Engineering. Global Trust.');

        // 2. Technology
        $response = $this->get('/about-us/technology');
        $response->assertStatus(200);
        $response->assertSee('Precision Weighing Technology');
        $response->assertSee('XRF Metal Analysis Technology');

        // 3. Global Presence
        $response = $this->get('/about-us/global-presence');
        $response->assertStatus(200);
        $response->assertSee('Serving Customers Across 65+ Nations');
        $response->assertSee('Regional Operations');
        $response->assertSee('Active Global Network');

        // 4. Vision & Mission
        $response = $this->get('/about-us/vision-mission');
        $response->assertStatus(200);
        $response->assertSee('Our Vision');
        $response->assertSee('Our Mission');

        // 5. Management
        $response = $this->get('/about-us/management');
        $response->assertStatus(200);
        $response->assertSee('Guided by Excellence and Integrity');
        $response->assertSee('Executive Philosophy');

        // 6. History
        $response = $this->get('/about-us/history');
        $response->assertStatus(200);
        $response->assertSee('A Legacy of Measurement Innovation');
        $response->assertSee('Key Milestones in Aczet Journey');

        // 7. Why Choose Aczet
        $response = $this->get('/about-us/why-choose-aczet');
        $response->assertStatus(200);
        $response->assertSee('Why Choose');
        $response->assertSee('Precision Engineering');
        $response->assertSee('Research, Development');

        // Redirect check
        $this->get('/about')->assertRedirect('/about-us');
    }

    public function test_header_megamenu_and_about_dropdown_render_on_pages(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Top Navigation Items
        $response->assertSee('Products &amp; Solutions', false);
        $response->assertSee('Industries');
        $response->assertSee('Services &amp; Support', false);
        $response->assertSee('Blogs &amp; Events', false);
        $response->assertSee('Career');
        $response->assertSee('About Us');
        $response->assertSee('Contact Us');

        // Multi-level 3-column mega menu (mt.com exact layout)
        $response->assertSee('Laboratory Balances');
        $response->assertSee('nav-mega-dropdown', false);
        $response->assertSee('mega-cat-sidebar', false);
        $response->assertSee('mt-mega-inner', false);
        $response->assertSee('mt-col-level1', false);
        $response->assertSee('mt-col-level2', false);
        $response->assertSee('mt-col-level3', false);
        $response->assertSee('mt-mega-close-btn', false);
        $response->assertSee('mt-utility-btn', false);
        $response->assertSee('/about-us/technology');
        $response->assertSee('/about-us/global-presence');

        // Footer Copyright Notice
        $response->assertSee('ACZET PVT. LTD. All Rights Reserved');
    }

    public function test_products_listing_renders_laboratory_balance_filters_and_compare_dock(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);

        // 13 Laboratory Balances Filters
        $response->assertSee('Filter Catalog');
        $response->assertSee('Category');
        $response->assertSee('Series');
        $response->assertSee('Types');
        $response->assertSee('Weighing Capacity');
        $response->assertSee('Readability');
        $response->assertSee('Display');
        $response->assertSee('Class');
        $response->assertSee('Calibration');
        $response->assertSee('Weighing Sensor');
        $response->assertSee('Readability MC');
        $response->assertSee('Density Resolution');
        $response->assertSee('OIML Class');
        $response->assertSee('Nominal Value');

        // Compare Checkbox and Floating Dock
        $response->assertSee('card-compare-strip', false);
        $response->assertSee('Add to Compare');
        $response->assertSee('floating-compare-dock', false);
        $response->assertSee('Compare Products');
        $response->assertSee('categoryIncompatibleModal', false);

        // Laboratory Balances Hero Banner & Eyebrow & Headline & Description
        $response->assertSee('Laboratory Balances');
        $response->assertSee('High Precision, Reliable Performance, and Advanced Weighing Solutions');
        $response->assertSee('Request info');
        $response->assertSee('Get a Quote');
        $response->assertSee('Aczet laboratory balances are designed to deliver high accuracy');

        // Clickable Breadcrumbs
        $response->assertSee('catalog-breadcrumbs', false);
        $response->assertSee('Home');

        // Sequence Product #1 and Product #2
        $response->assertSee('Ultra-Microbalance AXR 2UA');
        $response->assertSee('Microbalance AXR 2MA');
        $response->assertSee('Minimum Weight (USP, 0.1%, typical)');
    }

    public function test_products_listing_filters_by_laboratory_balance_specs_and_per_page(): void
    {
        // 1. Filter by Sensor
        $response = $this->get('/products?sensor[]=Singleblock+EMFC');
        $response->assertStatus(200);
        $response->assertSee('Sensor: Singleblock EMFC');

        // 2. Filter by Readability MC
        $response = $this->get('/products?readability_mc[]=0.0001+%25+MC');
        $response->assertStatus(200);
        $response->assertSee('MC: 0.0001 % MC');

        // 3. Filter by OIML Class
        $response = $this->get('/products?oiml_class[]=E1');
        $response->assertStatus(200);
        $response->assertSee('OIML: E1');

        // 4. Per page parameter
        $response = $this->get('/products?per_page=24');
        $response->assertStatus(200);
        $response->assertSee('24 per page');
    }

    public function test_product_detail_renders_slider_service_manual_and_accessories(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(200);

        // 8-card technical slider
        $response->assertSee('p-tech-slider-section', false);
        $response->assertSee('techSliderTrack', false);
        $response->assertSee('Monotech Weighing Sensor');
        $response->assertSee('Automatic Internal Calibration');
        $response->assertSee('Touchscreen Display Interface');

        // Service manual password modal
        $response->assertSee('serviceManualModal', false);
        $response->assertSee('Authorized Technician Access');

        // Compatible accessories tab & APN
        $response->assertSee('tab-accessories', false);
        $response->assertSee('Compatible Accessories');

        // End-of-page CTA & Recently Viewed Tracker
        $response->assertSee('p-end-cta-section', false);
        $response->assertSee('Request Your Quote Today');
        $response->assertSee('recentlyViewedSection', false);
    }

    public function test_service_manual_password_verification_endpoint(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        // Incorrect password returns 403
        $failResponse = $this->postJson("/products/{$product->id}/service-manual-access", [
            'password' => 'WrongPassword123'
        ]);
        $failResponse->assertStatus(403);
        $failResponse->assertJson(['success' => false]);

        // Correct password returns 200
        $successResponse = $this->postJson("/products/{$product->id}/service-manual-access", [
            'password' => 'Aczet@Service2026'
        ]);
        $successResponse->assertStatus(200);
        $successResponse->assertJson(['success' => true]);
        $this->assertArrayHasKey('download_url', $successResponse->json());
    }

    public function test_comparison_category_validation_handles_mismatched_categories(): void
    {
        $cat1 = Category::firstOrCreate(['slug' => 'cat-one', 'name' => 'Category One']);
        $cat2 = Category::firstOrCreate(['slug' => 'cat-two', 'name' => 'Category Two']);

        $prod1 = Product::create([
            'category_id' => $cat1->id,
            'name' => 'Test Product One',
            'model_number' => 'TP-01',
            'slug' => 'test-product-one',
            'short_description' => 'Test 1',
            'is_active' => true,
        ]);

        $prod2 = Product::create([
            'category_id' => $cat2->id,
            'name' => 'Test Product Two',
            'model_number' => 'TP-02',
            'slug' => 'test-product-two',
            'short_description' => 'Test 2',
            'is_active' => true,
        ]);

        // Querying compare with differing category items triggers category mismatch flash message
        $response = $this->get("/compare?ids={$prod1->id},{$prod2->id}");
        $response->assertStatus(200);
        $response->assertSessionHas('category_error');
        $response->assertSee('The selected product belongs to a different category and cannot be added to this comparison.');
    }

    public function test_global_presence_renders_all_document_specified_offices_and_pins(): void
    {
        $response = $this->get('/about-us/global-presence');
        $response->assertStatus(200);

        // Document specific addresses
        $response->assertSee('E2, Plot 15, Wicel Rd');
        $response->assertSee('Survey no. 70, Hissa no. 4');
        $response->assertSee('BIS Building, 119 Mahesak Road');
        $response->assertSee('Old Goldsouq, Deira - Dubai');
        $response->assertSee('Piscataway, Edison, NJ 08854');
        $response->assertSee('Suzhou Industrial Park, Xinfa Road');

        // 7 Indian Hubs
        $response->assertSee('Mumbai');
        $response->assertSee('Delhi');
        $response->assertSee('Chennai');
        $response->assertSee('Uttar Pradesh');
        $response->assertSee('Madhya Pradesh');
        $response->assertSee('Kolkata');
        $response->assertSee('Gujarat');

        // SVG Pins & Statistics
        $response->assertSee('Interactive Global Metrology Map', false);
        $response->assertSee('65+');
        $response->assertSee('Countries Served');
    }

    public function test_frontend_layout_renders_light_blue_theme(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Light blue theme #0284C7 in global stylesheet
        $response->assertSee('css/app.css', false);
        $this->assertFileExists(public_path('css/app.css'));
        $appCss = file_get_contents(public_path('css/app.css'));
        $this->assertStringContainsString('--accent: #0284C7;', $appCss);
        $this->assertStringContainsString('--accent-hover: #0369A1;', $appCss);

        // Key Metrology & Equipment Categories section removed per requirement
        $response->assertDontSee('Key Metrology & Equipment Categories');
        $response->assertSee('Laboratory Balances');
    }

    public function test_comparison_renders_print_pdf_export_and_limits(): void
    {
        $response = $this->get('/compare');
        $response->assertStatus(200);
        $response->assertSee('Equipment Comparison Matrix');
        $response->assertSee('Print / PDF Export');
        $response->assertSee('Excel CSV');
        $response->assertSee('Compare up to 10 products');
    }
}


