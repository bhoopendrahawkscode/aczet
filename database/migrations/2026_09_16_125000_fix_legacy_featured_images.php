<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Product::where('model_number', 'CM-21U')->update([
            'main_image' => '/images/products/micro-balance-real.jpg',
        ]);

        Product::where('model_number', 'MB-200T')->update([
            'main_image' => '/images/products/moisture-analyzer-real.jpg',
        ]);
    }

    public function down(): void
    {
        // Keep the real featured product photography available if this migration is rolled back.
    }
};
