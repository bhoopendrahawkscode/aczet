<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleAndRegionTest extends TestCase
{
    public function test_region_switch_updates_session_and_page(): void
    {
        $response = $this->get('/?region=Europe');

        $response->assertStatus(200);
        $response->assertSessionHas('aczet_region', 'Europe');
        $response->assertSee('Region: <strong>Europe</strong>', false);
    }

    public function test_language_switch_updates_session_and_locale(): void
    {
        $response = $this->get('/?lang=de');

        $response->assertStatus(200);
        $response->assertSessionHas('aczet_lang', 'de');
        // 'nav_home' in German is 'Startseite'
        $response->assertSee('Startseite');
        // 'Deutsch' selector text
        $response->assertSee('Deutsch');
    }

    public function test_modals_and_full_query_links_are_rendered(): void
    {
        $response = $this->get('/products?category=analytical-balances');

        $response->assertStatus(200);
        $response->assertSee('id="regionModal"', false);
        $response->assertSee('id="langModal"', false);
        // Ensure request()->fullUrlWithQuery preserves the query param
        $response->assertSee('category=analytical-balances&amp;region=Europe', false);
        $response->assertSee('category=analytical-balances&amp;lang=de', false);
    }
}

