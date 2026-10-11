<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;

class MegaMenuDynamicTest extends TestCase
{
    public function test_mega_menu_renders_database_categories_with_clickable_parent_redirect_links(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Verify mega menu structure
        $response->assertSee('nav-mega-dropdown', false);
        $response->assertSee('mt-col-level1 mega-cat-sidebar', false);
        $response->assertSee('mt-col-level2', false);
        $response->assertSee('mt-col-level3', false);

        // Verify Column 1: Parent categories dynamically loaded with redirect links
        $response->assertSee('Laboratory Weighing');
        $response->assertSee('/products?category=laboratory-weighing');

        $response->assertSee('Industrial Weighing Solutions');
        $response->assertSee('/products?category=industrial-weighing-solutions');

        $response->assertSee('Jewelry Machines');
        $response->assertSee('/products?category=jewelry-machines');

        $response->assertSee('Industrial Machines');
        $response->assertSee('/products?category=industrial-machines');

        $response->assertSee('Laboratory Instruments');
        $response->assertSee('/products?category=laboratory-instruments');

        // Verify Column 2: Subcategories dynamically rendered
        $response->assertSee('Laboratory Balances');
        $response->assertSee('Analytical Balances');
        $response->assertSee('Moisture Analyzers');
        $response->assertSee('Industrial Scales');
        $response->assertSee('Jewelry &amp; Carat Balances', false);

        // Verify Column 3: Items panel dynamically rendered
        $response->assertSee('items-cat-', false);
        $response->assertSee('Explore All', false);
    }

    public function test_clicking_parent_category_redirect_url_renders_landing_page(): void
    {
        // 1. Laboratory Weighing Landing Page
        $labResponse = $this->get('/products?category=laboratory-weighing');
        if (strpos($labResponse->getContent(), 'Unlock Service Excellence') === false) {
            fwrite(STDERR, "\nDEBUG LAB RESPONSE:\n" . substr($labResponse->getContent(), 0, 1500) . "\n");
        }
        $labResponse->assertStatus(200);
        $labResponse->assertSee('Laboratory Weighing');
        $labResponse->assertSee('Unlock Service Excellence');
        $labResponse->assertSee('1800 22 8884');

        // 2. Industrial Weighing Solutions Landing Page
        $indResponse = $this->get('/products?category=industrial-weighing-solutions');
        $indResponse->assertStatus(200);
        $indResponse->assertSee('Industrial Weighing Solutions');
        $indResponse->assertSee('Unlock Service Excellence');

        // 3. Jewelry Machines Landing Page
        $jwlResponse = $this->get('/products?category=jewelry-machines');
        $jwlResponse->assertStatus(200);
        $jwlResponse->assertSee('Jewelry Machines');

        // 4. Industrial Machines Landing Page
        $machResponse = $this->get('/products?category=industrial-machines');
        $machResponse->assertStatus(200);
        $machResponse->assertSee('Industrial Machines');

        // 5. Laboratory Instruments Landing Page
        $instResponse = $this->get('/products?category=laboratory-instruments');
        $instResponse->assertStatus(200);
        $instResponse->assertSee('Laboratory Instruments');
    }

    public function test_mega_menu_close_button_and_scripts_are_rendered_properly(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Verify close button presence and event handling
        $response->assertSee('mt-mega-close-btn', false);
        $response->assertSee('onclick="closeMegaMenu(event)"', false);
        $response->assertSee('aria-label="Close menu"', false);
        $response->assertSee('function closeMegaMenu(e)', false);
        $response->assertSee('megaDropdown.classList.add(\'is-closed\')', false);
        $response->assertSee('megaItem.classList.add(\'menu-closed\')', false);

        // Verify external CSS contains .menu-closed and .is-closed overrides
        $cssContent = file_get_contents(public_path('css/app.css'));
        $this->assertStringContainsString('.nav-item.menu-closed', $cssContent);
        $this->assertStringContainsString('.nav-mega-dropdown.is-closed', $cssContent);
        $this->assertStringContainsString('display: none !important', $cssContent);
    }

    public function test_mega_menu_starts_in_neutral_state_without_preselected_subcategories_or_items(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Ensure no categories or subcategories are hardcoded active on load
        $response->assertDontSee('mt-cat-row active', false);
        $response->assertDontSee('mt-subcat-panel active', false);

        // Ensure controllers and progressive disclosure scripts are rendered
        $response->assertSee('window.resetMegaMenu = function()', false);
        $response->assertSee('window.switchLevel1 = function(panelId, el)', false);
        $response->assertSee('window.switchLevel2 = function(itemsId, el)', false);
        $response->assertSee('col1El.addEventListener(\'mouseleave\'', false);
        $response->assertSee('col2El.addEventListener(\'mouseleave\'', false);
        $response->assertSee('col3El.addEventListener(\'mouseleave\'', false);

        // Ensure CSS contains progressive active display rules
        $cssContent = file_get_contents(public_path('css/app.css'));
        $this->assertStringContainsString('.mt-col-level2.active', $cssContent);
        $this->assertStringContainsString('.mt-col-level3.active', $cssContent);
    }
}
