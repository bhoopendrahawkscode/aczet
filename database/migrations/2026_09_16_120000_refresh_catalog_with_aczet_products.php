<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRegionVisibility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const CY224_IMAGE = '/images/products/cy224.svg';
    private const CM21_IMAGE = '/images/products/cm21.svg';
    private const IS600_IMAGE = '/images/products/is600.svg';
    private const GXZ600_IMAGE = '/images/products/gxz600.svg';
    private const MB200_IMAGE = '/images/products/mb200.svg';

    public function up(): void
    {
        if (! Schema::hasTable('products') || Product::count() === 0) {
            return;
        }

        $categoryUpdates = [
            'analytical-balances' => ['name' => 'Laboratory Balances', 'description' => 'Micro, semi-micro, analytical and precision balances for laboratory weighing from 0.1 microgram to 35,000 grams.', 'image' => self::CY224_IMAGE],
            'micro-balances' => ['name' => 'Scales', 'description' => 'Table top, waterproof, counting, bench, medical, crane and price-computing scales for everyday weighing.', 'image' => self::CM21_IMAGE],
            'moisture-analyzers' => ['name' => 'Weigh Bridge & Systems', 'description' => 'Road, portable truck, tank and rail weighing systems with capacities from 5 tonnes to 100 tonnes.', 'image' => self::IS600_IMAGE],
            'industrial-scales' => ['name' => 'Jewellery Instruments', 'description' => 'Gold testing, laser marking, laser welding, engraving, melting and density solutions for jewellery professionals.', 'image' => self::GXZ600_IMAGE],
            'jewelry-balances' => ['name' => 'Handheld XRF', 'description' => 'Portable material, minerals, RoHS and PMI analysis with fast, non-destructive results.', 'image' => self::MB200_IMAGE],
        ];

        $categories = [];
        foreach ($categoryUpdates as $slug => $values) {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                $category->update($values);
                $categories[$slug] = $category;
            }
        }

        $extraCategories = [
            ['slug' => 'industrial-instruments', 'name' => 'Industrial Instruments', 'description' => 'Measurement, analysis, laser marking and laser spot welding equipment for industrial applications.', 'image' => self::IS600_IMAGE],
            ['slug' => 'ultrasonic-cleaners', 'name' => 'Professional Digital Ultrasonic Cleaner / Bath', 'description' => 'CUB Series digital ultrasonic cleaners with durable SS 304 tanks from 2 to 100 litres.', 'image' => self::MB200_IMAGE],
            ['slug' => 'check-weighers-metal-detectors', 'name' => 'Check Weighers & Metal Detectors', 'description' => 'High-speed checkweighers, dynamic checkweighers and metal detection systems for production lines.', 'image' => self::IS600_IMAGE],
            ['slug' => 'mass-comparators', 'name' => 'Mass Comparator', 'description' => 'CM 21/100MC mass comparators for reference weights and precision measurement from 1 mg to 100 g.', 'image' => self::CM21_IMAGE],
            ['slug' => 'standard-calibration-weights', 'name' => 'Standard Calibration Weights', 'description' => 'OIML R111 stainless steel E1 class weights, singles and sets from 1 g to 20 kg.', 'image' => self::GXZ600_IMAGE],
        ];

        foreach ($extraCategories as $values) {
            $categories[$values['slug']] = Category::updateOrCreate(['slug' => $values['slug']], $values);
        }

        $products = [
            ['model' => 'CY-224C', 'category' => 'analytical-balances', 'name' => 'Semi Micro & Analytical Balance', 'description' => 'Fully automatic analytical weighing with built-in calibration for accurate laboratory results.', 'image' => self::CY224_IMAGE],
            ['model' => 'CM-A', 'category' => 'micro-balances', 'name' => 'Touch Screen Micro Balance', 'description' => 'Touch screen micro balance with 22 g capacity, 30 mm pan and 1 microgram readability.', 'image' => self::CM21_IMAGE],
            ['model' => 'TTS-31', 'category' => 'micro-balances', 'name' => 'Table Top Scale', 'description' => 'Practical table top weighing from 1 g to 31 kg with LED or LCD display and optional RS-232C.', 'image' => self::IS600_IMAGE],
            ['model' => 'WB-100T', 'category' => 'moisture-analyzers', 'name' => 'Weigh Bridge', 'description' => 'Road weigh bridge with 100 tonne capacity, 18 x 3 metre platform and LED display.', 'image' => self::IS600_IMAGE],
            ['model' => 'COMPACT-ECO', 'category' => 'industrial-scales', 'name' => 'Gold Testing Machine (Compact Eco Series)', 'description' => 'Computerised gold analyser with 50 kV software-controlled X-ray tube and precision collimator.', 'image' => self::GXZ600_IMAGE],
            ['model' => 'NANORIS-PIN-SDD', 'category' => 'jewelry-balances', 'name' => 'Material Analysis Handheld XRF Spectrometer', 'description' => 'Nanoris PIN-SDD handheld XRF spectrometer for fast, non-destructive industrial material analysis.', 'image' => self::MB200_IMAGE],
            ['model' => 'AXIOM-CUBE-X', 'category' => 'industrial-instruments', 'name' => 'Coating Thickness Measurement & Material Analysis', 'description' => 'X-ray fluorescence analysis for identifying and quantifying elements in science and industry.', 'image' => self::IS600_IMAGE],
            ['model' => 'CUB-10', 'category' => 'ultrasonic-cleaners', 'name' => 'Digital Ultrasonic Cleaner / Bath - CUB Series', 'description' => 'Professional ultrasonic cleaning with a durable SS 304 tank and 2 to 100 litre options.', 'image' => self::MB200_IMAGE],
            ['model' => 'CW-H', 'category' => 'check-weighers-metal-detectors', 'name' => 'High Speed Checkweigher', 'description' => 'Industrial PC based high-speed checkweigher with user-friendly operation and automatic zeroing.', 'image' => self::IS600_IMAGE],
            ['model' => 'CM-21/100MC', 'category' => 'mass-comparators', 'name' => 'CM 21/100MC Mass Comparator', 'description' => 'Mass comparator for reference weights across a range of 1 mg to 100 g.', 'image' => self::CM21_IMAGE],
            ['model' => '117-01', 'category' => 'standard-calibration-weights', 'name' => 'Standard Calibration Weights (E1 Class)', 'description' => 'OIML R111 compliant stainless steel E1 class weights, available from 1 g to 20 kg.', 'image' => self::GXZ600_IMAGE],
        ];

        foreach ($products as $index => $values) {
            $category = $categories[$values['category']] ?? null;
            if (! $category) {
                continue;
            }

            $product = Product::updateOrCreate(
                ['model_number' => $values['model']],
                [
                    'category_id' => $category->id,
                    'name' => 'Aczet ' . $values['name'],
                    'slug' => Str::slug('aczet-' . $values['name']),
                    'short_description' => $values['description'],
                    'full_description' => $values['description'],
                    'main_image' => $values['image'],
                    'price' => null,
                    'is_featured' => $index < 6,
                    'is_active' => true,
                ]
            );

            foreach (['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'] as $region) {
                ProductRegionVisibility::updateOrCreate(
                    ['product_id' => $product->id, 'region' => $region],
                    ['is_visible' => true]
                );
            }
        }
    }

    public function down(): void
    {
        // Catalog content is intentionally retained if this migration is rolled back.
    }
};
