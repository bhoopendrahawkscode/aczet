<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminProductAndImageTest extends TestCase
{
    protected function authenticateAdmin(): User
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@aczet.com'],
            ['name' => 'Admin Tester', 'password' => bcrypt('password123')]
        );
        $this->actingAs($admin);
        return $admin;
    }

    public function test_product_falls_back_to_noimage_when_empty_or_file_missing(): void
    {
        $cat = Category::first();
        $this->assertNotNull($cat);

        // Product with no image
        $productNoImg = Product::create([
            'category_id' => $cat->id,
            'name' => 'Test Balance No Image',
            'model_number' => 'TBN-01',
            'slug' => 'test-balance-no-image',
            'short_description' => 'Test balance without image',
            'main_image' => null,
            'is_active' => true,
        ]);

        $this->assertStringContainsString('noimage.jpeg', $productNoImg->main_image);

        // Product with non-existent local file
        $productMissingImg = Product::create([
            'category_id' => $cat->id,
            'name' => 'Test Balance Missing Image',
            'model_number' => 'TBM-01',
            'slug' => 'test-balance-missing-image',
            'short_description' => 'Test balance with missing file',
            'main_image' => '/uploads/products/non_existent_image_123456.png',
            'is_active' => true,
        ]);

        $this->assertStringContainsString('noimage.jpeg', $productMissingImg->main_image);
    }

    public function test_category_falls_back_to_noimage_when_empty_or_file_missing(): void
    {
        $catNoImg = Category::create([
            'name' => 'Test Cat No Image',
            'slug' => 'test-cat-no-image',
            'image' => null,
        ]);

        $this->assertStringContainsString('noimage.jpeg', $catNoImg->image_url);

        $catMissingImg = Category::create([
            'name' => 'Test Cat Missing Image',
            'slug' => 'test-cat-missing-image',
            'image' => '/uploads/categories/missing_file_789.png',
        ]);

        $this->assertStringContainsString('noimage.jpeg', $catMissingImg->image_url);
    }

    public function test_admin_products_renders_styled_pagination_and_edit_modal(): void
    {
        $this->authenticateAdmin();

        $response = $this->get('/admin/products');
        $response->assertStatus(200);

        // Verify styled pagination container
        $response->assertSee('custom-pagination-container', false);
        $response->assertSee('custom-pagination', false);
        $response->assertSee('Showing', false);
        $response->assertSee('results', false);

        // Verify Edit Product Modal and Add Product Modal elements
        $response->assertSee('editProductModal', false);
        $response->assertSee('addProductModal', false);
        $response->assertSee('Edit Product');
        $response->assertSee('Update Image');
    }

    public function test_admin_can_create_product_with_image_upload_or_path(): void
    {
        $this->authenticateAdmin();
        $cat = Category::first();

        // 1. With static image URL / path
        $response = $this->post('/admin/products/store', [
            'category_id' => $cat->id,
            'name' => 'Analytical Microbalance Alpha',
            'model_number' => 'AMA-100',
            'short_description' => 'Alpha microbalance precision test',
            'price' => 4500.00,
            'image' => '/images/products/cy224.svg',
            'is_featured' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'model_number' => 'AMA-100',
            'main_image' => '/images/products/cy224.svg',
        ]);

        // 2. With file upload
        $fakeImage = UploadedFile::fake()->image('test_product.jpg', 600, 600);
        $response2 = $this->post('/admin/products/store', [
            'category_id' => $cat->id,
            'name' => 'Analytical Microbalance Beta',
            'model_number' => 'AMB-200',
            'short_description' => 'Beta microbalance precision test',
            'price' => 5200.00,
            'image_file' => $fakeImage,
            'is_featured' => 0,
            'is_active' => 1,
        ]);

        $response2->assertRedirect('/admin/products');
        $product = Product::where('model_number', 'AMB-200')->first();
        $this->assertNotNull($product);
        $this->assertStringContainsString('/uploads/products/prod_', $product->getRawOriginal('main_image'));
    }

    public function test_admin_can_update_product_and_change_image(): void
    {
        $this->authenticateAdmin();
        $cat = Category::first();

        $product = Product::create([
            'category_id' => $cat->id,
            'name' => 'Original Machine',
            'model_number' => 'ORIG-01',
            'slug' => 'original-machine-01',
            'short_description' => 'Original short summary',
            'price' => 1200.00,
            'main_image' => '/images/products/noimage.jpeg',
            'is_active' => true,
        ]);

        // Update with new image path
        $response = $this->post("/admin/products/{$product->id}/update", [
            'category_id' => $cat->id,
            'name' => 'Original Machine Updated',
            'model_number' => 'ORIG-01-REV',
            'short_description' => 'Updated short summary',
            'price' => 1500.00,
            'image' => '/images/products/cm21.svg',
            'is_featured' => 1,
            'is_active' => 1,
        ]);

        $response->assertSessionHas('success');
        $product->refresh();
        $this->assertEquals('ORIG-01-REV', $product->model_number);
        $this->assertEquals('/images/products/cm21.svg', $product->getRawOriginal('main_image'));

        // Update with file upload
        $fakeUpload = UploadedFile::fake()->image('revised_model.png', 500, 500);
        $response2 = $this->post("/admin/products/{$product->id}/update", [
            'category_id' => $cat->id,
            'name' => 'Original Machine Uploaded',
            'model_number' => 'ORIG-01-REV',
            'short_description' => 'Updated short summary',
            'price' => 1500.00,
            'image_file' => $fakeUpload,
            'is_active' => 1,
        ]);

        $response2->assertSessionHas('success');
        $product->refresh();
        $this->assertStringContainsString('/uploads/products/prod_', $product->getRawOriginal('main_image'));
    }

    public function test_admin_can_update_category_image(): void
    {
        $this->authenticateAdmin();
        $category = Category::first();
        $this->assertNotNull($category);

        // Update with image URL/path
        $response = $this->post("/admin/categories/{$category->id}/update", [
            'name' => $category->name,
            'description' => 'Updated category description',
            'image' => '/uploads/categories/test_icon.svg',
        ]);

        $response->assertSessionHas('success');
        $category->refresh();
        $this->assertEquals('/uploads/categories/test_icon.svg', $category->image);

        // Update with file upload
        $fakeIcon = UploadedFile::fake()->image('cat_icon.png', 200, 200);
        $response2 = $this->post("/admin/categories/{$category->id}/update", [
            'name' => $category->name,
            'description' => 'Updated category description again',
            'image_file' => $fakeIcon,
        ]);

        $response2->assertSessionHas('success');
        $category->refresh();
        $this->assertStringContainsString('/uploads/categories/cat_', $category->image);
    }
}
