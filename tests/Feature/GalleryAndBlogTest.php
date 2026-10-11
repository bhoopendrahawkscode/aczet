<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Blog;

class GalleryAndBlogTest extends TestCase
{
    public function test_frontend_gallery_page_loads_with_database_items(): void
    {
        $response = $this->get('/gallery');
        $response->assertStatus(200);
        $response->assertSee('Ultra-Clean Assembly Facility');
        $response->assertSee('Laser Interferometer Metrology Lab');
    }

    public function test_homepage_loads_latest_insights(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Useful thinking from Aczet');
        $response->assertSee('hero-carousel-viewport');
        $response->assertSee('Weigh with confidence');
        $response->assertSee('Single-Block Electromagnetic Force Compensation');
        $response->assertSee('logo-mark-cube');
    }

    public function test_frontend_blogs_page_loads_with_blog_and_news_items(): void
    {
        $response = $this->get('/blogs');
        $response->assertStatus(200);
        $response->assertSee('Understanding Calibration Standards');
        $response->assertSee('Aczet Showcases Next-Gen Micro-Balances');
    }

    public function test_admin_galleries_requires_authentication(): void
    {
        $response = $this->get('/admin/galleries');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_blogs_requires_authentication(): void
    {
        $response = $this->get('/admin/blogs');
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_galleries_and_blogs(): void
    {
        $user = User::first() ?? User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/galleries')
            ->assertStatus(200)
            ->assertSee('Gallery Photos')
            ->assertSee('Ultra-Clean Assembly Facility');

        $this->actingAs($user)
            ->get('/admin/blogs')
            ->assertStatus(200)
            ->assertSee('Blog')
            ->assertSee('Aczet Showcases Next-Gen Micro-Balances');

        $this->actingAs($user)
            ->get('/admin/banners')
            ->assertStatus(200)
            ->assertSee('Homepage Banner Manager')
            ->assertSee('Weigh with confidence.');
    }

    public function test_admin_can_create_update_and_delete_gallery_item(): void
    {
        $user = User::first() ?? User::factory()->create();

        // 1. Create
        $response = $this->withoutMiddleware()->actingAs($user)->post('/admin/galleries/store', [
            'title' => 'Test Calibration Chamber',
            'category' => 'Testing',
            'image' => '/images/products/cy224.svg',
            'sort_order' => 10,
            'is_active' => '1',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('galleries', [
            'title' => 'Test Calibration Chamber',
            'category' => 'Testing',
        ]);

        $item = Gallery::where('title', 'Test Calibration Chamber')->first();
        $this->assertNotNull($item);

        // 2. Update
        $updateResponse = $this->withoutMiddleware()->actingAs($user)->post('/admin/galleries/' . $item->id . '/update', [
            'title' => 'Updated Calibration Chamber',
            'category' => 'Testing',
            'image' => '/images/products/cy224.svg',
            'sort_order' => 12,
            'is_active' => '1',
        ]);
        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('galleries', [
            'id' => $item->id,
            'title' => 'Updated Calibration Chamber',
            'sort_order' => 12,
        ]);

        // 3. Delete
        $deleteResponse = $this->withoutMiddleware()->actingAs($user)->delete('/admin/galleries/' . $item->id);
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('galleries', [
            'id' => $item->id,
        ]);
    }

    public function test_admin_can_create_update_and_delete_blog_and_news(): void
    {
        $user = User::first() ?? User::factory()->create();

        // 1. Create News
        $response = $this->withoutMiddleware()->actingAs($user)->post('/admin/blogs/store', [
            'title' => 'New Product Release 2026',
            'type' => 'News',
            'author' => 'Test Team',
            'summary' => 'Summary of new product release',
            'content' => 'Full detailed content about the product launch event.',
            'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b',
            'published_at' => '2026-09-16',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('blogs', [
            'title' => 'New Product Release 2026',
            'type' => 'News',
        ]);

        $blog = Blog::where('title', 'New Product Release 2026')->first();
        $this->assertNotNull($blog);

        // 2. Update News
        $updateResponse = $this->withoutMiddleware()->actingAs($user)->post('/admin/blogs/' . $blog->id . '/update', [
            'title' => 'Updated Product Release 2026',
            'type' => 'News',
            'author' => 'Test Team',
            'summary' => 'Updated summary',
            'content' => 'Updated content body.',
            'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b',
            'published_at' => '2026-09-16',
        ]);
        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Product Release 2026',
        ]);

        // 3. Delete News
        $deleteResponse = $this->withoutMiddleware()->actingAs($user)->delete('/admin/blogs/' . $blog->id);
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('blogs', [
            'id' => $blog->id,
        ]);
    }
}
