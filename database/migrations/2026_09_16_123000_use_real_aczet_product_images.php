<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $images = [
            'CY-224C' => '/images/products/ultra-micro-2.jpg',
            'CM-A' => '/images/products/micro-touchscreen.jpg',
            'TTS-31' => '/images/products/table-top-scale.jpg',
            'WB-100T' => '/images/products/weigh-bridge.jpg',
            'COMPACT-ECO' => '/images/products/gold-compact-eco.jpg',
            'NANORIS-PIN-SDD' => '/images/products/handheld-xrf.jpg',
            'AXIOM-CUBE-X' => '/images/products/gold-cube.jpg',
            'CUB-10' => '/images/products/ultrasonic-small.jpg',
            'CW-H' => '/images/products/checkweigher-cwh.jpg',
            'CM-21/100MC' => '/images/products/micro-touchscreen.jpg',
            '117-01' => '/images/products/calibration-weight.jpg',
        ];

        foreach ($images as $model => $image) {
            Product::where('model_number', $model)->update(['main_image' => $image]);
        }
    }

    public function down(): void
    {
        // Keep the downloaded product photography available if this migration is rolled back.
    }
};
