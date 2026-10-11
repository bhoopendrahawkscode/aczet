<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRegionVisibility;

class CategoryFilterAjaxTest extends TestCase
{
    public function test_category_filter_renders_vertical_options_with_chevron_and_badge(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);

        // Sidebar Category accordion
        $response->assertSee('filterGroupCategory', false);
        $response->assertSee('catCountBadge', false);
        $response->assertSee('category-filter-checkbox', false);

        // All 8 Document Category options present
        $response->assertSee('Ultra-Microbalances');
        $response->assertSee('Microbalances');
        $response->assertSee('Analytical Balances');
        $response->assertSee('Precision Balances and Scales');
        $response->assertSee('Moisture Analyzers');
        $response->assertSee('Density Scales');
        $response->assertSee('Test Weights');
        $response->assertSee('Accessories for Laboratory weighing');

        // Client-side AJAX script present (No page reload)
        $response->assertSee('applyFiltersAjax', false);
        $response->assertSee('handleCategoryFilterChange', false);
        $response->assertSee('removeCategoryFilterAjax', false);
        $response->assertSee('clearAllCategoryFiltersAjax', false);
    }

    public function test_ajax_filter_microbalances_returns_json_with_results_and_active_chip(): void
    {
        $response = $this->withSession(['user_region' => 'India'])->get('/products?category[]=laboratory-balances-microbalances&ajax=1', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $json = $response->json();

        $this->assertTrue($json['success']);
        $this->assertEquals(1, $json['selected_category_count']);
        $this->assertEquals(['laboratory-balances-microbalances'], $json['selected_category_slugs']);
        $this->assertGreaterThan(0, $json['total']);

        // Cards HTML contains Microbalance AXR 2MA
        $this->assertStringContainsString('Microbalance AXR 2MA', $json['cards_html']);

        // Active Chips HTML contains Category: Microbalances and Clear All
        $this->assertStringContainsString('Category: Microbalances', $json['chips_html']);
        $this->assertStringContainsString('Clear All', $json['chips_html']);
    }

    public function test_ajax_filter_multiple_categories_returns_combined_count(): void
    {
        $response = $this->withSession(['user_region' => 'India'])->get('/products?category[]=laboratory-balances-microbalances&category[]=laboratory-balances-analytical-balances&ajax=1', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $json = $response->json();

        $this->assertTrue($json['success']);
        $this->assertEquals(2, $json['selected_category_count']);
        $this->assertGreaterThan(0, $json['total']);
        $this->assertStringContainsString('Category: Microbalances', $json['chips_html']);
        $this->assertStringContainsString('Category: Analytical Balances', $json['chips_html']);
        $this->assertStringContainsString('Clear All', $json['chips_html']);
    }

    public function test_ajax_filter_ultra_microbalances_returns_axr_2ua(): void
    {
        $response = $this->withSession(['user_region' => 'India'])->get('/products?category[]=laboratory-balances-ultra-microbalances&ajax=1', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $json = $response->json();

        $this->assertTrue($json['success']);
        $this->assertEquals(1, $json['selected_category_count']);
        $this->assertEquals(1, $json['total']);
        $this->assertStringContainsString('Ultra-Microbalance AXR 2UA', $json['cards_html']);
        $this->assertStringContainsString('Clear All', $json['chips_html']);
    }

    public function test_microbalances_filter_updates_series_types_and_capacity_facets(): void
    {
        $response = $this->withSession(['user_region' => 'India'])->get('/products?category[]=laboratory-balances-microbalances&ajax=1', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $json = $response->json();

        $this->assertTrue($json['success']);
        $this->assertArrayHasKey('sidebar_facets_html', $json);
        $facets = $json['sidebar_facets_html'];

        // 1. Series: AXR (9), CM (9) active; ALT (0), CY (0), etc. disabled
        $this->assertStringContainsString('AXR (9)', $facets);
        $this->assertStringContainsString('CM (9)', $facets);
        $this->assertStringContainsString('ALT (0)', $facets);
        $this->assertStringContainsString('CY (0)', $facets);
        $this->assertStringContainsString('CZ (0)', $facets);

        // 2. Types: 4 active types with counts, and locked inactive types
        $this->assertStringContainsString('Microbalance (7)', $facets);
        $this->assertStringContainsString('Filter Microbalance (7)', $facets);
        $this->assertStringContainsString('Pipettes Microbalance (2)', $facets);
        $this->assertStringContainsString('Stent Microbalance (1)', $facets);
        $this->assertStringContainsString('Analytical balance', $facets);
        $this->assertStringContainsString('blurred-locked', $facets);

        // 3. Weighing Capacity: 2g (5), 5g (3), 6g(5), 11g (5), disabled 50g, extra items (60g, 60kg), and Show more...
        $this->assertStringContainsString('2g (5)', $facets);
        $this->assertStringContainsString('5g (3)', $facets);
        $this->assertStringContainsString('6g(5)', $facets);
        $this->assertStringContainsString('11g (5)', $facets);
        $this->assertStringContainsString('50g', $facets);
        $this->assertStringContainsString('60g', $facets);
        $this->assertStringContainsString('60kg', $facets);
        $this->assertStringContainsString('Show more...', $facets);

        // 4. Readability: 1ug (1) active, disabled items (0.1ug, 10ug, 0.01mg, etc.)
        $this->assertStringContainsString('1ug (1)', $facets);
        $this->assertStringContainsString('0.1ug', $facets);
        $this->assertStringContainsString('10ug', $facets);
        $this->assertStringContainsString('0.01mg', $facets);

        // 5. Display: Colour TFT Touchscreen (9), Bright LED backlit LCD Display (9), disabled LED
        $this->assertStringContainsString('Colour TFT Touchscreen (9)', $facets);
        $this->assertStringContainsString('Bright LED backlit LCD Display (9)', $facets);
        $this->assertStringContainsString('LED', $facets);

        // 6. Class: Premium (9), Elite (9), disabled Standard, Basic
        $this->assertStringContainsString('Premium (9)', $facets);
        $this->assertStringContainsString('Elite (9)', $facets);
        $this->assertStringContainsString('Standard', $facets);
        $this->assertStringContainsString('Basic', $facets);
    }

    public function test_filter_by_series_and_type_via_ajax(): void
    {
        // Filter by Series AXR
        $response = $this->withSession(['user_region' => 'India'])->get('/products?category[]=laboratory-balances-microbalances&series[]=AXR&ajax=1', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertTrue($json['success']);
        $this->assertStringContainsString('AXR', $json['chips_html']);
        $this->assertStringContainsString('Microbalance AXR 2MA', $json['cards_html']);

        // Filter by Type Filter Microbalance
        $responseType = $this->withSession(['user_region' => 'India'])->get('/products?category[]=laboratory-balances-microbalances&type[]=Filter+Microbalance&ajax=1', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $responseType->assertStatus(200);
        $jsonType = $responseType->json();
        $this->assertTrue($jsonType['success']);
        $this->assertStringContainsString('Type: Filter Microbalance', $jsonType['chips_html']);
        $this->assertStringContainsString('AXR 2MF', $jsonType['cards_html']);
    }

    public function test_filter_by_readability_display_and_class_via_ajax(): void
    {
        // Filter by Readability 1ug
        $respRead = $this->withSession(['user_region' => 'India'])->get('/products?category[]=laboratory-balances-microbalances&readability[]=1ug&ajax=1', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $respRead->assertStatus(200);
        $jsonRead = $respRead->json();
        $this->assertTrue($jsonRead['success']);
        $this->assertStringContainsString('Readability: 1ug', $jsonRead['chips_html']);
        $this->assertStringContainsString('Microbalance AXR 2MA', $jsonRead['cards_html']);

        // Filter by Display Colour TFT Touchscreen
        $respDisp = $this->withSession(['user_region' => 'India'])->get('/products?category[]=laboratory-balances-microbalances&display[]=Colour+TFT+Touchscreen&ajax=1', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $respDisp->assertStatus(200);
        $jsonDisp = $respDisp->json();
        $this->assertTrue($jsonDisp['success']);
        $this->assertStringContainsString('Display: Colour TFT Touchscreen', $jsonDisp['chips_html']);
        $this->assertStringContainsString('Microbalance AXR 2MA', $jsonDisp['cards_html']);

        // Filter by Class Premium
        $respClass = $this->withSession(['user_region' => 'India'])->get('/products?category[]=laboratory-balances-microbalances&class[]=Premium&ajax=1', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $respClass->assertStatus(200);
        $jsonClass = $respClass->json();
        $this->assertTrue($jsonClass['success']);
        $this->assertStringContainsString('Class: Premium', $jsonClass['chips_html']);
        $this->assertStringContainsString('Microbalance AXR 2MA', $jsonClass['cards_html']);
    }

    public function test_default_category_filter_is_open_and_subsequent_facets_are_collapsed(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);

        // Category filter is open by default (does not have collapsed class)
        $response->assertSee('<div class="filter-group" id="filterGroupCategory">', false);

        // Secondary facet groups are collapsed by default
        $response->assertSee('class="filter-group collapsed" id="facetGroupSeries"', false);
        $response->assertSee('class="filter-group collapsed" id="facetGroupTypes"', false);
        $response->assertSee('class="filter-group collapsed" id="facetGroupCapacity"', false);
        $response->assertSee('class="filter-group collapsed" id="facetGroupReadability"', false);
        $response->assertSee('class="filter-group collapsed" id="facetGroupDisplay"', false);
        $response->assertSee('class="filter-group collapsed" id="facetGroupClass"', false);
    }
}
