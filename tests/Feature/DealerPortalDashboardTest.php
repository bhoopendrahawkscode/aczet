<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Dealer;
use App\Models\Product;
use App\Models\Lead;

class DealerPortalDashboardTest extends TestCase
{
    protected function authenticateDealer(): Dealer
    {
        $dealer = Dealer::firstOrCreate(
            ['email' => 'dealer.test@aczet.com'],
            [
                'company_name' => 'Apex Metrology Solutions Ltd',
                'contact_person' => 'Marcus Vance',
                'phone' => '+44 20 7946 0912',
                'country' => 'United Kingdom',
                'address' => '71 High Street, London',
                'status' => 'Approved',
                'tier' => 'Tier 1 Gold Partner',
                'discount_percentage' => 35.00,
                'password' => bcrypt('password123'),
            ]
        );

        $this->actingAs($dealer, 'dealer');
        return $dealer;
    }

    public function test_guest_is_redirected_from_dealer_dashboard(): void
    {
        $response = $this->get('/dealer/dashboard');
        $response->assertRedirect('/dealer/login');
    }

    public function test_dealer_dashboard_renders_search_toolbar_and_pagination(): void
    {
        $dealer = $this->authenticateDealer();

        $response = $this->get('/dealer/dashboard');
        $response->assertStatus(200);

        // Check header and tier details
        $response->assertSee('Wholesale Price Matrix &amp; Profit Calculator', false);
        $response->assertSee('Tier Discount: 35% Off MSRP', false);

        // Check search toolbar & per page controls
        $response->assertSee('name="q"', false);
        $response->assertSee('name="per_page"', false);
        $response->assertSee('name="sort"', false);
        $response->assertSee('10 / page', false);
        $response->assertSee('25 / page', false);

        // Check pagination container is rendered
        $response->assertSee('dealer-pagination', false);
        $response->assertSee('custom-pagination-container', false);
        $response->assertSee('Showing', false);

        // Check Quick PO form contains full product catalog and live estimation card
        $response->assertSee('id="po_product_select"', false);
        $response->assertSee('id="live_po_summary"', false);
        $response->assertSee('id="po_total_rate"', false);
    }

    public function test_dealer_dashboard_supports_query_search_and_pagination_navigation(): void
    {
        $dealer = $this->authenticateDealer();

        // Test search query
        $product = Product::where('is_active', true)->first();
        $query = substr($product->model_number, 0, 3);

        $searchResponse = $this->get('/dealer/dashboard?q=' . urlencode($query));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee($query);

        // Test per_page parameter
        $perPageResponse = $this->get('/dealer/dashboard?per_page=25');
        $perPageResponse->assertStatus(200);
        $perPageResponse->assertSee('value="25" selected', false);

        // Test page navigation
        $page2Response = $this->get('/dealer/dashboard?page=2');
        $page2Response->assertStatus(200);
    }

    public function test_dealer_can_submit_bulk_wholesale_po(): void
    {
        $dealer = $this->authenticateDealer();
        $product = Product::where('is_active', true)->first();

        $poNumber = 'PO-TEST-' . rand(1000, 9999);

        $response = $this->post('/dealer/bulk-order', [
            'po_number' => $poNumber,
            'product_id' => $product->id,
            'quantity' => 10,
            'shipping_address' => 'Aczet Logistics Terminal, Hamburg, Germany',
            'notes' => 'Priority air freight dispatch request',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('leads', [
            'product_id' => $product->id,
            'email' => $dealer->email,
            'source' => 'Authorized Dealer Portal (Wholesale PO)',
        ]);
    }
}

