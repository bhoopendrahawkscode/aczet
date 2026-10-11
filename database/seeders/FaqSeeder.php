<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/faqs.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("Data file {$jsonPath} not found!");
            return;
        }

        $items = json_decode(File::get($jsonPath), true);
        if (!$items) {
            $this->command->error("Failed to decode faqs.json");
            return;
        }

        // Direct explicit mapping for utmost accuracy
        $categorySlugMap = [
            'Microbalance' => 'laboratory-balances-microbalances',
            'Semi-Micro Balance' => 'laboratory-balances-microbalances',
            'Analytical Balance' => 'laboratory-balances-analytical-balances',
            'Precision Balance' => 'laboratory-balances',
            'Moisture Analyzer' => 'laboratory-balances',
            'Electronic Densimeter' => 'laboratory-balances-density-scales',
            'Calibration Test Weights' => 'laboratory-balances',
            'Jewellery and Gold Balances' => 'jewelry-machines-jewelry-scales-balances',
            'Jewellery XRF Gold Testing Machines' => 'jewelry-machines-xrf-instruments-gold-testing-machine',
            'Handheld and Industrial XRF Analyzers' => 'industrial-machines-xrf-instruments-alloy-pmi-mining-rohs',
            'Laser Marking Machines' => 'jewelry-machines-laser-engraver-cutting',
            'Laser Welding Machines' => 'jewelry-machines-laser-spot-welding-machines',
            'Laser Cutting Machines' => 'jewelry-machines-laser-engraver-cutting',
            'Industrial Weighing Scales' => 'industrial-weighing-solutions-bench-platform-scales',
            'Load Cells and Weighing Indicators' => 'industrial-weighing-solutions-weigh-modules-load-cells-systems',
            'Refractometer' => 'laboratory-instruments-refractometers',
            'Polarimeter' => 'laboratory-instruments-polarimeters',
            'Colorimeter' => 'laboratory-instruments-visual-colorimeter',
            'Probe Ultrasonicator' => 'laboratory-instruments-ultrasonic-probe-sonicator',
            'Ultrasonic Cleaner' => 'laboratory-instruments-ultrasonic-cleaners',
            'Milk Analyzer' => 'laboratory-instruments-ultrasonic-milk-analyzers',
            'Tablet Hardness Tester' => 'laboratory-instruments-tablet-hardness-tester',
            'Rotational Viscometer' => 'laboratory-instruments-rotational-viscometer',
            'Ice Flake Maker' => 'laboratory-instruments-ice-flake-maker',
            'Coating Thickness Analyzer' => 'industrial-machines-xrf-instruments-coating-thickness-measurement',
        ];

        // Cache category IDs by slug
        $categoriesBySlug = Category::all()->keyBy('slug');

        $sortTracker = [];
        $seededCount = 0;

        foreach ($items as $item) {
            $catName = $item['category'] ?? 'General';
            $groupName = $item['group'] ?? 'General';
            $question = trim($item['question']);
            $answer = trim($item['answer']);
            $intro = $item['category_intro'] ?? null;

            // Increment sort order per category
            if (!isset($sortTracker[$catName])) {
                $sortTracker[$catName] = 1;
            } else {
                $sortTracker[$catName]++;
            }

            // Resolve category ID
            $categoryId = null;
            if (isset($categorySlugMap[$catName]) && isset($categoriesBySlug[$categorySlugMap[$catName]])) {
                $categoryId = $categoriesBySlug[$categorySlugMap[$catName]]->id;
            } else {
                // Fuzzy fallback match by name
                $matched = Category::where('name', 'like', "%{$catName}%")->first();
                if ($matched) {
                    $categoryId = $matched->id;
                }
            }

            Faq::updateOrCreate(
                [
                    'question' => $question,
                    'category_name' => $catName,
                ],
                [
                    'group_name' => $groupName,
                    'category_intro' => $intro,
                    'category_id' => $categoryId,
                    'answer' => $answer,
                    'sort_order' => $sortTracker[$catName],
                    'is_active' => true,
                ]
            );

            $seededCount++;
        }

        $this->command->info("Successfully seeded {$seededCount} FAQs from Aczet Product FAQ documentation across " . count($sortTracker) . " categories!");
    }
}

