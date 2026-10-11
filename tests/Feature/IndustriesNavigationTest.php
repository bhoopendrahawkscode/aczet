<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndustriesNavigationTest extends TestCase
{
    public function test_industries_index_page_loads_and_has_active_navbar(): void
    {
        $response = $this->get('/industries');
        $response->assertStatus(200);

        // Verify page content
        $response->assertSee('Industry Solutions &amp; Metrology Systems', false);
        $response->assertSee('Key Industries We Serve');
        $response->assertSee('Pharmaceutical &amp; Biotech', false);
        $response->assertSee('Chemical &amp; Petrochemical', false);
        $response->assertSee('Jewelry &amp; Precious Metals', false);
        $response->assertSee('Metals, Mining &amp; Raw Materials', false);

        // Verify active class highlighting: Industries MUST be active, Products must NOT be active
        $content = $response->getContent();
        
        // Products & Solutions nav-item should NOT be active
        $this->assertStringNotContainsString('nav-item nav-item-mega active', $content);

        // Industries nav-item MUST be active
        $this->assertMatchesRegularExpression('/<li class="nav-item active">\s*<a href="[^"]*\/industries"[^>]*>/i', $content);
    }

    public function test_industry_detail_page_loads_and_has_active_navbar(): void
    {
        $response = $this->get('/industries/pharmaceutical-biotech');
        $response->assertStatus(200);

        // Content verification
        $response->assertSee('Pharmaceutical &amp; Biotech', false);
        $response->assertSee('Key Operational &amp; Regulatory Challenges', false);
        $response->assertSee('Aczet Engineered Solutions', false);
        $response->assertSee('Standards &amp; Regulatory Compliance', false);

        $content = $response->getContent();

        // Products & Solutions nav-item should NOT be active
        $this->assertStringNotContainsString('nav-item nav-item-mega active', $content);

        // Industries nav-item MUST be active
        $this->assertMatchesRegularExpression('/<li class="nav-item active">\s*<a href="[^"]*\/industries"[^>]*>/i', $content);
    }

    public function test_products_page_keeps_products_active_and_industries_inactive(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Products & Solutions nav-item MUST be active
        $this->assertStringContainsString('nav-item nav-item-mega active', $content);

        // Industries nav-item should NOT be active on /products
        $this->assertDoesNotMatchRegularExpression('/<li class="nav-item active">\s*<a href="[^"]*\/industries"[^>]*>/i', $content);
    }

    public function test_invalid_industry_slug_returns_404(): void
    {
        $response = $this->get('/industries/non-existent-sector');
        $response->assertStatus(404);
    }
}
