<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Product;
use App\Models\ProductSpecification;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Readability MC for Moisture Analyzers
        $mbProds = Product::where('model_number', 'like', 'MB %')->get();
        foreach ($mbProds as $p) {
            $val = ($p->model_number === 'MB 54') ? '0.0001 % MC' : '0.001 % MC';
            ProductSpecification::updateOrCreate(
                ['product_id' => $p->id, 'spec_key' => 'Readability MC'],
                ['spec_value' => $val]
            );
        }

        // 2. Density Resolution for Density Scales
        $densityProds = [
            'CY 204GT' => '0.0001 g/cm³',
            'CY 613GT' => '0.001 g/cm³',
            'CY 3102GT' => '0.01 g/cm³',
        ];
        foreach ($densityProds as $m => $res) {
            $p = Product::where('model_number', $m)->first();
            if ($p) {
                ProductSpecification::updateOrCreate(
                    ['product_id' => $p->id, 'spec_key' => 'Density Resolution'],
                    ['spec_value' => $res]
                );
            }
        }

        // 3. OIML Class & Nominal Value for Test Weights
        $twProds = Product::where('category_id', 11)->orWhere('name', 'like', '%Test Weight%')->get();
        foreach ($twProds as $p) {
            $seriesSpec = $p->specifications->firstWhere('spec_key', 'Series');
            $capSpec = $p->specifications->firstWhere('spec_key', 'Weighing Capacity');
            if ($seriesSpec && in_array($seriesSpec->spec_value, ['E1', 'E2', 'F1', 'F2', 'M1'])) {
                ProductSpecification::updateOrCreate(
                    ['product_id' => $p->id, 'spec_key' => 'OIML Class'],
                    ['spec_value' => $seriesSpec->spec_value]
                );
            }
            if ($capSpec) {
                ProductSpecification::updateOrCreate(
                    ['product_id' => $p->id, 'spec_key' => 'Nominal Value'],
                    ['spec_value' => $capSpec->spec_value]
                );
            }
        }

        // 4. Weighing Sensor for Microbalances and Analytical Balances
        $microProds = Product::where('model_number', 'like', 'AXR %')->orWhere('model_number', 'like', 'CM %')->get();
        foreach ($microProds as $p) {
            ProductSpecification::updateOrCreate(
                ['product_id' => $p->id, 'spec_key' => 'Weighing Sensor'],
                ['spec_value' => 'Singleblock EMFC']
            );
        }

        // 5. Minimum Weight (USP, 0.1%, typical)
        $minWeights = [
            'AXR 2UA' => '0.5 mg',
            'AXR 2MA' => '1 mg',
            'AXR 6MA' => '1 mg',
            'AXR 11MA' => '1 mg',
            'AXR 2MF' => '1 mg',
            'AXR 5MF' => '1 mg',
            'CM 21' => '1.4 mg',
            'CM 21U' => '0.8 mg',
            'CY 224C' => '10 mg',
            'CY 224' => '10 mg',
            'CY 124' => '10 mg',
            'CY 64' => '10 mg',
            'CY 510' => '20 mg',
        ];
        foreach ($minWeights as $m => $mw) {
            $p = Product::where('model_number', $m)->first();
            if ($p) {
                ProductSpecification::updateOrCreate(
                    ['product_id' => $p->id, 'spec_key' => 'Minimum Weight (USP, 0.1%, typical)'],
                    ['spec_value' => $mw]
                );
            }
        }

        // 6. Name and Short Description for AXR 2UA, AXR 2MA, AXR 6MA matching screenshot
        $p2ua = Product::where('model_number', 'AXR 2UA')->first();
        if ($p2ua) {
            $p2ua->update([
                'name' => 'Ultra-Microbalance AXR 2UA',
                'short_description' => 'Next-generation ultra microbalance designed for extremely precise weighing of ultra-light samples. With 2.1 g capacity and 0.1 µg readability, the AXR 2UA delivers exceptional accuracy for advanced laboratory applications. It features a colour TFT touchscreen, automatic motorised internal calibration with PSAC, and a user-friendly interface for reliable, secure, and efficient micro-weighing performance.'
            ]);
        }

        $p2ma = Product::where('model_number', 'AXR 2MA')->first();
        if ($p2ma) {
            $p2ma->update([
                'name' => 'Microbalance AXR 2MA',
                'short_description' => 'Next-generation weighing technology with a solid, durable construction for long-term reliability. High-precision performance with 2.1 g capacity and 1 µg readability for accurate micro-weighing. Intuitive color TFT touchscreen display with internal calibration and easy leveling alerts. Supports 21 CFR part 11 compliant user management for secure and regulated laboratory operations. Multiple connectivity options including USB, RS232, and Ethernet for seamless data integration. Compact design with overload protection and power-saving mode, ideal for modern laboratories.'
            ]);
        }

        $p6ma = Product::where('model_number', 'AXR 6MA')->first();
        if ($p6ma) {
            $p6ma->update([
                'name' => 'Microbalance AXR 6MA',
                'short_description' => 'AXR Microbalance - precision micro weighing made simple. High-accuracy microbalance with capacitive touchscreen; automatic draft shield; all-round clear visibility; compact footprint; easy cleaning; designed for weighing rare, expensive, or toxic substances; ensures traceable and compliant results; supports laboratory standards such as USP and Euramet.'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        ProductSpecification::whereIn('spec_key', ['Readability MC', 'Density Resolution', 'OIML Class', 'Nominal Value'])->delete();
    }
};
