<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class CompareFeatureTest extends TestCase
{
    public function test_floating_compare_dock_markup_renders_on_catalog_page(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);

        // Dock container and elements matching Screenshot 1
        $response->assertSee('floating-compare-dock', false);
        $response->assertSee('compare-dock-container', false);
        $response->assertSee('compare-dock-left-wrap', false);
        $response->assertSee('compare-dock-actions', false);
        $response->assertSee('compare-dock-count-text', false);
        $response->assertSee('btn-dock-compare', false);
        $response->assertSee('Compare Products (0/10)', false);

        // Compare modal container matching Screenshot 2
        $response->assertSee('comparisonMatrixModal', false);
        $response->assertSee('modal-comparison-window', false);
        $response->assertSee('modal-compare-head', false);
        $response->assertSee('compareModalCategoryTitle', false);
        $response->assertSee('btnDownloadComparePdf', false);
    }

    public function test_compare_endpoint_ajax_returns_json_and_table_html(): void
    {
        $p1 = Product::where('model_number', 'AXR 2MA')->first();
        $p2 = Product::where('model_number', 'AXR 2MF')->first();

        if (!$p1 || !$p2) {
            $p1 = Product::first();
            $p2 = Product::skip(1)->first();
        }

        $this->assertNotNull($p1);
        $this->assertNotNull($p2);

        $response = $this->getJson("/compare?ids={$p1->id},{$p2->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'total',
            'category_name',
            'pdf_url',
            'table_html',
            'category_mismatch'
        ]);

        $json = $response->json();
        $this->assertTrue($json['success']);
        $this->assertEquals(2, $json['total']);
        $this->assertStringContainsString('table-side-by-side-compare', $json['table_html']);
        $this->assertStringContainsString('Get a Quote', $json['table_html']);
        $this->assertStringContainsString('btn-remove-compare-col', $json['table_html']);
    }

    public function test_compare_pdf_generates_valid_pdf_download(): void
    {
        $p1 = Product::where('model_number', 'AXR 2MA')->first();
        $p2 = Product::where('model_number', 'AXR 2MF')->first();

        if (!$p1 || !$p2) {
            $p1 = Product::first();
            $p2 = Product::skip(1)->first();
        }

        $response = $this->get("/compare/pdf?ids={$p1->id},{$p2->id}");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('.pdf', $response->headers->get('content-disposition'));
    }

    public function test_category_incompatibility_rule_in_compare_endpoint(): void
    {
        $cat1 = Category::firstOrCreate(['slug' => 'lab-balances', 'name' => 'Laboratory Balances']);
        $cat2 = Category::firstOrCreate(['slug' => 'industrial-scales', 'name' => 'Industrial Scales']);

        $prodA = Product::create([
            'category_id' => $cat1->id,
            'name' => 'Balance Model A',
            'model_number' => 'BAL-01',
            'slug' => 'balance-model-a',
            'is_active' => true,
        ]);

        $prodB = Product::create([
            'category_id' => $cat2->id,
            'name' => 'Scale Model B',
            'model_number' => 'SCL-01',
            'slug' => 'scale-model-b',
            'is_active' => true,
        ]);

        // AJAX request returns category_mismatch = true
        $response = $this->getJson("/compare?ids={$prodA->id},{$prodB->id}");
        $response->assertStatus(200);
        $response->assertJson(['category_mismatch' => true]);
    }

    public function test_cy224c_and_cpr02_cannot_be_compared_due_to_different_categories(): void
    {
        $catAnalytical = Category::firstOrCreate(
            ['slug' => 'laboratory-balances-analytical-balances'],
            ['name' => 'Analytical Balances']
        );
        $catAccessories = Category::firstOrCreate(
            ['slug' => 'laboratory-balances-accessories-for-laboratory-weighing'],
            ['name' => 'Accessories for Laboratory weighing']
        );

        $cy224c = Product::create([
            'category_id' => $catAnalytical->id,
            'name' => 'Analytical Balance CY 224C',
            'model_number' => 'CY 224C',
            'slug' => 'analytical-balance-cy-224c-test',
            'is_active' => true,
        ]);

        $cpr02 = Product::create([
            'category_id' => $catAccessories->id,
            'name' => 'Aczet Printer CPR 02',
            'model_number' => 'CPR 02',
            'slug' => 'aczet-printer-cpr-02-test',
            'is_active' => true,
        ]);

        $this->assertNotEquals($cy224c->getComparisonGroupKey(), $cpr02->getComparisonGroupKey());
        $this->assertEquals('laboratory-balances', $cy224c->getComparisonGroupKey());
        $this->assertEquals('accessories-laboratory-weighing', $cpr02->getComparisonGroupKey());

        $response = $this->getJson("/compare?ids={$cy224c->id},{$cpr02->id}");
        $response->assertStatus(200);
        $response->assertJson(['category_mismatch' => true]);
        $json = $response->json();
        $this->assertEquals(1, $json['total']); // Keeps only the first product
    }

    public function test_similar_laboratory_balances_can_be_compared(): void
    {
        $catMicro = Category::firstOrCreate(
            ['slug' => 'laboratory-balances-microbalances'],
            ['name' => 'Microbalances']
        );
        $catPrecision = Category::firstOrCreate(
            ['slug' => 'laboratory-balances-precision-balances-and-scales'],
            ['name' => 'Precision Balances and Scales']
        );

        $prodMicro = Product::create([
            'category_id' => $catMicro->id,
            'name' => 'Microbalance AXR 2MA',
            'model_number' => 'AXR 2MA-T',
            'slug' => 'microbalance-axr-2ma-test',
            'is_active' => true,
        ]);

        $prodPrecision = Product::create([
            'category_id' => $catPrecision->id,
            'name' => 'Precision Balance ALT 223',
            'model_number' => 'ALT 223-T',
            'slug' => 'precision-balance-alt-223-test',
            'is_active' => true,
        ]);

        $this->assertEquals($prodMicro->getComparisonGroupKey(), $prodPrecision->getComparisonGroupKey());
        $this->assertEquals('laboratory-balances', $prodMicro->getComparisonGroupKey());

        $response = $this->getJson("/compare?ids={$prodMicro->id},{$prodPrecision->id}");
        $response->assertStatus(200);
        $response->assertJson(['category_mismatch' => false]);
        $json = $response->json();
        $this->assertEquals(2, $json['total']);
    }

    public function test_products_catalog_outputs_comparison_group_attributes(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee('data-comparison-group=', false);
        $response->assertSee('data-comparison-group-name=', false);
    }
}
