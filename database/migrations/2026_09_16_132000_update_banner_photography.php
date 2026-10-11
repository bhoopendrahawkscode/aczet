<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('banners')->where('sort_order', 0)->update(['image' => '/images/banner-industry.jpg']);
        DB::table('banners')->where('sort_order', 1)->update(['image' => '/images/banner-vision.jpg']);
        DB::table('banners')->where('sort_order', 2)->update(['image' => '/images/banner-service.jpg']);
    }

    public function down(): void
    {
        DB::table('banners')->where('sort_order', 0)->update(['image' => '/images/products/micro-touchscreen.jpg']);
        DB::table('banners')->where('sort_order', 1)->update(['image' => '/images/products/weigh-bridge.jpg']);
        DB::table('banners')->where('sort_order', 2)->update(['image' => '/images/products/gold-compact-eco.jpg']);
    }
};
