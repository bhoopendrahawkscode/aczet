<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'image', 'translations', 'parent_id'];

    protected $casts = [
        'translations' => 'array',
    ];

    protected $appends = ['image_url'];

    public function getLocalizedName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if (!empty($this->translations[$locale]['name'])) {
            return $this->translations[$locale]['name'];
        }
        return $this->name;
    }

    public function getLocalizedDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if (!empty($this->translations[$locale]['description'])) {
            return $this->translations[$locale]['description'];
        }
        return $this->description;
    }

    public function getImageUrlAttribute()
    {
        $image = $this->image;

        if (!empty($image)) {
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '//')) {
                return $image;
            }

            $clean = ltrim($image, '/');
            if (file_exists(public_path($clean))) {
                return vasset($clean);
            }
        }

        // If category has no image in DB, dynamically query DB for an active product image
        try {
            $directProd = $this->products()
                ->where('is_active', true)
                ->whereNotNull('main_image')
                ->where('main_image', '!=', '')
                ->where('main_image', 'not like', '%noimage%')
                ->first();
            if ($directProd && !empty($directProd->main_image) && !str_contains($directProd->main_image, 'noimage')) {
                return $directProd->main_image;
            }

            // Or query subcategory products from DB
            $childIds = $this->children()->pluck('id');
            if ($childIds->isNotEmpty()) {
                $childProd = Product::whereIn('category_id', $childIds)
                    ->where('is_active', true)
                    ->whereNotNull('main_image')
                    ->where('main_image', '!=', '')
                    ->where('main_image', 'not like', '%noimage%')
                    ->first();
                if ($childProd && !empty($childProd->main_image) && !str_contains($childProd->main_image, 'noimage')) {
                    return $childProd->main_image;
                }
            }
        } catch (\Throwable $e) {}

        return vasset('images/products/noimage.jpeg');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Comparison Compatibility Group Key
     * Products from the same or similar product category can be compared.
     * Unrelated products (e.g. Balances vs Accessories like CY 224C and CPR 02) cannot be compared.
     */
    public function getComparisonGroupKey(): string
    {
        $slug = (string)($this->slug ?? '');
        $name = (string)($this->name ?? '');

        // 1. Accessories (check first so "accessories for laboratory weighing" is NEVER grouped with balances)
        if (str_contains($slug, 'accessories') || str_contains($slug, 'accessory') || stripos($name, 'accessories') !== false) {
            if (str_contains($slug, 'jewelry') || stripos($name, 'jewelry') !== false) {
                return 'accessories-jewelry';
            }
            if (str_contains($slug, 'industrial-machine') || stripos($name, 'industrial machine') !== false) {
                return 'accessories-industrial-machines';
            }
            if (str_contains($slug, 'industrial') || stripos($name, 'industrial') !== false) {
                return 'accessories-industrial-weighing';
            }
            if (str_contains($slug, 'instrument') || stripos($name, 'instrument') !== false) {
                return 'accessories-lab-instruments';
            }
            return 'accessories-laboratory-weighing';
        }

        // 2. Moisture Analyzers
        if (str_contains($slug, 'moisture-analyzer') || stripos($name, 'moisture') !== false) {
            return 'moisture-analyzers';
        }

        // 3. Test Weights & Calibration Weights
        if (str_contains($slug, 'test-weight') || str_contains($slug, 'calibration-weight') || stripos($name, 'weight') !== false) {
            return 'test-weights';
        }

        // 4. Laboratory Balances (Microbalances, Analytical, Precision, Density Scales)
        if (
            str_contains($slug, 'microbalance') || 
            str_contains($slug, 'analytical-balance') || 
            str_contains($slug, 'precision-balance') || 
            str_contains($slug, 'laboratory-balance') ||
            str_contains($slug, 'lab-balance') ||
            str_contains($slug, 'density-scale') ||
            stripos($name, 'microbalance') !== false ||
            stripos($name, 'analytical balance') !== false ||
            stripos($name, 'precision balance') !== false ||
            stripos($name, 'laboratory balance') !== false
        ) {
            if (!str_contains($slug, 'jewelry') && !str_contains($slug, 'industrial') && stripos($name, 'jewelry') === false && stripos($name, 'industrial') === false) {
                return 'laboratory-balances';
            }
        }

        // 5. Jewelry Scales & Balances
        if (str_contains($slug, 'jewelry-scale') || (str_contains($slug, 'jewelry') && str_contains($slug, 'balance')) || stripos($name, 'jewelry scale') !== false) {
            return 'jewelry-scales';
        }

        // 6. Industrial Scales
        if (
            str_contains($slug, 'industrial-scale') ||
            str_contains($slug, 'desk-scale') ||
            str_contains($slug, 'bench-platform-scale') ||
            str_contains($slug, 'floor-scale') ||
            str_contains($slug, 'crane-scale') ||
            str_contains($slug, 'medical-scale') ||
            stripos($name, 'industrial scale') !== false ||
            stripos($name, 'desk scale') !== false ||
            stripos($name, 'bench') !== false ||
            stripos($name, 'crane scale') !== false
        ) {
            return 'industrial-scales';
        }

        // 7. Industrial Weigh Modules & Load Cells
        if (str_contains($slug, 'weigh-module') || str_contains($slug, 'load-cell') || stripos($name, 'load cell') !== false) {
            return 'industrial-load-cells';
        }

        // 8. Weighbridges
        if (str_contains($slug, 'weighbridge') || stripos($name, 'weighbridge') !== false) {
            return 'industrial-weighbridges';
        }

        // 9. Product Inspection Solutions
        if (str_contains($slug, 'product-inspection') || str_contains($slug, 'check-weigher') || str_contains($slug, 'metal-detector') || stripos($name, 'inspection') !== false) {
            return 'industrial-inspection';
        }

        // 10. XRF Instruments
        if (str_contains($slug, 'xrf') || stripos($name, 'xrf') !== false) {
            return 'xrf-instruments';
        }

        // 11. Laser Machines
        if (str_contains($slug, 'laser-spot') || str_contains($slug, 'welding') || stripos($name, 'welding') !== false) {
            return 'laser-welding';
        }
        if (str_contains($slug, 'laser-engrav') || str_contains($slug, 'cutting') || stripos($name, 'engrav') !== false) {
            return 'laser-engraving';
        }

        // 12. Induction Melting
        if (str_contains($slug, 'induction-melting') || stripos($name, 'melting') !== false) {
            return 'induction-melting';
        }

        // 13. Ultrasonic Cleaners
        if (str_contains($slug, 'ultrasonic-cleaner') || stripos($name, 'cleaner') !== false) {
            return 'ultrasonic-cleaners';
        }

        // 14. Laboratory Instruments
        if (str_contains($slug, 'refractometer') || stripos($name, 'refractometer') !== false) return 'refractometers';
        if (str_contains($slug, 'polarimeter') || stripos($name, 'polarimeter') !== false) return 'polarimeters';
        if (str_contains($slug, 'colorimeter') || stripos($name, 'colorimeter') !== false) return 'colorimeters';
        if (str_contains($slug, 'milk-analyzer') || stripos($name, 'milk') !== false) return 'milk-analyzers';
        if (str_contains($slug, 'viscometer') || stripos($name, 'viscometer') !== false) return 'viscometers';
        if (str_contains($slug, 'tablet-hardness') || stripos($name, 'hardness') !== false) return 'tablet-hardness';
        if (str_contains($slug, 'probe-sonicator') || stripos($name, 'sonicator') !== false) return 'probe-sonicators';
        if (str_contains($slug, 'ice-flake') || stripos($name, 'ice flake') !== false) return 'ice-flake-makers';

        return !empty($slug) ? $slug : 'category-' . $this->id;
    }

    /**
     * Human-readable Comparison Category Title
     */
    public function getComparisonGroupName(): string
    {
        $groupKey = $this->getComparisonGroupKey();
        $names = [
            'laboratory-balances' => 'Laboratory Balances',
            'moisture-analyzers' => 'Moisture Analyzers',
            'test-weights' => 'Test Weights',
            'accessories-laboratory-weighing' => 'Accessories for Laboratory Weighing',
            'industrial-scales' => 'Industrial Scales',
            'industrial-load-cells' => 'Weigh Modules & Load Cells',
            'industrial-weighbridges' => 'Weighbridges Scales Systems',
            'industrial-inspection' => 'Product Inspection Solutions',
            'accessories-industrial-weighing' => 'Accessories for Industrial Weighing',
            'jewelry-scales' => 'Jewelry Scales & Balances',
            'xrf-instruments' => 'XRF Instruments',
            'laser-engraving' => 'Laser Engraving & Cutting',
            'laser-welding' => 'Laser Spot Welding',
            'induction-melting' => 'Induction Melting Machines',
            'ultrasonic-cleaners' => 'Ultrasonic Cleaners',
            'accessories-jewelry' => 'Accessories for Jewelry Machines',
            'accessories-industrial-machines' => 'Accessories for Industrial Machines',
            'refractometers' => 'Refractometers',
            'polarimeters' => 'Polarimeters',
            'colorimeters' => 'Visual Colorimeters',
            'milk-analyzers' => 'Ultrasonic Milk Analyzers',
            'viscometers' => 'Rotational Viscometers',
            'tablet-hardness' => 'Tablet Hardness Testers',
            'probe-sonicators' => 'Ultrasonic Probe Sonicators',
            'ice-flake-makers' => 'ICE Flake Makers',
            'accessories-lab-instruments' => 'Accessories for Laboratory Instruments',
        ];

        return $names[$groupKey] ?? ($this->name ?: 'Equipment');
    }
}

