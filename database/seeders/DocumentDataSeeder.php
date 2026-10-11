<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\ProductFaq;
use App\Models\ProductRegionVisibility;
use Illuminate\Support\Facades\DB;

class DocumentDataSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = storage_path('app/sheets_catalog.json');
        if (!file_exists($jsonPath)) {
            $jsonPath = base_path('storage/app/sheets_catalog.json');
        }
        if (!file_exists($jsonPath)) {
            $this->command->error("JSON data file not found at: {$jsonPath}");
            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);
        if (!$data) {
            $this->command->error("Failed to decode JSON data.");
            return;
        }

        $this->command->info("Seeding categories and subcategories from structured sheets...");

        // Main category images mapping
        $mainCatImages = [
            'Laboratory Weighing' => 'images/products/micro-touchscreen.jpg',
            'Laboratory Balances' => 'images/products/micro-touchscreen.jpg',
            'Industrial weighing solutions' => 'images/products/table-top-scale.jpg',
            'Industrial Weighing Solutions' => 'images/products/table-top-scale.jpg',
            'Jewelry machine' => 'images/products/gold-cube.jpg',
            'Jewelry Machines' => 'images/products/gold-cube.jpg',
            'Industrial machines' => 'images/products/handheld-xrf.jpg',
            'Industrial Machines' => 'images/products/handheld-xrf.jpg',
            'Laboratory Instruments' => 'images/products/ultrasonic-large.jpg',
        ];

        $categoryModels = [];
        $subCategoryModels = [];

        foreach ($data['categories_hierarchy'] as $mainCatName => $subCats) {
            $mainCatSlug = Str::slug($mainCatName);
            if ($mainCatName === 'Jewelry machine') {
                $mainCatSlug = 'jewelry-machines';
            }
            $parent = Category::updateOrCreate(
                ['slug' => $mainCatSlug],
                [
                    'name' => $mainCatName,
                    'description' => "Precision {$mainCatName} engineered for research, industry, and certified metrology.",
                    'image' => $mainCatImages[$mainCatName] ?? 'images/products/micro-touchscreen.jpg',
                    'parent_id' => null
                ]
            );
            $categoryModels[$mainCatName] = $parent;

            foreach ($subCats as $subCatName) {
                if (empty($subCatName)) continue;
                $subSlug = Str::slug($mainCatName . '-' . $subCatName);
                if ($mainCatSlug === 'laboratory-weighing') {
                    $subSlug = ($subCatName === 'Laboratory Balances') ? 'laboratory-balances' : Str::slug('laboratory-balances-' . $subCatName);
                }
                $sub = Category::updateOrCreate(
                    ['slug' => $subSlug],
                    [
                        'name' => $subCatName,
                        'parent_id' => $parent->id,
                        'description' => "{$subCatName} by Aczet - High precision and dependable engineering.",
                        'image' => $mainCatImages[$mainCatName] ?? 'images/products/micro-touchscreen.jpg'
                    ]
                );
                $subCategoryModels[$mainCatName . ':::' . $subCatName] = $sub;
                $subCategoryModels[$parent->name . ':::' . $subCatName] = $sub;
            }
        }

        $this->command->info("Seeding products from COUNTRY-WISE and Technical sheets...");

        // Product default images based on category
        $defaultImages = [
            'Microbalances' => 'images/products/cm21.svg',
            'Analytical Balances' => 'images/products/cy224.svg',
            'Precision Balances and Scales' => 'images/products/cy224.svg',
            'Moisture Analyzers' => 'images/products/mb200.svg',
            'Density Scales' => 'images/products/gxz600.svg',
            'Floor Scales' => 'images/products/is600.svg',
            'Bench & Platform Scales' => 'images/products/is600.svg',
            'Desk Scales' => 'images/products/table-top-scale.jpg',
            'Jewelry Scales & Balances' => 'images/products/gxz600.svg',
            'XRF Instruments - Gold Testing Machine' => 'images/products/gold-compact-eco.jpg',
            'Laser Spot Welding Machines' => 'images/products/gold-cube.jpg',
            'Product Inspection Solutions' => 'images/products/checkweigher-cwh.jpg',
            'Ultrasonic Cleaners' => 'images/products/ultrasonic-small.jpg',
            'Test Weights' => 'images/products/calibration-weight.jpg',
        ];

        $seededCount = 0;
        $createdProducts = [];

        foreach ($data['products'] as $pItem) {
            $mainCat = $pItem['main_category'];
            $subCat = $pItem['sub_category'];
            $model = $pItem['model_number'];

            $catModel = $subCategoryModels[$mainCat . ':::' . $subCat] ?? $categoryModels[$mainCat] ?? null;
            if (!$catModel) continue;

            $existing = Product::where('model_number', $model)
                ->where('category_id', $catModel->id)
                ->first();

            if ($existing) {
                $product = $existing;
                if (!empty($pItem['short_description'])) {
                    $product->short_description = $pItem['short_description'];
                }
                $product->is_active = true;
                $product->save();
            } else {
                $baseSlug = Str::slug('aczet-' . $model);
                if (Product::where('slug', $baseSlug)->exists()) {
                    $baseSlug = Str::slug('aczet-' . $catModel->slug . '-' . $model);
                }
                if (Product::where('slug', $baseSlug)->exists()) {
                    $baseSlug = Str::slug('aczet-' . $model . '-' . Str::random(5));
                }

                $pName = !empty($subCat) ? "Aczet {$subCat} ({$model})" : "Aczet {$mainCat} ({$model})";
                $img = $defaultImages[$subCat] ?? $defaultImages[$mainCat] ?? 'frontend/images/products/cy-224c.png';

                $shortDesc = !empty($pItem['short_description']) 
                    ? $pItem['short_description'] 
                    : "Precision engineered Aczet {$model} featuring {$pItem['capacity']} capacity and {$pItem['readability']} readability for certified laboratory and industrial workflows.";

                $product = Product::create([
                    'category_id' => $catModel->id,
                    'name' => $pName,
                    'model_number' => $model,
                    'slug' => $baseSlug,
                    'short_description' => $shortDesc,
                    'full_description' => $shortDesc . " Backed by ISO/IEC 17025 certified calibration and Aczet factory warranty.",
                    'main_image' => $img,
                    'gallery' => [$img],
                    'pdf_datasheet' => "docs/{$baseSlug}-datasheet.pdf",
                    'pdf_manual' => "docs/{$baseSlug}-user-manual.pdf",
                    'pdf_service_manual' => "docs/{$baseSlug}-service-manual.pdf",
                    'is_featured' => ($seededCount < 8),
                    'is_active' => true,
                ]);
            }

            $createdProducts[$model] = $product;
            $seededCount++;

            // Seed Core Specifications
            $specItems = [
                ['group' => 'Metrology Parameters', 'key' => 'Weighing Capacity', 'val' => $pItem['capacity']],
                ['group' => 'Metrology Parameters', 'key' => 'Readability', 'val' => $pItem['readability']],
                ['group' => 'Engineering Architecture', 'key' => 'Series', 'val' => $pItem['series']],
                ['group' => 'Engineering Architecture', 'key' => 'Type', 'val' => $pItem['type']],
                ['group' => 'Interface & Calibration', 'key' => 'Display', 'val' => $pItem['display']],
                ['group' => 'Interface & Calibration', 'key' => 'Calibration', 'val' => $pItem['calibration']],
                ['group' => 'Sensor & Construction', 'key' => 'Weighing Sensor', 'val' => $pItem['sensors']],
                ['group' => 'Quality Standards', 'key' => 'Class', 'val' => $pItem['class']],
                ['group' => 'Quality Standards', 'key' => 'Special Filter', 'val' => $pItem['special_filter']],
            ];

            // If extra technical specs exist for this model
            if (isset($data['tech_specs'][$model])) {
                foreach ($data['tech_specs'][$model] as $tk => $tv) {
                    $specItems[] = ['group' => 'Technical Parameters', 'key' => $tk, 'val' => $tv];
                }
            }

            foreach ($specItems as $sIdx => $sObj) {
                if (empty($sObj['val'])) continue;
                ProductSpecification::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'spec_key' => $sObj['key']
                    ],
                    [
                        'group_name' => $sObj['group'],
                        'spec_value' => $sObj['val'],
                        'sort_order' => $sIdx + 1
                    ]
                );
            }

            // Seed Region Visibilities
            $regions = [
                'India' => $pItem['india_avail'],
                'USA' => $pItem['usa_avail'],
                'GCC' => $pItem['global_avail'],
                'Europe' => $pItem['global_avail'],
                'Asia Pacific' => $pItem['global_avail'],
                'Thailand' => $pItem['global_avail'],
            ];

            foreach ($regions as $rName => $isVisible) {
                ProductRegionVisibility::updateOrCreate(
                    ['product_id' => $product->id, 'region' => $rName],
                    ['is_visible' => $isVisible]
                );
            }
        }

        $this->command->info("Seeded {$seededCount} products and specifications.");

        // Seed 26 Corporate FAQs
        $this->command->info("Seeding corporate technical FAQs from Aczet_Product_FAQ docx.docx...");
        $faqMapping = [
            'Microbalance' => ['Microbalances', 'Ultra-Microbalances'],
            'Semi-Micro Balance' => ['Microbalances', 'Analytical Balances'],
            'Analytical Balance' => ['Analytical Balances'],
            'Precision Balance' => ['Precision Balances and Scales'],
            'Moisture Analyzer' => ['Moisture Analyzers'],
            'Electronic Densimeter' => ['Density Scales'],
            'Calibration Test Weights' => ['Test Weights'],
            'Jewellery and Gold Balances' => ['Jewelry Scales & Balances'],
            'Jewellery XRF Gold Testing Machines' => ['XRF Instruments - Gold Testing Machine'],
            'Handheld and Industrial XRF Analyzers' => ['XRF Instruments - Coating Thickness measurement', 'XRF Instruments - Alloy, PMI, Mining, RoHS'],
            'Laser Marking Machines' => ['Laser Engraver & Cutting'],
            'Laser Welding Machines' => ['Laser Spot Welding Machines'],
            'Laser Cutting Machines' => ['Laser Engraver & Cutting'],
            'Industrial Weighing Scales' => ['Floor Scales', 'Bench & Platform Scales', 'Desk Scales'],
            'Load Cells and Weighing Indicators' => ['Weigh Modules & Load Cells Systems', 'Crane Scales'],
            'Ultrasonic Cleaner' => ['Ultrasonic Cleaners'],
            'Refractometer' => ['Refractometers'],
            'Polarimeter' => ['Polarimeters'],
            'Colorimeter' => ['Visual Colorimeter'],
            'Probe Ultrasonicator' => ['Ultrasonic Probe Sonicator'],
            'Milk Analyzer' => ['Ultrasonic Milk Analyzers'],
            'Tablet Hardness Tester' => ['Tablet Hardness Tester'],
            'Rotational Viscometer' => ['Rotational Viscometer'],
            'Ice Flake Maker' => ['ICE Flake Maker'],
            'Coating Thickness Analyzer' => ['XRF Instruments - Coating Thickness measurement'],
        ];

        $faqCount = 0;
        foreach (($data['faqs'] ?? []) as $faqCategory => $qaList) {
            if (empty($qaList)) continue;
            $matchingSubCats = $faqMapping[$faqCategory] ?? [$faqCategory];
            
            // Find products in these subcategories
            $matchingProducts = Product::whereHas('category', function($q) use ($matchingSubCats) {
                $q->whereIn('name', $matchingSubCats);
            })->take(5)->get();

            if ($matchingProducts->isEmpty()) {
                // Fallback to active flagship products
                $matchingProducts = Product::where('is_active', true)->take(3)->get();
            }

            foreach ($matchingProducts as $mProd) {
                foreach ($qaList as $qIdx => $qa) {
                    ProductFaq::firstOrCreate(
                        [
                            'product_id' => $mProd->id,
                            'question' => $qa['question']
                        ],
                        [
                            'answer' => $qa['answer'],
                            'sort_order' => $qIdx + 1
                        ]
                    );
                    $faqCount++;
                }
            }
        }

        $this->command->info("Seeded {$faqCount} product FAQs successfully across catalog!");
    }
}
