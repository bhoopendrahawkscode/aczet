<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_homepage_renders_all_modern_sections_and_zero_collision_dock(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('hero-carousel-viewport', false);
        $response->assertSee('hero-slides-wrapper', false);
        $response->assertSee('hero-slider-bar', false);
        $response->assertSee('slide-tabs-list', false);
        $response->assertSee('Laboratory &amp; Micro', false);
        $response->assertSee('Industrial &amp; Platform', false);
        $response->assertSee('Engineering &amp; Assay', false);
        $response->assertSee('search-dock-wrapper', false);
        $response->assertSee('search-dock-card', false);
        $response->assertSee('dock-chips-bar', false);
        $response->assertSee('trust-strip-modern', false);
        $response->assertSee('section-bento', false);
        $response->assertSee('section-categories', false);
        $response->assertSee('section-instruments', false);
        $response->assertSee('section-roi-calc', false);
        $response->assertSee('section-metrics-dark', false);
        $response->assertSee('section-testimonials', false);
        $response->assertSee('section-ecosystem', false);
        $response->assertSee('section-faq-modern', false);
        $response->assertSee('section-closing-cta', false);
    }

    public function test_homepage_renders_mt_discovery_hub_and_quick_cards(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Discovery Hub & Search Bar
        $response->assertSee('mt-hub-section', false);
        $response->assertSee('mt-search-wrapper', false);
        $response->assertSee('mt-search-bar', false);
        $response->assertSee('mt-search-category-dropdown', false);
        $response->assertSee('All Categories');

        // Six Quick Action Cards
        $response->assertSee('mt-quick-cards-grid', false);
        $response->assertSee('Get Onsite Service');
        $response->assertSee('Track Your Order');
        $response->assertSee('Browse Events Near You');
        $response->assertSee('Increase Your Knowledge');
        $response->assertSee('Join the Team');
        $response->assertSee('Contact Us');
        $response->assertSee('ACZET', false);
        $response->assertDontSee('METTLER TOLEDO');
    }

    public function test_product_detail_page_renders_modern_metrology_showcase(): void
    {
        $response = $this->get('/products/aczet-high-speed-checkweigher');

        $response->assertStatus(200);
        $response->assertSee('CW-H');
        $response->assertSee('Aczet High Speed Checkweigher');
        $response->assertSee('p-stage-card', false);
        $response->assertSee('p-quick-specs-grid', false);
        $response->assertSee('p-tabs-nav-bar', false);
        $response->assertSee('tab-overview', false);
        $response->assertSee('tab-specs', false);
        $response->assertSee('tab-compliance', false);
        $response->assertSee('tab-downloads', false);
        $response->assertSee('tab-faqs', false);
        $response->assertSee('p-related-section', false);
        $response->assertSee('p-sticky-rfq-bar', false);
        $response->assertSee('imageLightboxModal', false);
        $response->assertSee('Weighing &amp; Sorting Performance', false);
    }
}
