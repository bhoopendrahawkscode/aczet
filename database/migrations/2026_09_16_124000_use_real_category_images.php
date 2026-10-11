<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $images = [
            'analytical-balances' => '/images/products/micro-touchscreen.jpg',
            'micro-balances' => '/images/products/table-top-scale.jpg',
            'moisture-analyzers' => '/images/products/weigh-bridge.jpg',
            'industrial-scales' => '/images/products/gold-compact-eco.jpg',
            'jewelry-balances' => '/images/products/handheld-xrf.jpg',
            'industrial-instruments' => '/images/products/gold-cube.jpg',
            'ultrasonic-cleaners' => '/images/products/ultrasonic-small.jpg',
            'check-weighers-metal-detectors' => '/images/products/checkweigher-cwh.jpg',
            'mass-comparators' => '/images/products/micro-touchscreen.jpg',
            'standard-calibration-weights' => '/images/products/calibration-weight.jpg',
        ];

        foreach ($images as $slug => $image) {
            Category::where('slug', $slug)->update(['image' => $image]);
        }
    }

    public function down(): void
    {
        // Keep the real product photography available if this migration is rolled back.
    }
};
