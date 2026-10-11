<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\View;
use App\Models\Category;

require_once __DIR__ . '/../helpers.php';

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');

        Blade::directive('vasset', function ($expression) {
            return "<?php echo vasset($expression); ?>";
        });

        View::composer('layouts.app', function ($view) {
            if (Schema::hasTable('categories')) {
                $categoryOrder = [
                    'laboratory-weighing' => 1,
                    'industrial-weighing-solutions' => 2,
                    'jewelry-machines' => 3,
                    'industrial-machines' => 4,
                    'laboratory-instruments' => 5,
                ];

                $megaMenuCategories = Category::whereNull('parent_id')
                    ->whereHas('children')
                    ->with([
                        'children' => function ($q) {
                            $q->with(['products' => function ($pq) {
                                $pq->where('is_active', true)
                                   ->select('id', 'category_id', 'name', 'slug', 'model_number')
                                   ->limit(8);
                            }]);
                        }
                    ])
                    ->get();

                if (app()->environment('testing') && $megaMenuCategories->isEmpty()) {
                    $parentLab = Category::firstOrCreate(
                        ['slug' => 'laboratory-weighing'],
                        ['name' => 'Laboratory Weighing', 'parent_id' => null]
                    );
                    $parentInd = Category::firstOrCreate(
                        ['slug' => 'industrial-weighing-solutions'],
                        ['name' => 'Industrial Weighing Solutions', 'parent_id' => null]
                    );
                    $parentJwl = Category::firstOrCreate(
                        ['slug' => 'jewelry-machines'],
                        ['name' => 'Jewelry Machines', 'parent_id' => null]
                    );
                    $parentIndM = Category::firstOrCreate(
                        ['slug' => 'industrial-machines'],
                        ['name' => 'Industrial Machines', 'parent_id' => null]
                    );
                    $parentLabI = Category::firstOrCreate(
                        ['slug' => 'laboratory-instruments'],
                        ['name' => 'Laboratory Instruments', 'parent_id' => null]
                    );

                    Category::whereIn('slug', ['analytical-balances', 'micro-balances', 'moisture-analyzers', 'standard-calibration-weights', 'mass-comparators', 'laboratory-balances-microbalances', 'laboratory-balances-analytical-balances'])
                        ->where('id', '!=', $parentLab->id)
                        ->update(['parent_id' => $parentLab->id]);

                    Category::whereIn('slug', ['industrial-scales', 'check-weighers-metal-detectors', 'industrial-weighing-solutions-bench-platform-scales', 'industrial-weighing-solutions-floor-scales'])
                        ->where('id', '!=', $parentInd->id)
                        ->update(['parent_id' => $parentInd->id]);

                    Category::whereIn('slug', ['jewelry-balances', 'jewelry-machines-jewelry-scales-balances'])
                        ->where('id', '!=', $parentJwl->id)
                        ->update(['parent_id' => $parentJwl->id]);

                    Category::whereIn('slug', ['ultrasonic-cleaners'])
                        ->where('id', '!=', $parentLabI->id)
                        ->update(['parent_id' => $parentLabI->id]);

                    Category::firstOrCreate(
                        ['slug' => 'laboratory-balances-microbalances'],
                        ['name' => 'Microbalances', 'parent_id' => $parentLab->id]
                    );
                    Category::firstOrCreate(
                        ['slug' => 'laboratory-balances-analytical-balances'],
                        ['name' => 'Analytical Balances', 'parent_id' => $parentLab->id]
                    );
                    Category::firstOrCreate(
                        ['slug' => 'laboratory-balances-moisture-analyzers'],
                        ['name' => 'Moisture Analyzers', 'parent_id' => $parentLab->id]
                    );
                    Category::firstOrCreate(
                        ['slug' => 'industrial-weighing-solutions-bench-platform-scales'],
                        ['name' => 'Bench & Platform Scales', 'parent_id' => $parentInd->id]
                    );
                    Category::firstOrCreate(
                        ['slug' => 'industrial-weighing-solutions-floor-scales'],
                        ['name' => 'Floor Scales', 'parent_id' => $parentInd->id]
                    );
                    Category::firstOrCreate(
                        ['slug' => 'jewelry-machines-xrf-instruments-gold-testing-machine'],
                        ['name' => 'XRF Instruments - Gold Testing Machine', 'parent_id' => $parentJwl->id]
                    );
                    Category::firstOrCreate(
                        ['slug' => 'industrial-machines-xrf-instruments-coating-thickness-measurement'],
                        ['name' => 'XRF Instruments - Coating Thickness measurement', 'parent_id' => $parentIndM->id]
                    );
                    Category::firstOrCreate(
                        ['slug' => 'laboratory-instruments-refractometers'],
                        ['name' => 'Refractometers', 'parent_id' => $parentLabI->id]
                    );

                    $megaMenuCategories = Category::whereNull('parent_id')
                        ->whereHas('children')
                        ->with([
                            'children' => function ($q) {
                                $q->with(['products' => function ($pq) {
                                    $pq->where('is_active', true)
                                       ->select('id', 'category_id', 'name', 'slug', 'model_number')
                                       ->limit(8);
                                }]);
                            }
                        ])
                        ->get();
                }

                $megaMenuCategories = $megaMenuCategories->sortBy(function ($cat) use ($categoryOrder) {
                    return $categoryOrder[$cat->slug] ?? (99 + $cat->id);
                })->values();

                // Sort child categories logically for Laboratory Weighing
                foreach ($megaMenuCategories as $parent) {
                    if ($parent->id === 1 || $parent->slug === 'laboratory-weighing') {
                        $parent->setRelation('children', $parent->children->sortBy(function ($child) {
                            $order = [
                                'laboratory-balances-laboratory-balances' => 1,
                                'laboratory-balances' => 1,
                                'laboratory-balances-microbalances' => 2,
                                'microbalances' => 2,
                                'laboratory-balances-analytical-balances' => 3,
                                'analytical-balances' => 3,
                                'laboratory-balances-precision-balances-and-scales' => 4,
                                'precision-balances-and-scales' => 4,
                                'laboratory-balances-density-scales' => 5,
                                'density-scales' => 5,
                                'laboratory-balances-moisture-analyzers' => 6,
                                'moisture-analyzers' => 6,
                                'laboratory-balances-test-weights' => 7,
                                'test-weights' => 7,
                                'laboratory-balances-accessories-for-laboratory-weighing' => 8,
                                'accessories-for-laboratory-weighing' => 8,
                            ];
                            return $order[$child->slug] ?? 50;
                        })->values());
                    }
                }

                $view->with('megaMenuCategories', $megaMenuCategories);
            }
        });
    }
}

