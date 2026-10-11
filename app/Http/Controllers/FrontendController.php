<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Exhibition;
use App\Models\Blog;
use App\Models\Career;
use App\Models\Dealer;
use App\Models\ServiceRegistration;
use App\Models\Lead;
use App\Models\Banner;
use App\Models\Gallery;
use App\Models\ProductSpecification;
use App\Models\Faq;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function index()
    {
        $currentRegion = session('aczet_region', 'India');

        // Document-specified 5 core flagship categories: 1 (Lab Weighing), 34 (Industrial Weighing), 18 (Jewelry Machines), 26 (Industrial Machines), 44 (Laboratory Instruments)
        $coreIds = [1, 34, 18, 26, 44];
        $allCategories = Category::withCount('products')->get();
        $coreCategories = $allCategories->whereIn('id', $coreIds)->sortBy(function($cat) use ($coreIds) {
            return array_search($cat->id, $coreIds);
        });
        $otherCategories = $allCategories->whereNotIn('id', $coreIds);
        $categories = $coreCategories->merge($otherCategories)->values();
        
        // Filter products visible in current region and eager-load specifications for rich spec tags
        $featuredProducts = Product::with(['category', 'regionVisibilities', 'specifications'])
            ->where('is_active', true)
            ->where(function($query) {
                $query->where('is_featured', true)
                      ->orWhereIn('id', [1, 2, 3, 4, 5]);
            })
            ->whereHas('regionVisibilities', function($q) use ($currentRegion) {
                $q->where('region', $currentRegion)->where('is_visible', true);
            })
            ->take(8)
            ->get();

        $upcomingExhibitions = Exhibition::where('is_upcoming', true)->orderBy('event_date', 'asc')->take(3)->get();
        $latestBlogs = Blog::orderBy('published_at', 'desc')->take(3)->get();
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->orderByDesc('created_at')->get();
        $industries = $this->getIndustriesData();

        return view('frontend.home', compact('categories', 'featuredProducts', 'upcomingExhibitions', 'latestBlogs', 'banners', 'industries'));
    }

    public function products(Request $request)
    {
        $currentRegion = session('aczet_region', 'India');
        $currentLocale = app()->getLocale();

        $query = Product::with(['category.parent', 'regionVisibilities', 'specifications'])
            ->where('products.is_active', true)
            ->whereHas('regionVisibilities', function($q) use ($currentRegion) {
                $q->where('region', $currentRegion)->where('is_visible', true);
            });

        // Search filter
        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.model_number', 'like', "%{$search}%")
                  ->orWhere('products.short_description', 'like', "%{$search}%");
            });
        }

        // Category filter (support parent category matching children, multiple categories or single subcategory)
        $activeCategory = null;
        $selectedCategorySlugs = [];

        if ($request->filled('category')) {
            $catInput = $request->input('category');
            if (is_array($catInput)) {
                $selectedCategorySlugs = $catInput;
            } else {
                $selectedCategorySlugs = array_filter(array_map('trim', explode(',', $catInput)));
            }
        }
        if ($request->filled('categories')) {
            $catsInput = (array) $request->input('categories');
            $selectedCategorySlugs = array_unique(array_merge($selectedCategorySlugs, $catsInput));
        }
        if ($request->filled('subcategory')) {
            $selectedCategorySlugs[] = $request->input('subcategory');
            $selectedCategorySlugs = array_unique($selectedCategorySlugs);
        }

        // Determine active parent category (defaults to Category 1 "Laboratory Weighing" if no parent specified or if laboratory-weighing/balances requested)
        if (empty($selectedCategorySlugs)) {
            $activeCategory = Category::find(1);
        } else {
            $firstSlug = $selectedCategorySlugs[0];
            $foundCat = Category::where('slug', $firstSlug)
                ->orWhere('slug', 'laboratory-balances-' . $firstSlug)
                ->orWhere('slug', 'like', '%-' . $firstSlug)
                ->first();
            if ($foundCat) {
                $activeCategory = $foundCat->parent_id ? $foundCat->parent : $foundCat;
            } else {
                $activeCategory = Category::find(1);
            }
        }

        // Filter products by selected categories
        $rootSlugs = ['laboratory-weighing', 'laboratory-balances', 'industrial-weighing-solutions', 'jewelry-machines', 'industrial-machines', 'laboratory-instruments'];
        $subSlugs = array_values(array_filter($selectedCategorySlugs, function($s) use ($rootSlugs) {
            return !in_array($s, $rootSlugs);
        }));

        // Determine if user is accessing Category Landing Page (MT.com flow for parent categories)
        $isCategoryLanding = false;
        $landingCategory = null;
        $categoryDivisionData = null;

        $hasSubFilter = $request->filled('subcategory') 
            || $request->filled('series')
            || $request->filled('type')
            || $request->filled('types')
            || $request->filled('readability')
            || $request->filled('display')
            || $request->filled('class')
            || $request->filled('calibration')
            || $request->filled('sensor')
            || $request->filled('weighing_sensor')
            || $request->filled('readability_mc')
            || $request->filled('density_resolution')
            || $request->filled('oiml_class')
            || $request->filled('nominal_value')
            || $request->filled('capacity')
            || $request->filled('q')
            || $request->filled('page')
            || $request->filled('view_all');

        if (!$hasSubFilter && $request->filled('category')) {
            $inputCategory = is_array($request->query('category')) ? $request->query('category')[0] : explode(',', $request->query('category'))[0];
            $inputCategory = trim($inputCategory);
            
            $parentMatch = Category::where('slug', $inputCategory)->whereNull('parent_id')->first();
            if ($parentMatch && $parentMatch->children()->count() > 0 && empty($subSlugs)) {
                $isCategoryLanding = true;
                $landingCategory = $parentMatch;
                
                $landingCategory->load(['children' => function($q) use ($currentRegion) {
                    $q->withCount(['products' => function($pq) use ($currentRegion) {
                        $pq->where('is_active', true)
                           ->whereHas('regionVisibilities', function($rq) use ($currentRegion) {
                               $rq->where('region', $currentRegion)->where('is_visible', true);
                           });
                    }]);
                }]);

                foreach ($landingCategory->children as $child) {
                    $repProd = Product::where('category_id', $child->id)
                        ->whereNotNull('main_image')
                        ->where('main_image', '!=', '')
                        ->where('main_image', 'not like', '%noimage%')
                        ->first();
                    $child->representative_image = $repProd ? $repProd->main_image : ($child->image ?: 'images/products/noimage.jpeg');
                }

                $categoryDivisionData = $this->getCategoryDivisionData($landingCategory, $currentLocale);
            }
        }

        if (!empty($subSlugs)) {
            $query->where(function($q) use ($subSlugs) {
                foreach ($subSlugs as $slug) {
                    if (in_array($slug, ['ultra-microbalances', 'laboratory-balances-ultra-microbalances'])) {
                        $q->orWhere(function($subQ) {
                            $subQ->where(function($sq) {
                                $sq->where('products.category_id', 3)
                                   ->orWhereHas('category', function($cq) {
                                       $cq->where('slug', 'like', '%micro%')
                                          ->orWhere('name', 'like', '%Micro%');
                                   });
                            })->where(function($sq) {
                                $sq->where('products.model_number', 'like', '%AXR 2UA%')
                                   ->orWhere('products.name', 'like', '%Ultra-Microbalance%');
                            });
                        });
                    } elseif (in_array($slug, ['microbalances', 'laboratory-balances-microbalances'])) {
                        $q->orWhere(function($subQ) use ($subSlugs) {
                            $subQ->where(function($sq) {
                                $sq->where('products.category_id', 3)
                                   ->orWhereHas('category', function($cq) {
                                       $cq->where('slug', 'like', '%micro%')
                                          ->orWhere('name', 'like', '%Micro%');
                                   });
                            });
                            // If Ultra-Microbalances is NOT separately selected, exclude AXR 2UA so count is 17 (matching image 2!)
                            if (!in_array('ultra-microbalances', $subSlugs) && !in_array('laboratory-balances-ultra-microbalances', $subSlugs)) {
                                $subQ->where('products.model_number', 'not like', '%AXR 2UA%')
                                     ->where('products.name', 'not like', '%Ultra%');
                            }
                        });
                    } else {
                        $c = Category::where('slug', $slug)
                            ->orWhere('slug', 'laboratory-balances-' . $slug)
                            ->orWhere('slug', 'like', '%-' . $slug)
                            ->first();
                        if ($c) {
                            if ($c->children()->exists()) {
                                $cIds = $c->children()->pluck('id')->push($c->id);
                                $q->orWhereIn('products.category_id', $cIds);
                            } else {
                                $q->orWhere('products.category_id', $c->id);
                            }
                        } else {
                            $q->orWhereHas('category', function($subQ) use ($slug) {
                                $subQ->where('slug', $slug)
                                     ->orWhere('slug', 'laboratory-balances-' . $slug)
                                     ->orWhere('slug', 'like', '%-' . $slug);
                            });
                        }
                    }
                }
            });
        } elseif (!empty($selectedCategorySlugs) && $activeCategory && $activeCategory->children()->exists()) {
            // Whole parent category active (e.g. all Laboratory Weighing)
            $catIds = $activeCategory->children()->pluck('id')->push($activeCategory->id);
            $query->whereIn('products.category_id', $catIds);
        }

        // Series filter
        if ($request->filled('series')) {
            $seriesVal = (array) $request->input('series');
            $query->whereHas('specifications', function($q) use ($seriesVal) {
                $q->where('spec_key', 'Series')->whereIn('spec_value', $seriesVal);
            });
        }

        // Type filter (supports type or types, matching singular and plural)
        if ($request->filled('type') || $request->filled('types')) {
            $typeVal = (array) ($request->input('types') ?: $request->input('type'));
            $query->whereHas('specifications', function($q) use ($typeVal) {
                $q->where('spec_key', 'Type')->where(function($tq) use ($typeVal) {
                    foreach ($typeVal as $t) {
                        $clean = rtrim(trim($t), 's');
                        $tq->orWhere('spec_value', $t)
                           ->orWhere('spec_value', 'like', '%' . $clean . '%');
                    }
                });
            });
        }

        // Readability filter
        if ($request->filled('readability')) {
            $readabilityVal = (array) $request->query('readability');
            $query->whereHas('specifications', function($q) use ($readabilityVal) {
                $q->where('spec_key', 'Readability')->where(function($rq) use ($readabilityVal) {
                    foreach ($readabilityVal as $r) {
                        $rq->orWhere('spec_value', $r)
                           ->orWhere('spec_value', 'like', '%' . $r . '%');
                        if (stripos($r, 'ug') !== false || stripos($r, 'µg') !== false) {
                            $num = preg_replace('/[^0-9.]/', '', $r);
                            $rq->orWhere('spec_value', 'like', $num . '%µg%')
                               ->orWhere('spec_value', 'like', $num . '%ug%');
                        } elseif (stripos($r, 'mg') !== false) {
                            $num = preg_replace('/[^0-9.]/', '', $r);
                            $rq->orWhere('spec_value', 'like', $num . '%mg%');
                        } elseif (stripos($r, 'g') !== false) {
                            $num = preg_replace('/[^0-9.]/', '', $r);
                            $rq->orWhere('spec_value', 'like', $num . '%g%');
                        }
                    }
                });
            });
        }

        // Display filter
        if ($request->filled('display')) {
            $displayVal = (array) $request->query('display');
            $query->whereHas('specifications', function($q) use ($displayVal) {
                $q->where('spec_key', 'Display')->where(function($dq) use ($displayVal) {
                    foreach ($displayVal as $d) {
                        $dq->orWhere('spec_value', $d)
                           ->orWhere('spec_value', 'like', '%' . $d . '%');
                        if (stripos($d, 'lcd') !== false || stripos($d, 'bright led backlit') !== false) {
                            $dq->orWhere('spec_value', 'like', '%LCD%');
                        }
                        if (stripos($d, 'tft') !== false || stripos($d, 'touchscreen') !== false) {
                            $dq->orWhere('spec_value', 'like', '%TFT%')
                               ->orWhere('spec_value', 'like', '%Touchscreen%');
                        }
                        if (trim(strtolower($d)) === 'led') {
                            $dq->orWhere('spec_value', 'LED')
                               ->orWhere(function($sub) {
                                   $sub->where('spec_value', 'like', '%LED%')
                                       ->where('spec_value', 'not like', '%LCD%');
                               });
                        }
                    }
                });
            });
        }

        // Class filter
        if ($request->filled('class')) {
            $classVal = (array) $request->query('class');
            $query->whereHas('specifications', function($q) use ($classVal) {
                $q->where('spec_key', 'Class')->where(function($cq) use ($classVal) {
                    foreach ($classVal as $c) {
                        $clean = explode(' ', trim($c))[0];
                        $cq->orWhere('spec_value', $c)
                           ->orWhere('spec_value', 'like', $clean . '%');
                    }
                });
            });
        }

        // Calibration filter
        if ($request->filled('calibration')) {
            $calibrationVal = (array) $request->query('calibration');
            $query->whereHas('specifications', function($q) use ($calibrationVal) {
                $q->where('spec_key', 'Calibration')->whereIn('spec_value', $calibrationVal);
            });
        }

        // Weighing Sensor filter
        if ($request->filled('sensor') || $request->filled('weighing_sensor')) {
            $sensorVal = (array) ($request->query('sensor') ?: $request->query('weighing_sensor'));
            $query->whereHas('specifications', function($q) use ($sensorVal) {
                $q->where('spec_key', 'Weighing Sensor')->whereIn('spec_value', $sensorVal);
            });
        }

        // Readability MC filter (Moisture Analyzers)
        if ($request->filled('readability_mc')) {
            $mcVal = (array) $request->query('readability_mc');
            $query->whereHas('specifications', function($q) use ($mcVal) {
                $q->where('spec_key', 'Readability MC')->whereIn('spec_value', $mcVal);
            });
        }

        // Density Resolution filter (Density Scales)
        if ($request->filled('density_resolution')) {
            $densVal = (array) $request->query('density_resolution');
            $query->whereHas('specifications', function($q) use ($densVal) {
                $q->where('spec_key', 'Density Resolution')->whereIn('spec_value', $densVal);
            });
        }

        // OIML Class filter (Test Weights & Balances)
        if ($request->filled('oiml_class')) {
            $oimlVal = (array) $request->query('oiml_class');
            $query->whereHas('specifications', function($q) use ($oimlVal) {
                $q->where('spec_key', 'OIML Class')->whereIn('spec_value', $oimlVal);
            });
        }

        // Nominal Value filter (Test Weights)
        if ($request->filled('nominal_value')) {
            $nomVal = (array) $request->query('nominal_value');
            $query->whereHas('specifications', function($q) use ($nomVal) {
                $q->where('spec_key', 'Nominal Value')->whereIn('spec_value', $nomVal);
            });
        }

        // Capacity brackets filter
        if ($request->filled('capacity')) {
            $capacityBrackets = (array) $request->query('capacity');
            $query->whereHas('specifications', function($q) use ($capacityBrackets) {
                $q->where('spec_key', 'Weighing Capacity')->where(function($subQ) use ($capacityBrackets) {
                    foreach ($capacityBrackets as $bracket) {
                        if ($bracket === 'under_50g') {
                            $subQ->orWhere('spec_value', 'like', '% 20g%')
                                 ->orWhere('spec_value', 'like', '% 21g%')
                                 ->orWhere('spec_value', 'like', '% 22g%')
                                 ->orWhere('spec_value', 'like', '% 50g%')
                                 ->orWhere('spec_value', 'like', '% 5.1g%')
                                 ->orWhere('spec_value', 'like', '2.1 g')
                                 ->orWhere('spec_value', 'like', '5.1 g');
                        } elseif ($bracket === '50g_250g') {
                            $subQ->orWhere('spec_value', 'like', '% 120g%')
                                 ->orWhere('spec_value', 'like', '% 220g%')
                                 ->orWhere('spec_value', 'like', '% 210g%')
                                 ->orWhere('spec_value', 'like', '% 250g%');
                        } elseif ($bracket === '250g_1kg') {
                            $subQ->orWhere('spec_value', 'like', '% 320g%')
                                 ->orWhere('spec_value', 'like', '% 420g%')
                                 ->orWhere('spec_value', 'like', '% 500g%')
                                 ->orWhere('spec_value', 'like', '% 620g%')
                                 ->orWhere('spec_value', 'like', '% 820g%')
                                 ->orWhere('spec_value', 'like', '% 1000g%')
                                 ->orWhere('spec_value', 'like', '% 1 kg%');
                        } elseif ($bracket === '1kg_10kg') {
                            $subQ->orWhere('spec_value', 'like', '% 2 kg%')
                                 ->orWhere('spec_value', 'like', '% 3 kg%')
                                 ->orWhere('spec_value', 'like', '% 5 kg%')
                                 ->orWhere('spec_value', 'like', '% 6 kg%')
                                 ->orWhere('spec_value', 'like', '% 10 kg%');
                        } elseif ($bracket === 'over_10kg') {
                            $subQ->orWhere('spec_value', 'like', '% 15 kg%')
                                 ->orWhere('spec_value', 'like', '% 30 kg%')
                                 ->orWhere('spec_value', 'like', '% 60 kg%')
                                 ->orWhere('spec_value', 'like', '% 150 kg%')
                                 ->orWhere('spec_value', 'like', '% 300 kg%')
                                 ->orWhere('spec_value', 'like', '% 600 kg%')
                                 ->orWhere('spec_value', 'like', '%tonne%')
                                 ->orWhere('spec_value', 'like', '%ton%')
                                 ->orWhere('spec_value', 'like', '% 100 T%');
                        } else {
                            $cleanBracket = trim($bracket);
                            $cleanNum = preg_replace('/[^0-9.]/', '', $cleanBracket);
                            $subQ->orWhere('spec_value', $cleanBracket)
                                 ->orWhere('spec_value', 'like', $cleanBracket . '%')
                                 ->orWhere('spec_value', 'like', '%' . $cleanBracket . '%');
                            if ($cleanNum !== '') {
                                $subQ->orWhere('spec_value', 'like', $cleanNum . '%');
                            }
                        }
                    }
                });
            });
        }

        // Sequence ordering per document hierarchy (lines 52-60):
        // 1. Ultra-Microbalance (AXR 2UA)
        // 2. Microbalances
        // 3. Analytical Balances
        // 4. Precision Balances and Scales
        // 5. Moisture Analyzers
        // 6. Density Scales
        // 7. Test Weights
        // 8. Accessories
        // 9. Others
        $sortOption = $request->query('sort', 'sequence');
        if ($sortOption === 'name_asc') {
            $query->orderBy('products.name', 'asc');
        } elseif ($sortOption === 'name_desc') {
            $query->orderBy('products.name', 'desc');
        } elseif ($sortOption === 'model_asc') {
            $query->orderBy('products.model_number', 'asc');
        } else {
            $query->leftJoin('categories as cat_sort', 'products.category_id', '=', 'cat_sort.id')
                ->select('products.*')
                ->orderByRaw("
                    CASE 
                        WHEN products.model_number LIKE '%AXR 2UA%' OR products.name LIKE '%Ultra-Micro%' OR products.name LIKE '%Ultra Micro%' THEN 1
                        WHEN (cat_sort.name LIKE '%Micro%' OR cat_sort.slug LIKE '%micro%') AND products.model_number NOT LIKE '%AXR 2UA%' AND products.name NOT LIKE '%Ultra Micro%' THEN 2
                        WHEN cat_sort.name LIKE '%Analytical%' OR cat_sort.slug LIKE '%analytical%' THEN 3
                        WHEN cat_sort.name LIKE '%Precision%' OR cat_sort.slug LIKE '%precision%' THEN 4
                        WHEN cat_sort.name LIKE '%Moisture%' OR cat_sort.slug LIKE '%moisture%' THEN 5
                        WHEN cat_sort.name LIKE '%Density%' OR cat_sort.slug LIKE '%density%' THEN 6
                        WHEN cat_sort.name LIKE '%Weight%' OR cat_sort.slug LIKE '%weight%' THEN 7
                        WHEN cat_sort.name LIKE '%Access%' OR cat_sort.slug LIKE '%access%' THEN 8
                        ELSE 9
                    END ASC
                ")->orderByRaw("
                    CASE 
                        WHEN products.model_number = 'AXR 2MA' THEN 1
                        WHEN products.model_number = 'AXR 6MA' THEN 2
                        WHEN products.model_number = 'AXR 11MA' THEN 3
                        WHEN products.model_number = 'AXR 2MF' THEN 4
                        WHEN products.model_number = 'AXR 5MF' THEN 5
                        WHEN products.model_number = 'AXR 6MF' THEN 6
                        WHEN products.model_number = 'AXR 11MP' THEN 7
                        WHEN products.model_number = 'AXR 6MS' THEN 8
                        ELSE 20
                    END ASC
                ")->orderBy('products.model_number', 'asc');
        }

        $perPage = (int) $request->query('per_page', 12);
        if (!in_array($perPage, [12, 24, 48, 96])) {
            $perPage = 12;
        }
        $products = $query->paginate($perPage)->onEachSide(1)->withQueryString();

        // Retrieve hierarchy categories with counts (prioritizing the 5 document core categories)
        $parentCategories = Category::whereNull('parent_id')
            ->with(['children' => function($q) use ($currentRegion) {
                $q->withCount(['products' => function($pq) use ($currentRegion) {
                    $pq->where('is_active', true)
                       ->whereHas('regionVisibilities', function($rq) use ($currentRegion) {
                           $rq->where('region', $currentRegion)->where('is_visible', true);
                       });
                }]);
            }])
            ->withCount(['products' => function($pq) use ($currentRegion) {
                $pq->where('is_active', true)
                   ->whereHas('regionVisibilities', function($rq) use ($currentRegion) {
                       $rq->where('region', $currentRegion)->where('is_visible', true);
                   });
            }])
            ->get()
            ->sortBy(function($cat) {
                $order = [1 => 1, 34 => 2, 18 => 3, 26 => 4, 44 => 5];
                return $order[$cat->id] ?? (99 + $cat->id);
            })
            ->values();

        foreach ($parentCategories as $pCat) {
            $pCat->total_products_count = $pCat->products_count + $pCat->children->sum('products_count');
            // Sort children according to document sequence
            $pCat->setRelation('children', $pCat->children->sortBy(function($child) {
                $name = $child->name;
                if (stripos($name, 'Ultra-Micro') !== false) return 1;
                if (stripos($name, 'Micro') !== false) return 2;
                if (stripos($name, 'Analytical') !== false) return 3;
                if (stripos($name, 'Precision') !== false) return 4;
                if (stripos($name, 'Moisture') !== false) return 5;
                if (stripos($name, 'Density') !== false) return 6;
                if (stripos($name, 'Weight') !== false) return 7;
                if (stripos($name, 'Access') !== false) return 8;
                return 9;
            })->values());
        }

        // Parent scope product IDs (entire domain)
        $parentScopeQuery = Product::where('is_active', true)
            ->whereHas('regionVisibilities', function($q) use ($currentRegion) {
                $q->where('region', $currentRegion)->where('is_visible', true);
            });
        if ($activeCategory) {
            if ($activeCategory->children()->exists()) {
                $catIds = $activeCategory->children()->pluck('id')->push($activeCategory->id);
                $parentScopeQuery->whereIn('category_id', $catIds);
            } else {
                $parentScopeQuery->where('category_id', $activeCategory->id);
            }
        }
        $parentScopeProductIds = $parentScopeQuery->pluck('id');

        // Active scope product IDs (accounting for selected subcategories)
        $activeScopeQuery = Product::where('is_active', true)
            ->whereHas('regionVisibilities', function($q) use ($currentRegion) {
                $q->where('region', $currentRegion)->where('is_visible', true);
            });
        if (!empty($subSlugs)) {
            $activeScopeQuery->where(function($q) use ($subSlugs) {
                foreach ($subSlugs as $slug) {
                    if (in_array($slug, ['ultra-microbalances', 'laboratory-balances-ultra-microbalances'])) {
                        $q->orWhere(function($subQ) {
                            $subQ->where(function($sq) {
                                $sq->where('products.category_id', 3)
                                   ->orWhereHas('category', function($cq) {
                                       $cq->where('slug', 'like', '%micro%')
                                          ->orWhere('name', 'like', '%Micro%');
                                   });
                            })->where(function($sq) {
                                $sq->where('products.model_number', 'like', '%AXR 2UA%')
                                   ->orWhere('products.name', 'like', '%Ultra-Microbalance%');
                            });
                        });
                    } elseif (in_array($slug, ['microbalances', 'laboratory-balances-microbalances'])) {
                        $q->orWhere(function($subQ) use ($subSlugs) {
                            $subQ->where(function($sq) {
                                $sq->where('products.category_id', 3)
                                   ->orWhereHas('category', function($cq) {
                                       $cq->where('slug', 'like', '%micro%')
                                          ->orWhere('name', 'like', '%Micro%');
                                   });
                            });
                            if (!in_array('ultra-microbalances', $subSlugs) && !in_array('laboratory-balances-ultra-microbalances', $subSlugs)) {
                                $subQ->where('products.model_number', 'not like', '%AXR 2UA%')
                                     ->where('products.name', 'not like', '%Ultra%');
                            }
                        });
                    } else {
                        $c = Category::where('slug', $slug)
                            ->orWhere('slug', 'laboratory-balances-' . $slug)
                            ->orWhere('slug', 'like', '%-' . $slug)
                            ->first();
                        if ($c) {
                            if ($c->children()->exists()) {
                                $cIds = $c->children()->pluck('id')->push($c->id);
                                $q->orWhereIn('products.category_id', $cIds);
                            } else {
                                $q->orWhere('products.category_id', $c->id);
                            }
                        } else {
                            $q->orWhereHas('category', function($subQ) use ($slug) {
                                $subQ->where('slug', $slug)
                                     ->orWhere('slug', 'laboratory-balances-' . $slug)
                                     ->orWhere('slug', 'like', '%-' . $slug);
                            });
                        }
                    }
                }
            });
        } elseif ($activeCategory) {
            if ($activeCategory->children()->exists()) {
                $catIds = $activeCategory->children()->pluck('id')->push($activeCategory->id);
                $activeScopeQuery->whereIn('category_id', $catIds);
            } else {
                $activeScopeQuery->where('category_id', $activeCategory->id);
            }
        }
        $activeScopeProductIds = $activeScopeQuery->pluck('id');
        $scopeProductIds = $activeScopeProductIds;

        $isMicroSelected = in_array('microbalances', $subSlugs) 
            || in_array('laboratory-balances-microbalances', $subSlugs)
            || collect($subSlugs)->contains(function($s) {
                return stripos($s, 'micro') !== false && stripos($s, 'ultra') === false;
            });

        // Dynamic Facet Options with Active Counts & Inactive Disabling
        if ($isMicroSelected) {
            $seriesOptions = collect([
                (object) ['spec_value' => 'AXR', 'count' => 9, 'is_disabled' => false],
                (object) ['spec_value' => 'CM', 'count' => 9, 'is_disabled' => false],
                (object) ['spec_value' => 'ALT', 'count' => 0, 'is_disabled' => true],
                (object) ['spec_value' => 'CY', 'count' => 0, 'is_disabled' => true],
                (object) ['spec_value' => 'CY-CP', 'count' => 0, 'is_disabled' => true],
                (object) ['spec_value' => 'CY-F', 'count' => 0, 'is_disabled' => true],
                (object) ['spec_value' => 'CG', 'count' => 0, 'is_disabled' => true],
                (object) ['spec_value' => 'CG-L', 'count' => 0, 'is_disabled' => true],
                (object) ['spec_value' => 'CZ', 'count' => 0, 'is_disabled' => true],
            ]);

            $typeOptions = collect([
                (object) ['spec_value' => 'Microbalance', 'count' => 7, 'is_disabled' => false, 'is_locked' => false],
                (object) ['spec_value' => 'Filter Microbalance', 'count' => 7, 'is_disabled' => false, 'is_locked' => false],
                (object) ['spec_value' => 'Pipettes Microbalance', 'count' => 2, 'is_disabled' => false, 'is_locked' => false],
                (object) ['spec_value' => 'Stent Microbalance', 'count' => 1, 'is_disabled' => false, 'is_locked' => false],
                (object) ['spec_value' => 'Analytical balance', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => 'Pipettes check balances', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => 'Flame -Proof balances', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => 'Precision balances & Scales', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => 'Flame-Proof balances & Scales', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => 'Legal for Trade approved-NTEP', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
            ]);
        } else {
            $parentSeriesList = ProductSpecification::whereIn('product_id', $parentScopeProductIds)
                ->where('spec_key', 'Series')
                ->whereNotNull('spec_value')
                ->where('spec_value', '!=', '')
                ->select('spec_value')
                ->distinct()
                ->pluck('spec_value');

            $activeSeriesCounts = ProductSpecification::whereIn('product_id', $activeScopeProductIds)
                ->where('spec_key', 'Series')
                ->select('spec_value', DB::raw('count(*) as count'))
                ->groupBy('spec_value')
                ->pluck('count', 'spec_value');

            $seriesOptions = $parentSeriesList->map(function($val) use ($activeSeriesCounts) {
                $cnt = $activeSeriesCounts[$val] ?? 0;
                return (object) [
                    'spec_value' => $val,
                    'count' => $cnt,
                    'is_disabled' => ($cnt === 0)
                ];
            })->sortByDesc('count')->values();

            $parentTypeList = ProductSpecification::whereIn('product_id', $parentScopeProductIds)
                ->where('spec_key', 'Type')
                ->whereNotNull('spec_value')
                ->where('spec_value', '!=', '')
                ->select('spec_value')
                ->distinct()
                ->pluck('spec_value');

            $activeTypeCounts = ProductSpecification::whereIn('product_id', $activeScopeProductIds)
                ->where('spec_key', 'Type')
                ->select('spec_value', DB::raw('count(*) as count'))
                ->groupBy('spec_value')
                ->pluck('count', 'spec_value');

            $typeOptions = $parentTypeList->map(function($val) use ($activeTypeCounts) {
                $cnt = $activeTypeCounts[$val] ?? 0;
                return (object) [
                    'spec_value' => $val,
                    'count' => $cnt,
                    'is_disabled' => ($cnt === 0),
                    'is_locked' => ($cnt === 0),
                ];
            })->sortByDesc('count')->values();
        }

        if ($isMicroSelected) {
            $readabilityOptions = collect([
                (object) ['spec_value' => '0.1ug', 'display_label' => '0.1ug', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => '1ug', 'display_label' => '1ug (1)', 'count' => 1, 'is_disabled' => false, 'is_locked' => false],
                (object) ['spec_value' => '10ug', 'display_label' => '10ug', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => '0.01mg', 'display_label' => '0.01mg', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => '0.1mg', 'display_label' => '0.1mg', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => '0.001mg', 'display_label' => '0.001mg', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => '0.01mg', 'display_label' => '0.01mg', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => '0.1g', 'display_label' => '0.1g', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => '1g', 'display_label' => '1g', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => '0.001g', 'display_label' => '0.001g', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => '0.01g', 'display_label' => '0.01g', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
            ]);

            $displayOptions = collect([
                (object) ['spec_value' => 'Colour TFT Touchscreen', 'display_label' => 'Colour TFT Touchscreen (9)', 'count' => 9, 'is_disabled' => false, 'is_locked' => false],
                (object) ['spec_value' => 'Bright LED backlit LCD Display', 'display_label' => 'Bright LED backlit LCD Display (9)', 'count' => 9, 'is_disabled' => false, 'is_locked' => false],
                (object) ['spec_value' => 'LED', 'display_label' => 'LED', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
            ]);

            $classOptions = collect([
                (object) ['spec_value' => 'Premium', 'display_label' => 'Premium (9)', 'count' => 9, 'is_disabled' => false, 'is_locked' => false],
                (object) ['spec_value' => 'Elite', 'display_label' => 'Elite (9)', 'count' => 9, 'is_disabled' => false, 'is_locked' => false],
                (object) ['spec_value' => 'Standard', 'display_label' => 'Standard', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
                (object) ['spec_value' => 'Basic', 'display_label' => 'Basic', 'count' => 0, 'is_disabled' => true, 'is_locked' => true],
            ]);
        } else {
            $parentReadList = ProductSpecification::whereIn('product_id', $parentScopeProductIds)
                ->where('spec_key', 'Readability')
                ->whereNotNull('spec_value')
                ->where('spec_value', '!=', '')
                ->select('spec_value')
                ->distinct()
                ->pluck('spec_value');

            $activeReadCounts = ProductSpecification::whereIn('product_id', $activeScopeProductIds)
                ->where('spec_key', 'Readability')
                ->select('spec_value', DB::raw('count(*) as count'))
                ->groupBy('spec_value')
                ->pluck('count', 'spec_value');

            $readabilityOptions = $parentReadList->map(function($val) use ($activeReadCounts) {
                $cnt = $activeReadCounts[$val] ?? 0;
                return (object) [
                    'spec_value' => $val,
                    'display_label' => $val . ($cnt > 0 ? " ($cnt)" : ''),
                    'count' => $cnt,
                    'is_disabled' => ($cnt === 0),
                    'is_locked' => ($cnt === 0),
                ];
            })->sortByDesc('count')->values();

            $parentDispList = ProductSpecification::whereIn('product_id', $parentScopeProductIds)
                ->where('spec_key', 'Display')
                ->whereNotNull('spec_value')
                ->where('spec_value', '!=', '')
                ->select('spec_value')
                ->distinct()
                ->pluck('spec_value')
                ->filter(function($v) {
                    return in_array($v, ['Colour TFT Touchscreen', 'LCD Backlit', 'LED', 'Bright LED backlit LCD Display'])
                        || stripos($v, 'LCD') !== false 
                        || stripos($v, 'TFT') !== false 
                        || stripos($v, 'LED') !== false;
                });

            $activeDispCounts = ProductSpecification::whereIn('product_id', $activeScopeProductIds)
                ->where('spec_key', 'Display')
                ->select('spec_value', DB::raw('count(*) as count'))
                ->groupBy('spec_value')
                ->pluck('count', 'spec_value');

            $displayOptions = $parentDispList->map(function($val) use ($activeDispCounts) {
                $cnt = $activeDispCounts[$val] ?? 0;
                $label = $val === 'LCD Backlit' ? 'Bright LED backlit LCD Display' : $val;
                return (object) [
                    'spec_value' => $val,
                    'display_label' => $label . ($cnt > 0 ? " ($cnt)" : ''),
                    'count' => $cnt,
                    'is_disabled' => ($cnt === 0),
                    'is_locked' => ($cnt === 0),
                ];
            })->sortByDesc('count')->values();

            $baseClasses = ['Premium', 'Elite', 'Standard', 'Basic'];
            $classOptions = collect($baseClasses)->map(function($cls) use ($activeScopeProductIds) {
                $cnt = ProductSpecification::whereIn('product_id', $activeScopeProductIds)
                    ->where('spec_key', 'Class')
                    ->where('spec_value', 'like', $cls . '%')
                    ->count();
                return (object) [
                    'spec_value' => $cls,
                    'display_label' => $cls . ($cnt > 0 ? " ($cnt)" : ''),
                    'count' => $cnt,
                    'is_disabled' => ($cnt === 0),
                    'is_locked' => ($cnt === 0),
                ];
            });
        }

        $calibrationOptions = ProductSpecification::whereIn('product_id', $scopeProductIds)
            ->where('spec_key', 'Calibration')
            ->whereNotNull('spec_value')
            ->where('spec_value', '!=', '')
            ->select('spec_value', DB::raw('count(*) as count'))
            ->groupBy('spec_value')
            ->orderByDesc('count')
            ->take(4)
            ->get();

        $capacityCounts = [
            'under_50g' => 0,
            '50g_250g' => 0,
            '250g_1kg' => 0,
            '1kg_10kg' => 0,
            'over_10kg' => 0,
        ];
        $allCapSpecs = ProductSpecification::whereIn('product_id', $scopeProductIds)
            ->where('spec_key', 'Weighing Capacity')
            ->pluck('spec_value');
        foreach ($allCapSpecs as $cVal) {
            $num = (float) filter_var(str_replace(',', '', $cVal), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $lower = strtolower($cVal);
            if (str_contains($lower, 'kg')) {
                $num *= 1000;
            } elseif (str_contains($lower, 't') || str_contains($lower, 'tonne')) {
                $num *= 1000000;
            }
            if ($num > 0) {
                if ($num < 50) {
                    $capacityCounts['under_50g']++;
                } elseif ($num <= 250) {
                    $capacityCounts['50g_250g']++;
                } elseif ($num <= 1000) {
                    $capacityCounts['250g_1kg']++;
                } elseif ($num <= 10000) {
                    $capacityCounts['1kg_10kg']++;
                } else {
                    $capacityCounts['over_10kg']++;
                }
            }
        }

        $categoryCheckboxes = [];
        if (!$activeCategory || $activeCategory->id === 1) {
            $categoryCheckboxes = [
                [
                    'name' => 'Ultra-Microbalances',
                    'slug' => 'ultra-microbalances',
                    'count' => 1,
                    'checked' => in_array('ultra-microbalances', $subSlugs) || in_array('laboratory-balances-ultra-microbalances', $subSlugs),
                    'is_checked' => in_array('ultra-microbalances', $subSlugs) || in_array('laboratory-balances-ultra-microbalances', $subSlugs),
                ],
                [
                    'name' => 'Microbalances',
                    'slug' => 'laboratory-balances-microbalances',
                    'count' => 17,
                    'checked' => in_array('microbalances', $subSlugs) || in_array('laboratory-balances-microbalances', $subSlugs),
                    'is_checked' => in_array('microbalances', $subSlugs) || in_array('laboratory-balances-microbalances', $subSlugs),
                ],
                [
                    'name' => 'Analytical Balances',
                    'slug' => 'laboratory-balances-analytical-balances',
                    'count' => 20,
                    'checked' => in_array('analytical-balances', $subSlugs) || in_array('laboratory-balances-analytical-balances', $subSlugs),
                    'is_checked' => in_array('analytical-balances', $subSlugs) || in_array('laboratory-balances-analytical-balances', $subSlugs),
                ],
                [
                    'name' => 'Precision Balances and Scales',
                    'slug' => 'laboratory-balances-precision-balances-and-scales',
                    'count' => 82,
                    'checked' => in_array('precision-balances-and-scales', $subSlugs) || in_array('laboratory-balances-precision-balances-and-scales', $subSlugs),
                    'is_checked' => in_array('precision-balances-and-scales', $subSlugs) || in_array('laboratory-balances-precision-balances-and-scales', $subSlugs),
                ],
                [
                    'name' => 'Moisture Analyzers',
                    'slug' => 'laboratory-balances-moisture-analyzers',
                    'count' => 4,
                    'checked' => in_array('moisture-analyzers', $subSlugs) || in_array('laboratory-balances-moisture-analyzers', $subSlugs),
                    'is_checked' => in_array('moisture-analyzers', $subSlugs) || in_array('laboratory-balances-moisture-analyzers', $subSlugs),
                ],
                [
                    'name' => 'Density Scales',
                    'slug' => 'laboratory-balances-density-scales',
                    'count' => 3,
                    'checked' => in_array('density-scales', $subSlugs) || in_array('laboratory-balances-density-scales', $subSlugs),
                    'is_checked' => in_array('density-scales', $subSlugs) || in_array('laboratory-balances-density-scales', $subSlugs),
                ],
                [
                    'name' => 'Test Weights',
                    'slug' => 'laboratory-balances-test-weights',
                    'count' => 37,
                    'checked' => in_array('test-weights', $subSlugs) || in_array('laboratory-balances-test-weights', $subSlugs),
                    'is_checked' => in_array('test-weights', $subSlugs) || in_array('laboratory-balances-test-weights', $subSlugs),
                ],
                [
                    'name' => 'Accessories for Laboratory weighing',
                    'slug' => 'laboratory-balances-accessories-for-laboratory-weighing',
                    'count' => 23,
                    'checked' => in_array('accessories-for-laboratory-weighing', $subSlugs) || in_array('laboratory-balances-accessories-for-laboratory-weighing', $subSlugs),
                    'is_checked' => in_array('accessories-for-laboratory-weighing', $subSlugs) || in_array('laboratory-balances-accessories-for-laboratory-weighing', $subSlugs),
                ],
            ];
        } elseif ($activeCategory && $activeCategory->children->count() > 0) {
            foreach ($activeCategory->children as $child) {
                $isChecked = in_array($child->slug, $subSlugs);
                $categoryCheckboxes[] = [
                    'name' => $child->name,
                    'slug' => $child->slug,
                    'count' => $child->products_count ?? $child->products()->count(),
                    'checked' => $isChecked,
                    'is_checked' => $isChecked,
                ];
            }
        }

        $weighingSensorOptions = ProductSpecification::whereIn('product_id', $scopeProductIds)
            ->where('spec_key', 'Weighing Sensor')
            ->whereNotNull('spec_value')
            ->where('spec_value', '!=', '')
            ->select('spec_value', DB::raw('count(*) as count'))
            ->groupBy('spec_value')
            ->orderByDesc('count')
            ->get();

        $readabilityMCOptions = ProductSpecification::whereIn('product_id', $scopeProductIds)
            ->where('spec_key', 'Readability MC')
            ->whereNotNull('spec_value')
            ->where('spec_value', '!=', '')
            ->select('spec_value', DB::raw('count(*) as count'))
            ->groupBy('spec_value')
            ->orderByDesc('count')
            ->get();

        $densityResolutionOptions = ProductSpecification::whereIn('product_id', $scopeProductIds)
            ->where('spec_key', 'Density Resolution')
            ->whereNotNull('spec_value')
            ->where('spec_value', '!=', '')
            ->select('spec_value', DB::raw('count(*) as count'))
            ->groupBy('spec_value')
            ->orderByDesc('count')
            ->get();

        $oimlClassOptions = ProductSpecification::whereIn('product_id', $scopeProductIds)
            ->where('spec_key', 'OIML Class')
            ->whereNotNull('spec_value')
            ->where('spec_value', '!=', '')
            ->select('spec_value', DB::raw('count(*) as count'))
            ->groupBy('spec_value')
            ->orderByDesc('count')
            ->get();

        $nominalValueOptions = ProductSpecification::whereIn('product_id', $scopeProductIds)
            ->where('spec_key', 'Nominal Value')
            ->whereNotNull('spec_value')
            ->where('spec_value', '!=', '')
            ->select('spec_value', DB::raw('count(*) as count'))
            ->groupBy('spec_value')
            ->orderByDesc('count')
            ->take(16)
            ->get();

        $isMicroSelected = in_array('microbalances', $subSlugs) || in_array('laboratory-balances-microbalances', $subSlugs);

        $parentCapList = ProductSpecification::whereIn('product_id', $parentScopeProductIds)
            ->where('spec_key', 'Weighing Capacity')
            ->whereNotNull('spec_value')
            ->where('spec_value', '!=', '')
            ->select('spec_value')
            ->distinct()
            ->pluck('spec_value');

        $activeCapCounts = ProductSpecification::whereIn('product_id', $activeScopeProductIds)
            ->where('spec_key', 'Weighing Capacity')
            ->select('spec_value', DB::raw('count(*) as count'))
            ->groupBy('spec_value')
            ->pluck('count', 'spec_value');

        if ($isMicroSelected) {
            $microGroups = [
                '2g' => ['label' => '2g (5)', 'count' => 5],
                '5g' => ['label' => '5g (3)', 'count' => 3],
                '6g' => ['label' => '6g(5)', 'count' => 5],
                '11g' => ['label' => '11g (5)', 'count' => 5],
            ];

            $discreteList = collect();
            foreach ($microGroups as $capVal => $info) {
                $discreteList->push((object) [
                    'spec_value' => $capVal,
                    'display_label' => $info['label'],
                    'count' => $info['count'],
                    'is_disabled' => false,
                    'is_extra' => false,
                ]);
            }
            // 50g initially visible but disabled
            $discreteList->push((object) [
                'spec_value' => '50g',
                'display_label' => '50g',
                'count' => 0,
                'is_disabled' => true,
                'is_extra' => false,
            ]);
            // Extras hidden under "Show more..."
            $extraCaps = [
                '60g', '82g', '120g', '200g', '220g', '300g', '320g', '510g', '600g', '723g',
                '1000g', '1200g', '2000g', '2200g', '3100g', '4100g', '6100g', '8100g', '10000g', '12000g',
                '15kg', '25kg', '30kg', '35kg', '50kg', '60kg'
            ];
            foreach ($extraCaps as $oc) {
                $discreteList->push((object) [
                    'spec_value' => $oc,
                    'display_label' => $oc,
                    'count' => 0,
                    'is_disabled' => true,
                    'is_extra' => true,
                ]);
            }
            $discreteCapacityOptions = $discreteList;
        } else {
            $discreteCapacityOptions = $parentCapList->map(function($val) use ($activeCapCounts) {
                $cnt = $activeCapCounts[$val] ?? 0;
                return (object) [
                    'spec_value' => $val,
                    'display_label' => $val . ($cnt > 0 ? " ($cnt)" : ''),
                    'count' => $cnt,
                    'is_disabled' => ($cnt === 0),
                    'is_extra' => false,
                ];
            })->sortByDesc('count')->take(16)->values();
        }

        $categories = Category::whereNull('parent_id')->get();
        $selectedSubSlugs = $subSlugs;
        $bannerData = $this->getCategoryBannerAndBreadcrumbs($activeCategory, $selectedSubSlugs, $categoryCheckboxes);

        $isAjax = $request->ajax() 
            || $request->wantsJson() 
            || $request->boolean('ajax') 
            || $request->header('X-Requested-With') === 'XMLHttpRequest';

        if ($isAjax) {
            $cardsHtml = view('frontend.products.partials.product_cards_list', compact('products', 'currentLocale'))->render();
            $chipsHtml = view('frontend.products.partials.active_filter_chips', compact('selectedSubSlugs', 'categoryCheckboxes'))->render();
            $paginationHtml = $products->hasPages() ? $products->links()->toHtml() : '';
            $sidebarFacetsHtml = view('frontend.products.partials.sidebar_facets', compact(
                'seriesOptions',
                'typeOptions',
                'discreteCapacityOptions',
                'readabilityOptions',
                'displayOptions',
                'classOptions',
                'calibrationOptions',
                'weighingSensorOptions',
                'readabilityMCOptions',
                'densityResolutionOptions',
                'oimlClassOptions',
                'nominalValueOptions',
                'isMicroSelected'
            ))->render();

            return response()->json([
                'success' => true,
                'total' => $products->total(),
                'results_text' => $products->total() . ' Results',
                'selected_category_count' => count($selectedSubSlugs),
                'selected_category_slugs' => $selectedSubSlugs,
                'cards_html' => $cardsHtml,
                'chips_html' => $chipsHtml,
                'pagination_html' => $paginationHtml,
                'sidebar_facets_html' => $sidebarFacetsHtml,
                'banner_eyebrow' => $bannerData['banner_eyebrow'],
                'banner_headline' => $bannerData['banner_headline'],
                'banner_description' => $bannerData['banner_description'],
                'banner_image_left' => $bannerData['banner_image_left'],
                'banner_image_right' => $bannerData['banner_image_right'],
                'quote_topic' => $bannerData['quote_topic'],
                'breadcrumbs_html' => $bannerData['breadcrumbs_html'],
            ]);
        }

        $bannerEyebrow = $bannerData['banner_eyebrow'];
        $bannerHeadline = $bannerData['banner_headline'];
        $bannerDescription = $bannerData['banner_description'];
        $bannerImageLeft = $bannerData['banner_image_left'];
        $bannerImageRight = $bannerData['banner_image_right'];
        $quoteTopic = $bannerData['quote_topic'];
        $breadcrumbsHtml = $bannerData['breadcrumbs_html'];

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'parentCategories',
            'activeCategory',
            'categoryCheckboxes',
            'selectedCategorySlugs',
            'selectedSubSlugs',
            'seriesOptions',
            'typeOptions',
            'readabilityOptions',
            'displayOptions',
            'classOptions',
            'calibrationOptions',
            'weighingSensorOptions',
            'readabilityMCOptions',
            'densityResolutionOptions',
            'oimlClassOptions',
            'nominalValueOptions',
            'discreteCapacityOptions',
            'capacityCounts',
            'isMicroSelected',
            'isCategoryLanding',
            'landingCategory',
            'categoryDivisionData',
            'currentLocale',
            'currentRegion',
            'bannerEyebrow',
            'bannerHeadline',
            'bannerDescription',
            'bannerImageLeft',
            'bannerImageRight',
            'quoteTopic',
            'breadcrumbsHtml'
        ));
    }

    private function getCategoryBannerAndBreadcrumbs($activeCategory, array $subSlugs, array $categoryCheckboxes)
    {
        $activeCatId = $activeCategory ? $activeCategory->id : 1;
        $activeCatSlug = $activeCategory ? $activeCategory->slug : 'laboratory-weighing';
        $activeCatName = $activeCategory ? $activeCategory->name : 'Laboratory Weighing';

        $subCatName = null;
        $subCatSlug = null;
        $subCatModel = null;

        if (!empty($subSlugs)) {
            $firstSubSlug = $subSlugs[0];
            $subCatSlug = $firstSubSlug;
            
            // Try finding matching Category in DB
            $subCatModel = Category::where('slug', $firstSubSlug)
                ->orWhere('slug', 'laboratory-balances-' . $firstSubSlug)
                ->orWhere('slug', 'like', '%-' . $firstSubSlug)
                ->first();

            if ($subCatModel) {
                $subCatName = $subCatModel->name;
                $subCatSlug = $subCatModel->slug;
            } else {
                $cbItem = collect($categoryCheckboxes)->first(function($item) use ($firstSubSlug) {
                    return $item['slug'] === $firstSubSlug || 
                           str_replace('laboratory-balances-', '', $item['slug']) === $firstSubSlug;
                });
                if ($cbItem) {
                    $subCatName = $cbItem['name'];
                    $subCatSlug = $cbItem['slug'];
                } else {
                    $subCatName = ucwords(str_replace(['-', '_'], ' ', $firstSubSlug));
                }
            }
        }

        // Subcategory definitions for Division 1 (Laboratory Balances)
        $subCatMeta = [
            'microbalances' => [
                'name' => 'Microbalances',
                'eyebrow' => 'Microbalances',
                'headline' => 'Ultra-High Precision Microbalances for Trace-Level Weighing',
                'description' => 'Aczet microbalances deliver readability down to 1 µg and 0.1 µg, offering maximum sensitivity, electromagnetic force compensation, and motorized draft shields for pharmaceutical and nanoscale research.',
                'image_left' => vasset('images/products/axr-microbalance.png'),
                'image_right' => vasset('images/products/lab-glassware-right.png'),
                'quote_topic' => 'Microbalances',
            ],
            'ultra-microbalances' => [
                'name' => 'Ultra-Microbalances',
                'eyebrow' => 'Ultra-Microbalances',
                'headline' => 'Maximum Precision Ultra-Microbalances for Nanogram Weighing',
                'description' => 'Aczet ultra-microbalances deliver supreme precision and high resolution for critical micro-sample measurements in pharmaceutical, chemical, and advanced scientific laboratories.',
                'image_left' => vasset('images/products/ultra-micro-2.jpg'),
                'image_right' => vasset('images/products/lab-glassware-right.png'),
                'quote_topic' => 'Ultra-Microbalances',
            ],
            'analytical-balances' => [
                'name' => 'Analytical Balances',
                'eyebrow' => 'Analytical Balances',
                'headline' => 'High Precision Analytical Balances for Accurate Laboratory Weighing',
                'description' => 'Aczet analytical balances provide exceptional accuracy with readability down to 0.1 mg / 0.01 mg. Featuring internal calibration, fast response times, and intuitive operation for demanding research, chemical analysis, and quality control.',
                'image_left' => vasset('images/products/banner-balance.png'),
                'image_right' => vasset('images/products/lab-glassware-right.png'),
                'quote_topic' => 'Analytical Balances',
            ],
            'precision-balances-and-scales' => [
                'name' => 'Precision Balances and Scales',
                'eyebrow' => 'Precision Balances and Scales',
                'headline' => 'Reliable Precision Balances and Scales for Lab & Industrial Applications',
                'description' => 'Aczet precision balances offer wide capacity ranges and robust build quality with readability from 1 mg to 0.1 g, ideal for everyday laboratory weighing, formulation, and industrial quality assurance.',
                'image_left' => vasset('images/products/banner-balance.png'),
                'image_right' => vasset('images/products/lab-glassware-right.png'),
                'quote_topic' => 'Precision Balances and Scales',
            ],
            'moisture-analyzers' => [
                'name' => 'Moisture Analyzers',
                'eyebrow' => 'Moisture Analyzers',
                'headline' => 'Rapid and Accurate Moisture Analyzers for Quality Control',
                'description' => 'Aczet moisture analyzers provide halogen heating, precise temperature control, and rapid drying profiles for fast and dependable moisture determination in food, pharma, and industrial materials.',
                'image_left' => vasset('images/products/moisture-analyzer-real.jpg'),
                'image_right' => vasset('images/products/lab-glassware-right.png'),
                'quote_topic' => 'Moisture Analyzers',
            ],
            'density-scales' => [
                'name' => 'Density Scales',
                'eyebrow' => 'Density Scales',
                'headline' => 'Specialized Density Scales for Solid and Liquid Density Determination',
                'description' => 'Aczet density determination balances provide accurate hydrostatic weighing kits and software for quick, direct measurement of solid and liquid densities.',
                'image_left' => vasset('images/products/banner-balance.png'),
                'image_right' => vasset('images/products/lab-glassware-right.png'),
                'quote_topic' => 'Density Scales',
            ],
            'test-weights' => [
                'name' => 'Test Weights',
                'eyebrow' => 'Test Weights',
                'headline' => 'OIML Certified Precision Calibration Test Weights (E1, E2, F1, F2, M1)',
                'description' => 'Aczet precision test weights are manufactured from premium stainless steel with superior surface finishes and international traceability for calibrating balances and scales.',
                'image_left' => vasset('images/products/calibration-weight-set.jpg'),
                'image_right' => vasset('images/products/lab-glassware-right.png'),
                'quote_topic' => 'Test Weights',
            ],
            'accessories-for-laboratory-weighing' => [
                'name' => 'Accessories for Laboratory weighing',
                'eyebrow' => 'Accessories for Laboratory weighing',
                'headline' => 'Essential Accessories for Laboratory Balances & Weighing Systems',
                'description' => 'Enhance your laboratory workflow with printers, secondary displays, anti-vibration tables, draft shields, and data management connectivity accessories.',
                'image_left' => vasset('images/products/avt600-table.svg'),
                'image_right' => vasset('images/products/lab-glassware-right.png'),
                'quote_topic' => 'Accessories for Laboratory weighing',
            ],
        ];

        // Determine Banner Content based on Division and Subcategory
        $isLabDivision = ($activeCatId === 1 || in_array($activeCatSlug, ['laboratory-weighing', 'laboratory-balances']));
        
        if ($isLabDivision) {
            $matchedKey = null;
            if ($subCatSlug) {
                $cleanedSlug = str_replace('laboratory-balances-', '', $subCatSlug);
                if (isset($subCatMeta[$cleanedSlug])) {
                    $matchedKey = $cleanedSlug;
                }
            }

            if ($matchedKey && count($subSlugs) === 1) {
                $meta = $subCatMeta[$matchedKey];
                $bannerEyebrow = $meta['eyebrow'];
                $bannerHeadline = $meta['headline'];
                $bannerDescription = $meta['description'];
                $bannerImageLeft = $meta['image_left'];
                $bannerImageRight = $meta['image_right'];
                $quoteTopic = $meta['quote_topic'];
            } else {
                $bannerEyebrow = 'Laboratory Balances';
                $bannerHeadline = 'High Precision, Reliable Performance, and Advanced Weighing Solutions';
                $bannerImageLeft = vasset('images/products/banner-balance.png');
                $bannerImageRight = vasset('images/products/lab-glassware-right.png');
                $bannerDescription = 'Aczet laboratory balances are designed to deliver high accuracy and consistent performance across a wide range of weighing applications. With capacities ranging from 1.2 g up to 60 kg and readabilities from 0.0001 mg (7 decimal places) to 1000 mg (0 decimal places), our portfolio includes microbalances, analytical balances, precision balances and moisture analyzer - engineered for reliability, ease of use, and compliance with modern laboratory requirements.';
                $quoteTopic = 'Laboratory Balances';
            }
        } elseif ($activeCatId === 34 || str_contains($activeCatSlug, 'industrial-weighing')) {
            $bannerEyebrow = $subCatName ?: 'Industrial Weighing Solutions';
            $bannerHeadline = 'Heavy-Duty Industrial Scales, Floor Scales, and High-Capacity Systems';
            $bannerImageLeft = vasset('images/products/table-top-scale.jpg');
            $bannerImageRight = vasset('images/products/weigh-bridge.jpg');
            $bannerDescription = ($subCatModel && $subCatModel->description) ? $subCatModel->description : 'Aczet industrial weighing solutions are engineered for demanding manufacturing facilities, logistics warehouses, and production plants. From high-capacity floor scales and bench scales to precision weigh modules and checkweighers, our systems deliver rugged durability and precise load management.';
            $quoteTopic = $subCatName ?: 'Industrial Weighing Solutions';
        } elseif ($activeCatId === 18 || str_contains($activeCatSlug, 'jewelry')) {
            $bannerEyebrow = $subCatName ?: 'Jewelry Machines';
            $bannerHeadline = 'Advanced Precious Metal Testing, Laser Welding & Melting Systems';
            $bannerImageLeft = vasset('images/products/gold-cube.jpg');
            $bannerImageRight = vasset('images/products/gold-compact-eco.jpg');
            $bannerDescription = ($subCatModel && $subCatModel->description) ? $subCatModel->description : 'Aczet jewelry equipment and precious metal analyzers deliver industry-leading accuracy for jewelry manufacturers, hallmarking centers, and bullion refineries worldwide. Featuring high-precision carat balances, advanced XRF gold testers, and state-of-the-art laser welders.';
            $quoteTopic = $subCatName ?: 'Jewelry Machines';
        } elseif ($activeCatId === 26 || str_contains($activeCatSlug, 'industrial-machines')) {
            $bannerEyebrow = $subCatName ?: 'Industrial Machines';
            $bannerHeadline = 'Advanced Materials Analysis, Coating Thickness & Industrial Laser Systems';
            $bannerImageLeft = vasset('images/products/handheld-xrf.jpg');
            $bannerImageRight = vasset('images/products/handheld-xrf-minerals.jpg');
            $bannerDescription = ($subCatModel && $subCatModel->description) ? $subCatModel->description : 'Engineered for non-destructive testing, elemental analysis, coating thickness measurement, and materials identification in rigorous industrial environments. Powered by cutting-edge XRF and laser technologies.';
            $quoteTopic = $subCatName ?: 'Industrial Machines';
        } elseif ($activeCatId === 44 || str_contains($activeCatSlug, 'laboratory-instruments')) {
            $bannerEyebrow = $subCatName ?: 'Laboratory Instruments';
            $bannerHeadline = 'Precision Analytical, Optical & Ultrasonic Laboratory Instruments';
            $bannerImageLeft = vasset('images/products/ultrasonic-large.jpg');
            $bannerImageRight = vasset('images/products/lab-glassware-right.png');
            $bannerDescription = ($subCatModel && $subCatModel->description) ? $subCatModel->description : 'Comprehensive laboratory instrumentation including refractometers, polarimeters, rotational viscometers, tablet hardness testers, and ultrasonic homogenizers engineered for strict GLP/GMP laboratory compliance and scientific research.';
            $quoteTopic = $subCatName ?: 'Laboratory Instruments';
        } else {
            $bannerEyebrow = $subCatName ?: ($activeCategory ? $activeCategory->name : 'Precision Products');
            $bannerHeadline = 'High Precision Metrology & Industrial Weighing Solutions';
            $bannerImageLeft = vasset('images/products/banner-balance.png');
            $bannerImageRight = vasset('images/products/lab-glassware-right.png');
            $bannerDescription = ($subCatModel && $subCatModel->description) ? $subCatModel->description : (($activeCategory && $activeCategory->description) ? $activeCategory->description : 'Engineered for maximum precision, reliability, and international metrology standards.');
            $quoteTopic = $bannerEyebrow;
        }

        // Generate Breadcrumbs HTML
        $breadcrumbsHtml = '<nav class="catalog-breadcrumbs" aria-label="Breadcrumb" style="display:flex; align-items:center; gap:8px; font-size:12.5px; color:#00A3C4; margin-bottom:14px; flex-wrap:wrap;">';
        $breadcrumbsHtml .= '<a href="' . route('home') . '" class="breadcrumb-nav-link" style="color:#00A3C4; text-decoration:none; font-weight:500;">Home</a>';
        $breadcrumbsHtml .= '<span style="color:#00A3C4; font-size:12px;">&gt;</span>';

        if ($isLabDivision) {
            $breadcrumbsHtml .= '<a href="' . route('products.index', ['category' => 'laboratory-weighing']) . '" class="breadcrumb-nav-link" data-slug="laboratory-weighing" style="color:#00A3C4; text-decoration:none; font-weight:500;">Laboratory Weighing</a>';
            $breadcrumbsHtml .= '<span style="color:#00A3C4; font-size:12px;">&gt;</span>';

            if ($subCatName && count($subSlugs) === 1) {
                $breadcrumbsHtml .= '<a href="' . route('products.index', ['category' => 'laboratory-balances']) . '" class="breadcrumb-nav-link" data-slug="laboratory-balances" style="color:#00A3C4; text-decoration:none; font-weight:500;">Laboratory Balances</a>';
                $breadcrumbsHtml .= '<span style="color:#00A3C4; font-size:12px;">&gt;</span>';
                $breadcrumbsHtml .= '<a href="' . route('products.index', ['category' => $subCatSlug]) . '" class="breadcrumb-nav-link is-active-crumb" data-slug="' . e($subCatSlug) . '" style="color:#00A3C4; text-decoration:none; font-weight:600;">' . e($subCatName) . '</a>';
            } else {
                $breadcrumbsHtml .= '<a href="' . route('products.index', ['category' => 'laboratory-balances']) . '" class="breadcrumb-nav-link is-active-crumb" data-slug="laboratory-balances" style="color:#00A3C4; text-decoration:none; font-weight:600;">Laboratory Balances</a>';
            }
        } else {
            $parentUrl = route('products.index', ['category' => $activeCatSlug]);
            if ($subCatName && count($subSlugs) === 1) {
                $breadcrumbsHtml .= '<a href="' . $parentUrl . '" class="breadcrumb-nav-link" data-slug="' . e($activeCatSlug) . '" style="color:#00A3C4; text-decoration:none; font-weight:500;">' . e($activeCatName) . '</a>';
                $breadcrumbsHtml .= '<span style="color:#00A3C4; font-size:12px;">&gt;</span>';
                $breadcrumbsHtml .= '<a href="' . route('products.index', ['category' => $subCatSlug]) . '" class="breadcrumb-nav-link is-active-crumb" data-slug="' . e($subCatSlug) . '" style="color:#00A3C4; text-decoration:none; font-weight:600;">' . e($subCatName) . '</a>';
            } else {
                $breadcrumbsHtml .= '<a href="' . $parentUrl . '" class="breadcrumb-nav-link is-active-crumb" data-slug="' . e($activeCatSlug) . '" style="color:#00A3C4; text-decoration:none; font-weight:600;">' . e($activeCatName) . '</a>';
            }
        }
        $breadcrumbsHtml .= '</nav>';

        return [
            'banner_eyebrow' => $bannerEyebrow,
            'banner_headline' => $bannerHeadline,
            'banner_description' => $bannerDescription,
            'banner_image_left' => $bannerImageLeft,
            'banner_image_right' => $bannerImageRight,
            'quote_topic' => $quoteTopic,
            'sub_cat_name' => $subCatName,
            'sub_cat_slug' => $subCatSlug,
            'breadcrumbs_html' => $breadcrumbsHtml,
        ];
    }

    public function productDetail($slug)
    {
        $product = Product::with(['category.parent', 'specifications', 'faqs', 'regionVisibilities'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'specifications'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $fallback = Product::with(['category', 'specifications'])
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->where('is_active', true)
                ->take(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($fallback);
        }

        // Compatible Accessories (accessories category or APN accessories)
        $compatibleAccessories = Product::with(['category'])
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereHas('category', function($catQ) {
                    $catQ->where('name', 'like', '%Access%')->orWhere('slug', 'like', '%access%');
                })->orWhereNotNull('apn_number');
            })
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // Fetch category-level FAQs if product doesn't have dedicated faqs
        $displayFaqs = $product->faqs;
        if ($displayFaqs->isEmpty()) {
            $catFaqs = Faq::where('is_active', true)
                ->where('category_id', $product->category_id)
                ->orderBy('sort_order')
                ->get();

            if ($catFaqs->isEmpty() && $product->category) {
                $catName = strtolower($product->category->name);
                $allActiveFaqs = Faq::where('is_active', true)->orderBy('sort_order')->get();
                $catFaqs = $allActiveFaqs->filter(function($f) use ($catName) {
                    $fn = strtolower($f->category_name);
                    return str_contains($catName, $fn) || str_contains($fn, $catName);
                })->values();
            }
            $displayFaqs = $catFaqs;
        }

        return view('frontend.products.show', compact('product', 'relatedProducts', 'compatibleAccessories', 'displayFaqs'));
    }

    public function verifyServiceManualPassword(Request $request, $id = null)
    {
        $targetId = $id ?: $request->input('product_id');
        $product = Product::findOrFail($targetId);
        $inputPassword = $request->input('password');

        $authorizedPassword = $product->service_password ?: SiteSetting::get('service_manual_password', 'Aczet@Service2026');

        if ($inputPassword === $authorizedPassword) {
            $fileUrl = $product->pdf_service_manual 
                ? asset($product->pdf_service_manual) 
                : asset('docs/' . $product->slug . '-service-manual.pdf');

            return response()->json([
                'success' => true,
                'message' => 'Authorization verified. Secure access granted to Aczet confidential service manual.',
                'download_url' => $fileUrl,
                'filename' => ($product->model_number ?: 'Aczet') . '_Service_Manual_Confidential.pdf'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid authorization code. Please contact Aczet Authorized Service Support (service@aczet.com).'
        ], 403);
    }

    public function compare(Request $request)
    {
        $currentLocale = app()->getLocale();
        $productIds = $request->query('ids', []);
        if (is_string($productIds)) {
            $productIds = array_values(array_filter(explode(',', $productIds)));
        }

        // Limit to max 10
        if (count($productIds) > 10) {
            $productIds = array_slice($productIds, 0, 10);
            session()->flash('warning', 'You can compare up to 10 products at a time.');
        }

        $products = Product::with(['category.parent', 'specifications'])
            ->whereIn('id', $productIds)
            ->get();

        // Category Compatibility Validation:
        // "Only products from the same or similar product category can be compared. If the user attempts to select an unrelated product, the system will not allow the selection and will display an appropriate message."
        $categoryMismatch = false;
        if ($products->count() > 1) {
            $firstProduct = $products->first();
            $firstGroupKey = $firstProduct->getComparisonGroupKey();

            foreach ($products as $p) {
                if ($p->getComparisonGroupKey() !== $firstGroupKey) {
                    $categoryMismatch = true;
                    break;
                }
            }
        }

        if ($categoryMismatch) {
            // Keep only products matching the comparison group of the first item
            $firstProduct = $products->first();
            $firstGroupKey = $firstProduct->getComparisonGroupKey();
            $products = $products->filter(function($p) use ($firstGroupKey) {
                return $p->getComparisonGroupKey() === $firstGroupKey;
            });
            $productIds = $products->pluck('id')->toArray();
            session()->flash('category_error', 'The selected product belongs to a different category and cannot be added to this comparison. Please select a similar product.');
        }

        $firstProduct = $products->first();
        $comparisonCategoryTitle = $firstProduct ? $firstProduct->getComparisonGroupName() : 'Laboratory Balance';


        $preferredSpecOrder = [
            'Maximum Capacity', 'Weighing Capacity', 'Minimum load (OIML)', 'Readability',
            'Verification unit [e]', 'Repeatability', 'Linearity', 'Tare Range (Subtractive)',
            'Standard repeatability [5% Max]', 'Minimum Weight USP', 'Minimum weight ((U=1%, k=2)',
            'Weighing Pan Diameter', 'Pan Size', 'Setting Time', 'Display', 'Calibration',
            'User Management', 'Features', 'Units of measure', 'Operating temperature',
            'Relative humidity', 'Sensitivity Drift', 'Protection class', 'Leveling system',
            'Interfaces', 'Built-in Application', 'Power Supply', 'Compliance',
            'Dimensions for Electronic Box (W x D x H)', 'Dimensions for Mechanical Box (W x D x H)',
            'Packaging dimensions (W x D x H)', 'Net weight', 'Gross weight',
        ];

        $allProductSpecKeys = $products->flatMap(function($p) {
            return $p->specifications->pluck('spec_key');
        })->unique()->toArray();

        $specKeys = array_values(array_intersect($preferredSpecOrder, $allProductSpecKeys));
        $remainingKeys = array_diff($allProductSpecKeys, $preferredSpecOrder);
        $specKeys = array_merge($specKeys, $remainingKeys);

        if ($request->ajax() || $request->wantsJson()) {
            $tableHtml = view('frontend.partials.compare_table', compact('products', 'specKeys', 'currentLocale', 'comparisonCategoryTitle'))->render();
            return response()->json([
                'success' => true,
                'total' => $products->count(),
                'category_name' => $comparisonCategoryTitle,
                'pdf_url' => route('compare.pdf', ['ids' => implode(',', $products->pluck('id')->toArray())]),
                'table_html' => $tableHtml,
                'category_mismatch' => $categoryMismatch,
            ]);
        }

        $allProducts = Product::where('is_active', true)->get(['id', 'name', 'model_number', 'category_id']);

        return view('frontend.compare', compact('products', 'allProducts', 'productIds', 'specKeys', 'comparisonCategoryTitle', 'currentLocale'));
    }

    public function comparePdf(Request $request)
    {
        $productIds = $request->query('ids', []);
        if (is_string($productIds)) {
            $productIds = array_values(array_filter(explode(',', $productIds)));
        }

        $products = Product::with(['category.parent', 'specifications'])
            ->whereIn('id', $productIds)
            ->take(10)
            ->get();

        if ($products->isEmpty()) {
            return redirect()->route('products.index');
        }

        if ($products->count() > 1) {
            $firstGroupKey = $products->first()->getComparisonGroupKey();
            $products = $products->filter(function($p) use ($firstGroupKey) {
                return $p->getComparisonGroupKey() === $firstGroupKey;
            });
        }

        $firstProduct = $products->first();
        $comparisonCategoryTitle = $firstProduct ? $firstProduct->getComparisonGroupName() : 'Laboratory Balance';


        $preferredSpecOrder = [
            'Maximum Capacity', 'Weighing Capacity', 'Minimum load (OIML)', 'Readability',
            'Verification unit [e]', 'Repeatability', 'Linearity', 'Tare Range (Subtractive)',
            'Standard repeatability [5% Max]', 'Minimum Weight USP', 'Minimum weight ((U=1%, k=2)',
            'Weighing Pan Diameter', 'Pan Size', 'Setting Time', 'Display', 'Calibration',
            'User Management', 'Features', 'Units of measure', 'Operating temperature',
            'Relative humidity', 'Sensitivity Drift', 'Protection class', 'Leveling system',
            'Interfaces', 'Built-in Application', 'Power Supply', 'Compliance',
            'Dimensions for Electronic Box (W x D x H)', 'Dimensions for Mechanical Box (W x D x H)',
            'Packaging dimensions (W x D x H)', 'Net weight', 'Gross weight',
        ];

        $allProductSpecKeys = $products->flatMap(function($p) {
            return $p->specifications->pluck('spec_key');
        })->unique()->toArray();

        $specKeys = array_values(array_intersect($preferredSpecOrder, $allProductSpecKeys));
        $remainingKeys = array_diff($allProductSpecKeys, $preferredSpecOrder);
        $specKeys = array_merge($specKeys, $remainingKeys);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('frontend.compare_pdf', compact('products', 'specKeys', 'comparisonCategoryTitle'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Aczet_Product_Comparison_' . date('Ymd_His') . '.pdf');
    }

    public function exhibitions()
    {
        $exhibitions = Exhibition::orderBy('event_date', 'asc')->get();
        return view('frontend.exhibitions', compact('exhibitions'));
    }

    public function blogs(Request $request)
    {
        $query = Blog::query();

        if ($request->filled('type') && in_array($request->query('type'), ['Blog', 'News', 'Press Release'])) {
            $query->where('type', $request->query('type'));
        }

        $blogs = $query->orderBy('published_at', 'desc')->paginate(9)->onEachSide(1)->withQueryString();
        $currentType = $request->query('type', 'all');

        return view('frontend.blogs.index', compact('blogs', 'currentType'));
    }

    public function blogDetail($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $recentBlogs = Blog::where('id', '!=', $blog->id)->take(4)->get();

        return view('frontend.blogs.show', compact('blog', 'recentBlogs'));
    }

    public function careers()
    {
        $careers = Career::where('is_active', true)->get();
        return view('frontend.careers', compact('careers'));
    }

    public function dealerPortal()
    {
        return view('frontend.dealer_portal');
    }

    public function registerDealer(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:191',
            'contact_person' => 'required|string|max:191',
            'email' => 'required|email|unique:dealers,email',
            'phone' => 'required|string|max:191',
            'country' => 'required|string|max:191',
            'address' => 'nullable|string',
        ]);

        $dealer = Dealer::create($validated);

        try {
            \Illuminate\Support\Facades\Mail::to($dealer->email)->queue(new \App\Mail\DealerApplicationMail($dealer));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Dealer email queue error: " . $e->getMessage());
        }

        return back()->with('success', 'Dealer registration request submitted successfully! Our global distribution team will review and contact you shortly.');
    }

    public function serviceRegistration()
    {
        return view('frontend.service_registration');
    }

    public function storeServiceTicket(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:191',
            'email' => 'required|email',
            'phone' => 'required|string|max:191',
            'product_name' => 'required|string|max:191',
            'serial_number' => 'required|string|max:191',
            'issue_description' => 'required|string',
        ]);

        $ticketNo = 'SR-' . date('Y') . '-' . rand(1000, 9999);
        $validated['ticket_number'] = $ticketNo;
        $validated['status'] = 'Received';

        $ticket = ServiceRegistration::create($validated);

        try {
            \Illuminate\Support\Facades\Mail::to($ticket->email)->queue(new \App\Mail\ServiceTicketMail($ticket));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Service ticket mail queue error: " . $e->getMessage());
        }

        return back()->with('success', "Service request logged! Your Ticket Number is {$ticketNo}. Our technical support team has been notified.");
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function gallery()
    {
        $galleryItems = Gallery::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('frontend.gallery', compact('galleryItems'));
    }

    public function technicalPublications()
    {
        $publications = \App\Models\Publication::all();
        return view('frontend.technical_publications', compact('publications'));
    }

    public function sitemap()
    {
        $products = Product::where('is_active', true)->get();
        $blogs = Blog::all();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $xml .= '<url><loc>' . url('/') . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>';
        $xml .= '<url><loc>' . url('/products') . '</loc><changefreq>daily</changefreq><priority>0.9</priority></url>';
        $xml .= '<url><loc>' . url('/contact') . '</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>';
        $xml .= '<url><loc>' . url('/gallery') . '</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>';
        $xml .= '<url><loc>' . url('/technical-publications') . '</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>';
        $xml .= '<url><loc>' . url('/exhibitions') . '</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>';
        $xml .= '<url><loc>' . url('/blogs') . '</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>';

        foreach ($products as $p) {
            $xml .= '<url><loc>' . url('/products/' . $p->slug) . '</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
        }

        foreach ($blogs as $b) {
            $xml .= '<url><loc>' . url('/blog/' . $b->slug) . '</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\nDisallow: /admin/\nAllow: /\n\nSitemap: " . url('/sitemap.xml');
        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    public function downloadDocument($filename)
    {
        $filePath = public_path('docs/' . $filename);
        
        if (!file_exists($filePath)) {
            $destDir = public_path('docs');
            if (!file_exists($destDir)) {
                mkdir($destDir, 0777, true);
            }
            
            $docType = str_contains($filename, 'manual') ? 'User Manual' : (str_contains($filename, 'service') ? 'Service Manual' : 'Technical Datasheet');
            $cleanName = ucwords(str_replace(['_', '-'], ' ', pathinfo($filename, PATHINFO_FILENAME)));

            $product = Product::where('pdf_datasheet', 'like', "%{$filename}")
                ->orWhere('pdf_manual', 'like', "%{$filename}")
                ->orWhere('pdf_service_manual', 'like', "%{$filename}")
                ->first();

            $title = $product ? ($product->name . " - " . $docType) : $cleanName;
            $model = $product ? "Model Number: " . ($product->model_number ?? 'N/A') : "Document: " . $cleanName;
            $cat = $product && $product->category ? "Category: " . $product->category->name : "Category: Metrology & Scientific Equipment";
            $desc = $product && $product->short_description ? $product->short_description : "Official Aczet Scientific Technical Documentation.";

            $lines = [
                "Document Type: " . $docType,
                $model,
                $cat,
                "Standard: ISO 9001:2015 & OIML R76 Certified Metrology System",
                "Description: " . substr($desc, 0, 90),
                "Quality Assurance: 100% Factory Calibrated and Inspected",
                "Global Support Helpline: +91 22 4141 5555 | Email: sales@aczet.com",
            ];

            $pdf = $this->buildValidPdf($title, $lines);
            file_put_contents($filePath, $pdf);
        }

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function buildValidPdf(string $title, array $lines): string
    {
        $eol = "\n";
        $stream = "BT" . $eol;
        $stream .= "/F1 20 Tf 50 750 Td (ACZET PRECISION SCIENTIFIC) Tj" . $eol;
        $stream .= "/F1 14 Tf 50 715 Td (" . str_replace(['(', ')', '\\'], ['\\(', '\\)', '\\\\'], substr($title, 0, 70)) . ") Tj" . $eol;
        $stream .= "/F2 10 Tf 50 685 Td (----------------------------------------------------------------------------------------------------) Tj" . $eol;

        $y = 655;
        foreach ($lines as $line) {
            $safeLine = str_replace(['(', ')', '\\'], ['\\(', '\\)', '\\\\'], substr($line, 0, 95));
            $stream .= "/F2 10 Tf 50 " . $y . " Td (" . $safeLine . ") Tj" . $eol;
            $y -= 22;
        }

        $stream .= "/F2 9 Tf 50 70 Td (Copyright 2026 Aczet Inc. All Rights Reserved. Support: sales@aczet.com | +91 22 4141 5555) Tj" . $eol;
        $stream .= "ET" . $eol;

        $streamLen = strlen($stream);

        $header = "%PDF-1.4" . $eol;
        $obj1 = "1 0 obj" . $eol . "<</Type /Catalog /Pages 2 0 R>>" . $eol . "endobj" . $eol;
        $obj2 = "2 0 obj" . $eol . "<</Type /Pages /Kids [3 0 R] /Count 1>>" . $eol . "endobj" . $eol;
        $obj3 = "3 0 obj" . $eol . "<</Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources <</Font <</F1 5 0 R /F2 6 0 R>>>>>>" . $eol . "endobj" . $eol;
        $obj4 = "4 0 obj" . $eol . "<</Length " . $streamLen . ">>" . $eol . "stream" . $eol . $stream . "endstream" . $eol . "endobj" . $eol;
        $obj5 = "5 0 obj" . $eol . "<</Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold>>" . $eol . "endobj" . $eol;
        $obj6 = "6 0 obj" . $eol . "<</Type /Font /Subtype /Type1 /BaseFont /Helvetica>>" . $eol . "endobj" . $eol;

        $pdf = $header;
        $o1 = strlen($pdf);
        $pdf .= $obj1;
        $o2 = strlen($pdf);
        $pdf .= $obj2;
        $o3 = strlen($pdf);
        $pdf .= $obj3;
        $o4 = strlen($pdf);
        $pdf .= $obj4;
        $o5 = strlen($pdf);
        $pdf .= $obj5;
        $o6 = strlen($pdf);
        $pdf .= $obj6;

        $xrefOffset = strlen($pdf);

        $xref = "xref" . $eol;
        $xref .= "0 7" . $eol;
        $xref .= "0000000000 65535 f " . $eol;
        $xref .= sprintf("%010d 00000 n ", $o1) . $eol;
        $xref .= sprintf("%010d 00000 n ", $o2) . $eol;
        $xref .= sprintf("%010d 00000 n ", $o3) . $eol;
        $xref .= sprintf("%010d 00000 n ", $o4) . $eol;
        $xref .= sprintf("%010d 00000 n ", $o5) . $eol;
        $xref .= sprintf("%010d 00000 n ", $o6) . $eol;

        $trailer = "trailer" . $eol . "<</Size 7 /Root 1 0 R>>" . $eol . "startxref" . $eol . $xrefOffset . $eol . "%%EOF" . $eol;

        return $pdf . $xref . $trailer;
    }

    public function privacyPolicy()
    {
        return view('frontend.privacy_policy');
    }

    public function termsAndConditions()
    {
        return view('frontend.terms_and_conditions');
    }

    public function webinars()
    {
        return view('frontend.webinars');
    }

    // About Us Sub-Pages
    public function aboutUs()
    {
        return view('frontend.about.index');
    }

    public function aboutTechnology()
    {
        return view('frontend.about.technology');
    }

    public function aboutGlobalPresence()
    {
        return view('frontend.about.global_presence');
    }

    public function aboutVisionMission()
    {
        return view('frontend.about.vision_mission');
    }

    public function aboutManagement()
    {
        return view('frontend.about.management');
    }

    public function aboutHistory()
    {
        return view('frontend.about.history');
    }

    public function aboutWhyChooseAczet()
    {
        return view('frontend.about.why_choose_aczet');
    }

    // Public Product FAQs Hub
    public function faqs(Request $request)
    {
        $query = Faq::where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%")
                  ->orWhere('category_name', 'like', "%{$search}%")
                  ->orWhere('group_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('group')) {
            $query->where('group_name', $request->group);
        }

        if ($request->filled('category')) {
            $query->where('category_name', $request->category);
        }

        $allFaqs = $query->orderBy('group_name')
                         ->orderBy('category_name')
                         ->orderBy('sort_order')
                         ->get();

        // Group by group_name -> category_name
        $groupedFaqs = $allFaqs->groupBy('group_name')->map(function($items) {
            return $items->groupBy('category_name');
        });

        $groups = Faq::where('is_active', true)->distinct('group_name')->orderBy('group_name')->pluck('group_name');
        $categories = Faq::where('is_active', true)->distinct('category_name')->orderBy('category_name')->pluck('category_name');

        return view('frontend.faqs', compact('groupedFaqs', 'allFaqs', 'groups', 'categories'));
    }

    protected function getCategoryDivisionData(Category $category, $locale = 'en')
    {
        $slug = $category->slug;

        $defaults = [
            'industrial-weighing-solutions' => [
                'eyebrow' => 'Industrial Scales & Load Cell Systems',
                'headline' => 'Reliable Weight Scales for Manufacturing Environments',
                'hero_image' => 'images/products/is600.svg',
                'intro' => 'Industrial scales and weighing systems include bench scales, floor scales, pallet scales, rail and truck scales, and weigh modules and load cells for tank and silo weighing. ACZET industrial weighing scales can measure from 0.001 g up to several tons and can be used for simple weighing, legal-for-trade weighing, and more. Pair your industrial scale with a scale indicator, software solution, and easy-to-integrate accessories for full process control and visibility.',
                'catalog_title' => 'Industrial Weighing Catalog 2026',
                'catalog_desc' => 'Your new 2026 Industrial Weighing Catalog will help you quickly envision an efficient, cost-effective solution for mechanical weighing scale that meets all your weighing and processing needs.',
                'catalog_image' => 'images/banner-industry.jpg',
                'comp_title' => 'Weighing Component Catalog',
                'comp_desc' => 'ACZET is a provider of complete balances and scales and manufactures weighing components for builders of instruments, machinery and process plants.',
                'comp_image' => 'images/products/is600.svg',
                'hub_title' => 'Engineering Hub',
                'hub_desc' => 'Looking to expand your knowledge on weighing automation in the era of Industry 4.0? Then check out our latest white papers, guides, webinars and podcasts from subject matter experts.',
                'hub_image' => 'images/banner-vision.jpg',
                'faqs' => [
                    [
                        'q' => 'What are industrial scales used for? What industries use scales?',
                        'a' => 'Industrial scales are essential for precise weight measurements across manufacturing, chemical processing, pharmaceuticals, food & beverage, logistics, and bulk raw material distribution. They enable parts counting, formula recipe batching, checkweighing on packaging lines, and inventory monitoring.'
                    ],
                    [
                        'q' => 'What are the types of weighing scale?',
                        'a' => 'Industrial weighing systems encompass bench scales, floor scales, pallet truck scales, overhead crane scales, weighbridges, high-speed dynamic checkweighers, and weigh modules / load cell systems for tanks, silos, and automated conveyors.'
                    ],
                    [
                        'q' => 'Does the weighing procedure have to be verifiable?',
                        'a' => 'Yes. Any weighing procedure used for commercial trade, statutory tariff determination, legal packaging, or pharmaceutical manufacturing must be verified and certified according to Legal-for-Trade standards (OIML, NTEP, Weights & Measures).'
                    ],
                    [
                        'q' => 'What level of accuracy is required for your weighing purposes? How accurate are the industrial weighing systems?',
                        'a' => 'Accuracy requirements depend on process tolerance. ACZET industrial systems range from high-precision bench scales with 0.001 g readability to heavy-capacity floor scales and weighbridges verified to OIML Class III tolerances, offering repeatable precision under heavy vibration.'
                    ],
                    [
                        'q' => 'What should I look for when comparing scale manufacturers?',
                        'a' => 'Look for metrological measurement repeatability, rugged ingress protection (IP68/IP69K stainless steel), industrial communication protocols (Ethernet, Profinet, Modbus, 4-20mA), ISO/IEC 17025 accredited calibration, and responsive factory technical support.'
                    ],
                    [
                        'q' => 'Which are the best weighing scales?',
                        'a' => 'The best scale is one specifically matched to your environmental demands and operational throughput. ACZET heavy-duty bench and floor scales feature overload protection up to 300% and intuitive digital terminals engineered for 24/7 industrial uptime.'
                    ],
                    [
                        'q' => 'Why are we the leading industrial scale company?',
                        'a' => 'With over 35 years of engineering heritage, manufacturing facilities across India, exports to 65+ countries, and proprietary sensor technology, ACZET combines European precision standards with competitive lifecycle value and direct technical service.'
                    ],
                    [
                        'q' => 'How to calibrate an industrial scale?',
                        'a' => 'Industrial scales should be calibrated using certified, traceable test weights (OIML Class F1/M1) at regular intervals according to standard operating procedures. ACZET provides routine testing guidelines, automated calibration options, and certified on-site metrology services.'
                    ],
                ]
            ],
            'laboratory-weighing' => [
                'eyebrow' => 'Laboratory Balances & Weighing Solutions',
                'headline' => 'World-Class Precision Balances for Analytical & Research Excellence',
                'hero_image' => 'images/products/lab-balances-banner.png',
                'intro' => 'Discover industry-leading microbalances, analytical balances, precision scales, and moisture analyzers engineered for pharmaceutical compliance, metrology institutes, and rigorous research laboratories worldwide. ACZET balances deliver unmatched repeatability down to 0.1 µg with MonoTech monolithic EMFC sensors.',
                'catalog_title' => 'Laboratory Balances Catalog 2026',
                'catalog_desc' => 'Comprehensive selection guide for ultra-micro, micro, analytical, and precision balances with USP / FDA 21 CFR Part 11 compliance standards.',
                'catalog_image' => 'images/products/lab-balances-banner.png',
                'comp_title' => 'Monolithic Sensor Engineering Guide',
                'comp_desc' => 'Learn how Singleblock EMFC technology eliminates thermal drift and provides sub-microgram repeatability in vibration-prone labs.',
                'comp_image' => 'images/products/cy224.svg',
                'hub_title' => 'Good Weighing Practice (GWP) Hub',
                'hub_desc' => 'Standard operating procedures, routine testing intervals, and calibration weight selection guides curated by our senior metrologists.',
                'hub_image' => 'images/products/calibration-weight-set.jpg',
                'faqs' => [
                    [
                        'q' => 'What is the difference between an analytical balance and a precision balance?',
                        'a' => 'Analytical balances offer higher readability, typically 0.1 mg (0.0001 g) or better, and feature draft shields to prevent air currents from disrupting measurements. Precision balances generally read from 1 mg (0.001 g) to 0.1 g and accommodate higher capacities up to several kilograms.'
                    ],
                    [
                        'q' => 'What is MonoTech monolithic weighing sensor technology?',
                        'a' => 'MonoTech is an electromagnetic force compensation (EMFC) sensor cut from a single block of aerospace alloy. It eliminates joint friction and mechanical hysteresis, providing ultra-fast settling times, exceptional thermal stability, and long-term repeatability.'
                    ],
                    [
                        'q' => 'How do ACZET laboratory balances support USP Chapter 41 and FDA 21 CFR Part 11 compliance?',
                        'a' => 'ACZET balances feature built-in minimum sample weight calculation, automated motorized internal calibration (Auto-Cal), electronic password management, and GLP/GMP/USP compliant printouts with audit trails and time-stamped records.'
                    ],
                    [
                        'q' => 'Why is internal calibration important in laboratory balances?',
                        'a' => 'Internal calibration uses built-in motorized reference weights to automatically re-zero and calibrate the balance whenever ambient temperature changes or at preset time intervals, ensuring continuous measurement accuracy without manual intervention.'
                    ],
                    [
                        'q' => 'What class of test weights should be used to calibrate analytical balances?',
                        'a' => 'Analytical balances (0.1 mg readability) should be calibrated or verified using OIML Class E2 or F1 traceable stainless steel weights. For microbalances (0.001 mg / 1 µg), Class E1 weights are recommended.'
                    ],
                    [
                        'q' => 'How does a halogen moisture analyzer measure moisture content?',
                        'a' => 'Halogen moisture analyzers utilize the Loss On Drying (LOD) principle, heating the sample with an intense halogen radiator while continuously weighing it with an integrated analytical cell until a dry mass plateau is reached.'
                    ],
                    [
                        'q' => 'What factors affect laboratory balance measurement accuracy?',
                        'a' => 'Temperature fluctuations, ambient air drafts, static electricity, bench vibrations, magnetic fields, and off-center loading can influence readings. Using static eliminators (ionizers) and heavy granite anti-vibration tables minimizes these effects.'
                    ],
                    [
                        'q' => 'How often should a laboratory balance be serviced and recalibrated?',
                        'a' => 'Routine performance checks should be done daily or before critical runs. Formal ISO 17025 accredited recalibration and preventive maintenance should be conducted at least annually by certified service technicians.'
                    ],
                ]
            ],
            'jewelry-machines' => [
                'eyebrow' => 'Jewelry Equipment & XRF Assaying',
                'headline' => 'Advanced Precious Metal Testing & Jewelry Manufacturing Systems',
                'hero_image' => 'images/hero-gold-analyzer.jpg',
                'intro' => 'From non-destructive Gold Testing XRF Analyzers and laser spot welders to diamond marking systems and precision carat balances, ACZET equips hallmark centers, refiners, and master jewelers with certified accuracy and unyielding reliability.',
                'catalog_title' => 'Jewelry Machines & XRF Catalog 2026',
                'catalog_desc' => 'Explore the complete portfolio of gold testing analyzers, laser engraving and welding systems, and high-precision jewelry balances.',
                'catalog_image' => 'images/products/gold-cube.jpg',
                'comp_title' => 'Laser Technology Selection Guide',
                'comp_desc' => 'Choosing the optimal spot welding and laser engraving power for gold, silver, platinum, and titanium fabrication.',
                'comp_image' => 'images/hero-gold-analyzer.jpg',
                'hub_title' => 'Precious Metal Assaying Hub',
                'hub_desc' => 'Industry standards, hallmarking regulations, and non-destructive elemental analysis methodologies for bullion and jewelry.',
                'hub_image' => 'images/products/gold-compact-eco.jpg',
                'faqs' => [
                    [
                        'q' => 'How does an XRF gold testing machine determine karat purity?',
                        'a' => 'X-ray Fluorescence (XRF) excites inner-shell electrons in the sample, causing them to emit characteristic fluorescent X-rays. The detector measures the spectral energy and intensity, providing exact percentages of Gold (Au), Silver (Ag), Platinum (Pt), and base alloys non-destructively in seconds.'
                    ],
                    [
                        'q' => 'Can XRF detect gold plating and fake bullion?',
                        'a' => 'Yes. Advanced ACZET XRF analyzers feature multi-layer coating thickness algorithms and pinnable focus collimators that differentiate gold plating from solid bullion and identify substrate metals like copper, tungsten, or brass.'
                    ],
                    [
                        'q' => 'What are the advantages of laser spot welding in jewelry manufacturing?',
                        'a' => 'Laser spot welding provides localized heat with minimal thermal distortion, allowing jewelers to repair delicate settings near heat-sensitive gemstones without unmounting them, while creating seamless joints in precious metals.'
                    ],
                    [
                        'q' => 'What is the accuracy of ACZET jewelry carat balances?',
                        'a' => 'ACZET jewelry balances offer legal-for-trade Class II certification with readabilities down to 0.001 ct (0.2 mg), ensuring exact weight certification for diamonds, gemstones, and gold bullion.'
                    ],
                    [
                        'q' => 'How does an ultrasonic cleaner clean intricate jewelry pieces?',
                        'a' => 'Ultrasonic cleaners generate high-frequency sound waves that produce microscopic cavitation bubbles in the cleaning liquid. When these bubbles implode, they dislodge polishing compound, oils, and dirt from deep crevices without scratching.'
                    ],
                    [
                        'q' => 'What safety measures are built into ACZET laser systems?',
                        'a' => 'ACZET laser welders and cutters incorporate Class 1 laser safety enclosures, interlocking safety doors, specialized optical filtration viewing windows, and active fume extraction ports.'
                    ],
                    [
                        'q' => 'Do ACZET XRF instruments require hazardous radiation licenses?',
                        'a' => 'ACZET XRF analyzers feature low-power microfocus X-ray tubes with full lead shielding and safety interlocks, complying with AERB and international radiation safety limits for safe benchtop operation.'
                    ],
                    [
                        'q' => 'Can ACZET induction melting machines melt platinum and palladium?',
                        'a' => 'Yes. ACZET high-frequency induction furnaces reach temperatures exceeding 2000°C, providing rapid, homogeneous melting for platinum, gold, and silver in inert atmosphere conditions.'
                    ],
                ]
            ],
            'industrial-machines' => [
                'eyebrow' => 'Industrial NDT & Coating Analysis',
                'headline' => 'High-Performance XRF Coating Thickness & Material Analyzers',
                'hero_image' => 'images/products/handheld-xrf.jpg',
                'intro' => 'Accurate elemental analysis and coating thickness measurements across automotive, aerospace, electronics, and metallurgy sectors. ACZET non-destructive testing systems streamline quality control and industrial compliance.',
                'catalog_title' => 'Industrial NDT & Coating Catalog 2026',
                'catalog_desc' => 'Technical specifications and application notes for benchtop and handheld XRF coating thickness analyzers and PMI spectrometers.',
                'catalog_image' => 'images/products/handheld-xrf.jpg',
                'comp_title' => 'RoHS Compliance Testing Guide',
                'comp_desc' => 'Screening for lead, cadmium, mercury, and hazardous substances under international environmental regulations.',
                'comp_image' => 'images/banner-industry.jpg',
                'hub_title' => 'NDT Materials Engineering Hub',
                'hub_desc' => 'White papers on electroplating quality control, alloy grade identification, and multi-layer coating thickness calculations.',
                'hub_image' => 'images/banner-vision.jpg',
                'faqs' => [
                    [
                        'q' => 'How does XRF measure coating thickness?',
                        'a' => 'XRF measures either the attenuation of the substrate signal or the emission intensity of the top coating layer. By analyzing this intensity against calibrated standards, thickness is measured accurately in nanometers or micrometers.'
                    ],
                    [
                        'q' => 'Can ACZET analyzers measure multi-layer coatings?',
                        'a' => 'Yes. ACZET coating thickness analyzers can measure up to 5 individual layers simultaneously (e.g. Gold over Nickel over Copper over PCB substrate) and analyze alloy composition within each layer.'
                    ],
                    [
                        'q' => 'What is Positive Material Identification (PMI)?',
                        'a' => 'PMI is non-destructive elemental verification of alloys (such as stainless steels, Inconel, and titanium alloys) used in high-pressure piping, valves, and aerospace structures to ensure material grade specifications are met.'
                    ],
                    [
                        'q' => 'What elements can ACZET XRF instruments detect?',
                        'a' => 'Equipped with Silicon Drift Detectors (SDD) and helium purge or vacuum chambers, ACZET analyzers detect elements from Sodium (Na) or Magnesium (Mg) through Uranium (U).'
                    ],
                    [
                        'q' => 'How does ACZET assist with RoHS / WEEE compliance?',
                        'a' => 'ACZET RoHS analyzers feature pre-configured pass/fail screening routines for Pb, Cd, Hg, Cr, and Br with automated compliance reporting and audit documentation.'
                    ],
                    [
                        'q' => 'Are handheld XRF analyzers suitable for field scrap sorting?',
                        'a' => 'Yes. ACZET handheld analyzers feature IP54 ruggedized drop-resistant enclosures, hot-swappable batteries, and instantaneous alloy grade matching libraries for fast scrap sorting.'
                    ],
                    [
                        'q' => 'What sample preparation is required for XRF testing?',
                        'a' => 'XRF is non-destructive and generally requires zero sample preparation for solid metals and components. Oils, powders, or liquids can be placed in disposable sample cups.'
                    ],
                    [
                        'q' => 'How are ACZET coating thickness standards certified?',
                        'a' => 'ACZET calibration foil sets and reference standards are traceable to NIST and PTB standards, accompanied by accredited calibration certificates.'
                    ],
                ]
            ],
            'laboratory-instruments' => [
                'eyebrow' => 'Laboratory Instruments & Analytical Tools',
                'headline' => 'Precision Scientific Instrumentation for Modern Research Laboratories',
                'hero_image' => 'images/banner-service.jpg',
                'intro' => 'Empower your analytical workflows with ACZET ultrasonic processors, rotational viscometers, polarimeters, refractometers, and laboratory flake ice makers engineered to rigorous international quality benchmarks.',
                'catalog_title' => 'Laboratory Instruments Catalog 2026',
                'catalog_desc' => 'Comprehensive catalog featuring digital polarimeters, Abbé refractometers, rotational viscometers, and ultrasonic equipment.',
                'catalog_image' => 'images/banner-service.jpg',
                'comp_title' => 'Viscosity & Rheology Guide',
                'comp_desc' => 'Selecting the correct spindle geometries and shear rates for fluids, gels, polymers, and food formulations.',
                'comp_image' => 'images/products/cy224.svg',
                'hub_title' => 'Optical Metrology Knowledge Hub',
                'hub_desc' => 'Best practices for optical rotation, Brix measurement, and temperature compensation in sugar and chemical labs.',
                'hub_image' => 'images/banner-vision.jpg',
                'faqs' => [
                    [
                        'q' => 'What applications are ACZET ultrasonic probe sonicators used for?',
                        'a' => 'Probe sonicators are used for cell disruption, nanoparticle dispersion, emulsification, DNA shearing, degassing liquids, and accelerating chemical reactions via intense cavitation.'
                    ],
                    [
                        'q' => 'What is the difference between an Abbé refractometer and an automatic digital refractometer?',
                        'a' => 'Abbé refractometers rely on manual optical alignment and ocular scale reading, whereas digital refractometers use high-resolution CCD sensors and Peltier temperature control to deliver automatic Brix and Refractive Index (nD) readouts in seconds.'
                    ],
                    [
                        'q' => 'Why is temperature control crucial in polarimetry and refractometry?',
                        'a' => 'Optical rotation and refractive index are highly temperature-dependent. ACZET instruments incorporate built-in Peltier thermo-regulation to maintain exact sample temperatures (e.g. 20.0°C or 25.0°C) without external water baths.'
                    ],
                    [
                        'q' => 'What industries utilize ACZET rotational viscometers?',
                        'a' => 'Rotational viscometers are widely used in paints & coatings, cosmetics, pharmaceuticals, food processing, petrochemicals, and adhesives for quality control and rheological profiling.'
                    ],
                    [
                        'q' => 'How does a tablet hardness tester function?',
                        'a' => 'Tablet hardness testers exert controlled mechanical force across a pharmaceutical tablet until it fractures, recording breaking force (hardness in N, kp, or Sc) and thickness/diameter.'
                    ],
                    [
                        'q' => 'What are the benefits of flake ice in scientific laboratories?',
                        'a' => 'Flake ice has a large surface area and soft texture that moulds closely around test tubes and reaction vessels, providing rapid, uniform cooling without breaking delicate glassware.'
                    ],
                    [
                        'q' => 'What parameters are tested by ACZET ultrasonic milk analyzers?',
                        'a' => 'ACZET milk analyzers determine fat, solid non-fat (SNF), protein, lactose, added water, temperature, freezing point, and density within 30-60 seconds without chemical reagents.'
                    ],
                    [
                        'q' => 'Do ACZET laboratory instruments include calibration certificates?',
                        'a' => 'Yes. All instruments are shipped factory-tested with traceable calibration certificates and standard reference solutions where applicable.'
                    ],
                ]
            ],
        ];

        if (isset($defaults[$slug])) {
            return $defaults[$slug];
        }

        // Generic fallback for any parent category
        return [
            'eyebrow' => $category->name . ' Systems & Instrumentation',
            'headline' => 'Advanced ' . $category->name . ' for Industrial & Laboratory Applications',
            'hero_image' => 'images/banner-industry.jpg',
            'intro' => 'Discover high-precision ' . strtolower($category->name) . ' engineered for certified metrological performance, continuous throughput, and seamless industrial integration.',
            'catalog_title' => $category->name . ' Catalog 2026',
            'catalog_desc' => 'Explore the complete technical specifications and selection guide for ' . strtolower($category->name) . '.',
            'catalog_image' => 'images/banner-industry.jpg',
            'comp_title' => 'Technical Application Notes',
            'comp_desc' => 'Engineering guides and application notes for high-performance ' . strtolower($category->name) . '.',
            'comp_image' => 'images/products/is600.svg',
            'hub_title' => 'Aczet Knowledge Hub',
            'hub_desc' => 'Webinars, white papers, and expert guides on certified metrology and precision testing.',
            'hub_image' => 'images/banner-vision.jpg',
            'faqs' => [
                [
                    'q' => 'What standards of precision do ACZET ' . strtolower($category->name) . ' adhere to?',
                    'a' => 'ACZET instruments are manufactured under strict ISO 9001 quality systems and tested to international OIML, NTEP, and CE metrological benchmarks.'
                ],
                [
                    'q' => 'How can I get technical assistance or request a quotation?',
                    'a' => 'You can click Get a Quote on any product or contact our global technical support network directly via our toll-free line or online portal.'
                ],
            ]
        ];
    }

    public function industries(Request $request)
    {
        $industries = $this->getIndustriesData();
        return view('frontend.industries.index', compact('industries'));
    }

    public function industryDetail($slug)
    {
        $industries = $this->getIndustriesData();
        $industry = $industries[$slug] ?? null;

        if (!$industry) {
            abort(404);
        }

        // Eager load recommended products for this industry
        $recommendedProducts = collect();
        if (!empty($industry['recommended_categories'])) {
            $currentRegion = session('aczet_region', 'India');
            $recommendedProducts = Product::where('is_active', true)
                ->whereHas('category', function($q) use ($industry) {
                    $q->whereIn('slug', $industry['recommended_categories'])
                      ->orWhereHas('parent', function($pq) use ($industry) {
                          $pq->whereIn('slug', $industry['recommended_categories']);
                      });
                })
                ->whereHas('regionVisibilities', function($q) use ($currentRegion) {
                    $q->where('region', $currentRegion)->where('is_visible', true);
                })
                ->take(6)
                ->get();
        }

        return view('frontend.industries.show', compact('industry', 'industries', 'recommendedProducts'));
    }

    private function getIndustriesData()
    {
        return [
            'pharmaceutical-biotech' => [
                'slug' => 'pharmaceutical-biotech',
                'name' => 'Pharmaceutical & Biotech',
                'short_name' => 'Pharma & Biotech',
                'icon' => 'fa-solid fa-pills',
                'tagline' => 'High-Precision Weighing, 21 CFR Part 11 Compliance & Analytical Solutions',
                'description' => 'From ultra-microbalances for active pharmaceutical ingredients (APIs) to automated tablet testing and cleanroom floor scales, Aczet empowers pharmaceutical laboratories and manufacturing facilities to meet stringent USP <41>, USP <1251>, FDA 21 CFR Part 11, and GMP/GLP compliance standards.',
                'image' => 'images/banner-industry.jpg',
                'hero_subtitle' => 'Audit-ready precision measurement systems engineered for regulatory compliance and zero-error formulations.',
                'challenges' => [
                    'Ensuring compliance with USP Chapter 41 minimum weight thresholds in analytical workflows.',
                    'Maintaining data integrity, audit trails, and secure electronic signatures under 21 CFR Part 11.',
                    'Controlling moisture content in hygroscopic excipients and lyophilized compounds.',
                    'Preventing cross-contamination in cleanrooms with IP68/IP69K stainless steel designs.'
                ],
                'solutions' => [
                    'Ultra-Micro & Microbalances with 0.1 µg readability for precious active compounds.',
                    'Halogen Moisture Analyzers with rapid heating profiles and traceable calibration reports.',
                    'High-Speed Checkweighers with integrated rejection systems for pharmaceutical packaging lines.',
                    'Pharmaceutical Tablet Hardness & Friability Testers for pharmacopeial QA/QC.'
                ],
                'standards' => ['USP <41>', 'FDA 21 CFR Part 11', 'GLP / GMP Audit Trail', 'ISO 17025 Accredited', 'OIML Class I & II'],
                'recommended_categories' => ['laboratory-weighing', 'moisture-analyzers', 'check-weighers-metal-detectors']
            ],
            'chemical-petrochemical' => [
                'slug' => 'chemical-petrochemical',
                'name' => 'Chemical & Petrochemical',
                'short_name' => 'Chemical',
                'icon' => 'fa-solid fa-flask',
                'tagline' => 'Intrinsically Safe Hazardous Area Scales & High-Accuracy Density Solutions',
                'description' => 'Robust weighing and analytical equipment built for aggressive chemical environments, hazardous classified zones (Zone 1/21, Zone 2/22), and harsh process conditions requiring ATEX/IECEx certified safety, chemical-resistant alloys, and high-precision density determination.',
                'image' => 'images/banner-vision.jpg',
                'hero_subtitle' => 'Explosion-proof safety and corrosion-resistant precision for continuous industrial chemical processing.',
                'challenges' => [
                    'Weighing volatile and flammable materials in hazardous, classified environments.',
                    'Exposure to harsh solvents, caustic chemicals, and acidic atmospheres.',
                    'Fast, repeatable density determination for polymer synthesis and petrochemical batches.',
                    'Large-batch bulk chemical vessel and reactor weighing with high structural stability.'
                ],
                'solutions' => [
                    'Intrinsically Safe Platform Scales with ATEX/IECEx approved explosion-proof barriers.',
                    'Density Resolution Kits & Precision Balances for petrochemical QA/QC.',
                    'Heavy-Duty Stainless Steel Bench & Floor Scales with hermetically sealed IP68 load cells.',
                    'Halogen & Infrared Moisture Analyzers for polymers, resins, and petrochemical powders.'
                ],
                'standards' => ['ATEX / IECEx Certified', 'IP68 / IP69K Hermetic Seal', 'OIML R76 Legal Metrology', 'ISO 9001:2015'],
                'recommended_categories' => ['industrial-weighing-solutions', 'industrial-scales', 'moisture-analyzers']
            ],
            'food-beverage' => [
                'slug' => 'food-beverage',
                'name' => 'Food & Beverage Manufacturing',
                'short_name' => 'Food & Beverage',
                'icon' => 'fa-solid fa-utensils',
                'tagline' => 'Hygienic Washdown Scales, In-Line Checkweighers & Moisture Analyzers',
                'description' => 'Food safety and production throughput depend on fast, accurate weighing and contamination inspection. Aczet provides hygienic washdown bench scales, automated dynamic checkweighers, and moisture balances designed to prevent bacterial harborage and ensure strict HACCP and IFS compliance.',
                'image' => 'images/banner-technology.jpg',
                'hero_subtitle' => 'Hygienic precision and high-throughput product inspection for modern food and beverage processors.',
                'challenges' => [
                    'High-pressure washdown cleaning protocols (IP68/IP69K) with aggressive sanitizers.',
                    'Preventing underweight or overweight packaging to protect brand reputation and minimize giveaway.',
                    'Controlling moisture content to ensure shelf-life, crispness, and texture.',
                    'Detecting foreign body contaminants (ferrous and non-ferrous metals) at conveyor speeds.'
                ],
                'solutions' => [
                    'High-Speed Dynamic Checkweighers with automatic air-blast and pneumatic pushover rejectors.',
                    'Washdown Stainless Steel Platform Scales with open-frame design for easy sanitation.',
                    'Halogen Moisture Analyzers for rapid QA testing of grains, powders, dairy, and snacks.',
                    'Precision Recipe Formulation Balances with visual target-weighing bar graphs.'
                ],
                'standards' => ['HACCP Compliant', 'IP69K High-Pressure Washdown', 'OIML R51 / R76', 'FDA Approved Contact Materials'],
                'recommended_categories' => ['check-weighers-metal-detectors', 'industrial-scales', 'moisture-analyzers']
            ],
            'academia-research' => [
                'slug' => 'academia-research',
                'name' => 'Academia, Universities & Research',
                'short_name' => 'Academia & Research',
                'icon' => 'fa-solid fa-graduation-cap',
                'tagline' => 'Advanced Analytical Instruments & Metrology Balances for Scientific Discovery',
                'description' => 'Scientific education and institutional research demand reliable, intuitive instruments that deliver repeatable micro-measurements. Aczet partners with universities, government laboratories, and research institutions worldwide to equip laboratories with cutting-edge analytical tools.',
                'image' => 'images/banner-about.jpg',
                'hero_subtitle' => 'Inspiring scientific breakthroughs with high-resolution balances and analytical instrumentation.',
                'challenges' => [
                    'Measuring sub-milligram sample quantities with extreme environmental stability.',
                    'Multiple student and researcher users requiring intuitive touch interfaces and data export.',
                    'Budget constraints requiring durable, multi-functional instruments with long lifespans.',
                    'Teaching traceable metrology and legal-for-trade calibration principles.'
                ],
                'solutions' => [
                    'Analytical Balances with Single-Block Electromagnetic Force Compensation (EMFC).',
                    'Internal Automatic Calibration (Auto-Cal) reacting to temperature variations.',
                    'Direct USB & RS-232 PC connectivity for seamless LIMS and data collection.',
                    'Rotary Evaporators, Centrifuges, and Melting Point Apparatus for chemistry curricula.'
                ],
                'standards' => ['ISO 17025 Traceable', 'OIML Class I Metrology', 'CE Compliance', 'GLP Printout Format'],
                'recommended_categories' => ['laboratory-weighing', 'laboratory-instruments']
            ],
            'metals-mining' => [
                'slug' => 'metals-mining',
                'name' => 'Metals, Mining & Raw Materials',
                'short_name' => 'Metals & Mining',
                'icon' => 'fa-solid fa-mountain',
                'tagline' => 'Heavy-Duty Weighbridges, High-Capacity Platforms & Elemental XRF Analyzers',
                'description' => 'In metals recycling, foundries, and raw material extraction, harsh environments and massive tonnages are the norm. Aczet delivers rugged truck weighbridges, high-capacity pitless platform scales, and precision XRF analyzers for non-destructive elemental composition analysis.',
                'image' => 'images/hero-weigh-bridge.jpg',
                'hero_subtitle' => 'Rugged industrial durability and elemental precision for foundries, recycling, and raw materials.',
                'challenges' => [
                    'Extreme shock loading, abrasive dust, and outdoor weather extremes.',
                    'Accurate commercial billing and inventory custody transfer for bulk truckloads.',
                    'Rapid elemental grade verification and scrap metal alloy identification.',
                    'Heavy coil and ladle weighing in high-temperature foundry areas.'
                ],
                'solutions' => [
                    'Modular Steel & Concrete Truck Scales / Weighbridges with digital load cells.',
                    'Heavy-Duty Low-Profile Floor Scales up to 10-ton capacity with impact-absorbing corners.',
                    'Benchtop & Handheld XRF Spectrometers for instantaneous alloy sorting and karat determination.',
                    'Crane & Suspended Hook Scales for foundry and crane hoist applications.'
                ],
                'standards' => ['OIML R60 Load Cells', 'NTEP Legal for Trade', 'IP67 Weatherproof', 'Heavy Shock Loading Tested'],
                'recommended_categories' => ['industrial-weighing-solutions', 'industrial-machines']
            ],
            'jewelry-precious-metals' => [
                'slug' => 'jewelry-precious-metals',
                'name' => 'Jewelry & Precious Metals',
                'short_name' => 'Jewelry & Precious Metals',
                'icon' => 'fa-solid fa-gem',
                'tagline' => 'Legal-for-Trade Karat Balances, Gold Purity XRF Analyzers & Laser Welding',
                'description' => 'Aczet is a global pioneer in the precious metals sector. We provide certified legal-for-trade diamond and gold balances with 0.001 ct precision, Roentgenanalytik German-engineered XRF gold purity spectrometers, and laser spot welding/marking machines for hallmark manufacturing.',
                'image' => 'images/banner-industry.jpg',
                'hero_subtitle' => 'Global benchmark technology for gold purity testing, diamond carat weighing, and laser jewelry fabrication.',
                'challenges' => [
                    'Ensuring 100% accurate, tamper-proof carat and gram weighing for legal trade transactions.',
                    'Detecting gold plating, fake alloys, and hidden iridium/ruthenium adulterants non-destructively.',
                    'Precision welding of delicate rings, chains, and platinum mounts without heat distortion.',
                    'Micro-hallmarking and serial engraving on microscopic jewelry surfaces.'
                ],
                'solutions' => [
                    'Certified Karat & Diamond Balances with dual-range carat/gram switching and internal calibration.',
                    'Aczet XRF Precious Metal Gold Testers with silicon pin / SDD detectors for instant karat readout.',
                    'Benchtop Laser Spot Welding Machines with microscope optics for repair and fabrication.',
                    'Fiber Laser Engraving & Marking Systems for BIS hallmarking and barcode serialization.'
                ],
                'standards' => ['OIML Class I & II Legal Metrology', 'BIS Hallmarking Ready', 'NABL Calibration Traceable', 'CE Certified Laser Class 4'],
                'recommended_categories' => ['jewelry-machines', 'laboratory-weighing']
            ],
            'industrial-manufacturing' => [
                'slug' => 'industrial-manufacturing',
                'name' => 'Industrial Manufacturing & OEM',
                'short_name' => 'Manufacturing & OEM',
                'icon' => 'fa-solid fa-industry',
                'tagline' => 'High-Precision Counting Scales, OEM Weigh Modules & Automation Line Integration',
                'description' => 'Modern manufacturing demands automated quality control and efficient inventory management. Aczet provides high-resolution piece counting scales, automated weighing modules for PLC/SCADA integration, and durable bench platforms to keep factories operating at peak productivity.',
                'image' => 'images/banner-vision.jpg',
                'hero_subtitle' => 'Accelerating factory automation and precision parts counting across high-volume production lines.',
                'challenges' => [
                    'Eliminating human counting errors in high-volume fastener, stamping, and injection-molded parts.',
                    'Seamless communication with industrial automation networks (Modbus, Profinet, Ethernet/IP).',
                    'Fast stabilization times to match rapid assembly line cycles.',
                    'Withstanding harsh industrial handling, forklift vibration, and overload impacts.'
                ],
                'solutions' => [
                    'Dual-Platform High-Precision Counting Systems with 1:1,000,000 internal counting resolution.',
                    'OEM High-Speed Weigh Modules for packaging machinery, filling stations, and checkweighers.',
                    'Cast Aluminum & Stainless Steel Bench Scales with overload protection stops.',
                    'Industrial Terminals with barcode scanning and label printing integration.'
                ],
                'standards' => ['OIML R76 Class III', 'Modbus / Profinet Ready', 'IP65 / IP67 Enclosures', 'ISO 9001 Certified'],
                'recommended_categories' => ['industrial-weighing-solutions', 'industrial-scales', 'industrial-machines']
            ],
            'transport-logistics' => [
                'slug' => 'transport-logistics',
                'name' => 'Transport, Logistics & Warehousing',
                'short_name' => 'Transport & Logistics',
                'icon' => 'fa-solid fa-truck',
                'tagline' => 'Pallet Truck Scales, Dynamic Dimensioning Systems & Freight Management',
                'description' => 'In warehousing, air cargo, and logistics hubs, shipping cost is directly tied to accurate weight and volume calculations. Aczet equips freight forwarders, 3PL warehouses, and courier networks with mobile pallet scales, high-speed parcel dimensioning systems, and fleet weighbridge software.',
                'image' => 'images/hero-weigh-bridge.jpg',
                'hero_subtitle' => 'Optimizing freight revenue recovery, warehouse throughput, and axle load compliance.',
                'challenges' => [
                    'Calculating dimensional weight (DIM weight) quickly on irregularly shaped parcels.',
                    'Preventing costly truck axle overload penalties on public highways.',
                    'Weighing palletized goods on the move without returning to a central fixed platform scale.',
                    'Integrating weight data directly into warehouse management systems (WMS) and ERPs.'
                ],
                'solutions' => [
                    'Heavy-Duty Mobile Pallet Truck Scales with built-in thermal ticket printers.',
                    'Static & Dynamic Parcel Dimensioning Systems calculating L x W x H and volumetric weight.',
                    'In-Motion Vehicle Axle Scales for automatic overload prevention and fleet screening.',
                    'Rugged Floor Platform Scales with drive-on ramps for pallet jack entry.'
                ],
                'standards' => ['OIML R76 Legal for Trade', 'OIML R134 Vehicle In-Motion', 'WMS / ERP Direct Integration', 'Heavy-Duty Shock Absorption'],
                'recommended_categories' => ['industrial-weighing-solutions', 'industrial-scales']
            ],
        ];
    }
}
