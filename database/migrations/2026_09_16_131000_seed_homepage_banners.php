<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('banners')->exists()) {
            return;
        }

        $now = now();
        DB::table('banners')->insert([
            ['eyebrow' => 'Precision instruments since 1986', 'title' => 'Weigh with confidence.', 'description' => 'Dependable laboratory and industrial weighing systems for the moments where a small difference matters.', 'image' => '/images/products/micro-touchscreen.jpg', 'cta_label' => 'Explore the range', 'cta_url' => '/products', 'sort_order' => 0, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['eyebrow' => 'Built for regulated work', 'title' => 'Make every result count.', 'description' => 'From micro balances to high-capacity systems, find clear specifications and practical technical support.', 'image' => '/images/products/weigh-bridge.jpg', 'cta_label' => 'View solutions', 'cta_url' => '/products', 'sort_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['eyebrow' => 'Technical support that stays useful', 'title' => 'A better answer starts here.', 'description' => 'Talk to Aczet engineers about your sample, capacity, environment, or next weighing challenge.', 'image' => '/images/products/gold-compact-eco.jpg', 'cta_label' => 'Talk to an engineer', 'cta_url' => '/contact', 'sort_order' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        DB::table('banners')->truncate();
    }
};
