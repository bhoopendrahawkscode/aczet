<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Faq;
use App\Models\Category;
use Database\Seeders\FaqSeeder;

class FaqManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(FaqSeeder::class);
    }

    public function test_faq_seeder_seeds_all_144_faqs_across_25_categories(): void
    {
        $this->assertEquals(144, Faq::count());

        $groups = Faq::distinct('group_name')->pluck('group_name')->toArray();
        $this->assertContains('Laboratory Weighing', $groups);
        $this->assertContains('Jewellery & Precious-Metal Solutions', $groups);
        $this->assertContains('Laser Solutions', $groups);
        $this->assertContains('Industrial Weighing', $groups);
        $this->assertContains('Laboratory & Analytical Instruments', $groups);

        $categories = Faq::distinct('category_name')->pluck('category_name');
        $this->assertEquals(25, $categories->count());
        $this->assertTrue($categories->contains('Microbalance'));
        $this->assertTrue($categories->contains('Analytical Balance'));
        $this->assertTrue($categories->contains('Jewellery XRF Gold Testing Machines'));
        $this->assertTrue($categories->contains('Laser Welding Machines'));
        $this->assertTrue($categories->contains('Refractometer'));
    }

    public function test_guest_cannot_access_admin_faqs(): void
    {
        $response = $this->get('/admin/faqs');
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_view_admin_faqs_and_filter(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get('/admin/faqs');
        $response->assertStatus(200);
        $response->assertSee('Product FAQs & Knowledge Base');
        $response->assertSee('Total FAQs');
        $response->assertSee('144');
        $response->assertSee('Microbalance');

        // Test filtering by business group
        $filteredResponse = $this->actingAs($admin)->get('/admin/faqs?group=Laser+Solutions');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Laser Solutions');

        // Test search query
        $searchResponse = $this->actingAs($admin)->get('/admin/faqs?search=microgram');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('microbalance');
    }

    public function test_admin_can_create_edit_toggle_and_delete_faq(): void
    {
        $admin = User::factory()->create();

        // Create FAQ
        $createResponse = $this->actingAs($admin)->post('/admin/faqs/store', [
            'group_name' => 'Laboratory Weighing',
            'category_name' => 'Ultra-Microbalance',
            'question' => 'What is the precision limit of an ultra-microbalance?',
            'answer' => 'An ultra-microbalance measures down to 0.1 micrograms (0.0000001g).',
            'sort_order' => 1,
            'is_active' => '1',
        ]);
        $createResponse->assertRedirect('/admin/faqs');
        $this->assertDatabaseHas('faqs', [
            'question' => 'What is the precision limit of an ultra-microbalance?',
            'category_name' => 'Ultra-Microbalance',
        ]);

        $createdFaq = Faq::where('question', 'What is the precision limit of an ultra-microbalance?')->first();

        // Update FAQ
        $updateResponse = $this->actingAs($admin)->post("/admin/faqs/{$createdFaq->id}/update", [
            'group_name' => 'Laboratory Weighing',
            'category_name' => 'Ultra-Microbalance Pro',
            'question' => 'What is the updated precision limit of an ultra-microbalance?',
            'answer' => 'Updated answer with verified ISO 17025 metrology calibration.',
            'sort_order' => 2,
            'is_active' => '1',
        ]);
        $updateResponse->assertRedirect('/admin/faqs');
        $this->assertDatabaseHas('faqs', [
            'id' => $createdFaq->id,
            'category_name' => 'Ultra-Microbalance Pro',
            'question' => 'What is the updated precision limit of an ultra-microbalance?',
        ]);

        // Toggle Status
        $toggleResponse = $this->actingAs($admin)->post("/admin/faqs/{$createdFaq->id}/toggle-status");
        $toggleResponse->assertRedirect('/admin/faqs');
        $this->assertFalse($createdFaq->fresh()->is_active);

        // Delete FAQ
        $deleteResponse = $this->actingAs($admin)->delete("/admin/faqs/{$createdFaq->id}");
        $deleteResponse->assertRedirect('/admin/faqs');
        $this->assertDatabaseMissing('faqs', ['id' => $createdFaq->id]);
    }

    public function test_frontend_faqs_page_loads_with_search_and_categories(): void
    {
        $response = $this->get('/faqs');
        $response->assertStatus(200);
        $response->assertSee('Frequently Asked');
        $response->assertSee('Laboratory Weighing');
        $response->assertSee('Microbalance');
        $response->assertSee('faqSearchInput');
        $response->assertSee('schema.org');
        $response->assertSee('FAQPage');
    }

    public function test_admin_sidebar_and_navigation_include_faq_links(): void
    {
        $admin = User::factory()->create();

        // Check Admin sidebar contains link
        $adminResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee(route('admin.faqs'));
        $adminResponse->assertSee('Product FAQs');

        // Check Frontend header and footer contain link
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee(route('faqs.index'));
        $homeResponse->assertSee('Product FAQs');
    }
}
