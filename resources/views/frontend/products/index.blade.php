@extends('layouts.app')

@section('title', ($t['catalog_title'] ?? 'Product & Service Catalog') . ' - Aczet Metrology')

@section('styles')
    <link rel="stylesheet" href="{{ vasset('css/products.css') }}">
@endsection

@section('content')
@if(!empty($isCategoryLanding) && !empty($landingCategory))
    @include('frontend.products.partials.category_landing')
@else
<!-- 1. CLEAN CORPORATE CATALOG HERO BANNER (MATCHING IMAGE1C.PNG & DOCUMENT) -->
<section class="catalog-hero-clean">
    <div class="catalog-hero-wrap">
        <!-- Breadcrumbs: Home > [Active Parent Category] > [Selected Subcategory] -->
        {!! $breadcrumbsHtml ?? '' !!}

        <!-- Clean Banner Grid: Balance Image on Left, Headline + Buttons in Center, Lab Glassware on Right -->
        <div class="catalog-banner-grid">
            <div class="banner-balance-stage">
                <img src="{{ $bannerImageLeft }}" alt="{{ $bannerEyebrow }}" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
            </div>
            <div class="banner-balance-content">
                <span class="banner-category-eyebrow">{{ $bannerEyebrow }}</span>
                <h1 class="banner-balance-headline">
                    {{ $bannerHeadline }}
                </h1>
                <div class="banner-action-buttons">
                    <button type="button" class="btn-banner-req-info" onclick="openQuoteModal('General Inquiry: {{ addslashes($quoteTopic) }}')">
                        Request info
                    </button>
                    <button type="button" class="btn-banner-get-quote" onclick="openQuoteModal('Quote Request: {{ addslashes($quoteTopic) }}')">
                        Get a Quote
                    </button>
                </div>
            </div>
            <div class="banner-glassware-stage">
                <img src="{{ $bannerImageRight }}" alt="{{ $bannerEyebrow }}" onerror="this.onerror=null;this.style.display='none';">
            </div>
        </div>

        <!-- Descriptive intro paragraph below the banner -->
        <div class="banner-sub-description">
            <p style="margin:0; line-height:1.65; color:#334155;">
                {{ $bannerDescription }}
            </p>
        </div>
    </div>
</section>

<!-- 2. TOOLBAR, SEARCH DOCK & CONTROLS -->
<section class="catalog-toolbar-section">
    <div class="catalog-toolbar-wrap">
        <!-- Search Input -->
        <form action="{{ route('products.index') }}" method="GET" class="catalog-search-box" id="searchFilterForm">
            @foreach((array) request('category') as $catVal)
                <input type="hidden" name="category[]" value="{{ $catVal }}">
            @endforeach
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            @if(request('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif

            <i class="fa-solid fa-magnifying-glass search-icon-left"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search models, capacities, or keywords (e.g. CY 224, 0.1mg, touch)..." class="catalog-search-input" aria-label="Search instruments">
            @if(request('q'))
                <button type="button" class="search-clear-btn" onclick="window.location.href='{{ route('products.index', array_filter(['category' => request('category'), 'sort' => request('sort'), 'per_page' => request('per_page')])) }}'" title="Clear search">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            @endif
        </form>

        <!-- Right Side: Sort, View Per Page & Mobile Filter Toggle -->
        <div class="catalog-toolbar-right">
            <!-- Mobile Filters Toggle -->
            <button type="button" class="btn-mobile-filters-toggle" onclick="toggleSidebar(true)">
                <i class="fa-solid fa-sliders"></i> Filters
            </button>

            <!-- View per page Dropdown -->
            <div class="sort-dropdown-wrap">
                <span>View</span>
                <select name="per_page" class="sort-select" onchange="applyPerPage(this.value)">
                    <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12 per page</option>
                    <option value="24" {{ request('per_page', 12) == 24 ? 'selected' : '' }}>24 per page</option>
                    <option value="48" {{ request('per_page', 12) == 48 ? 'selected' : '' }}>48 per page</option>
                    <option value="96" {{ request('per_page', 12) == 96 ? 'selected' : '' }}>96 per page</option>
                </select>
            </div>

            <!-- Sequence / Sort Dropdown -->
            <div class="sort-dropdown-wrap">
                <span>Sort by:</span>
                <select name="sort" class="sort-select" onchange="applySort(this.value)">
                    <option value="sequence" {{ request('sort', 'sequence') == 'sequence' ? 'selected' : '' }}>Catalog Sequence (Default)</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A &rarr; Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z &rarr; A</option>
                    <option value="model_asc" {{ request('sort') == 'model_asc' ? 'selected' : '' }}>Model Number</option>
                </select>
            </div>

            <!-- Model Counter (matching image1c.png) -->
            <span class="catalog-counter-badge" id="topToolbarCounter" style="background:#F1F5F9; color:#0F172A; border:1px solid #E2E8F0; font-size:0.86rem; padding:6px 14px; border-radius:20px;">
                <strong>{{ $products->total() }} Results</strong>
            </span>

            @if(request()->hasAny(['q', 'category', 'categories', 'subcategory', 'series', 'type', 'capacity', 'readability', 'display', 'class', 'calibration', 'sensor', 'weighing_sensor', 'readability_mc', 'density_resolution', 'oiml_class', 'nominal_value', 'sort']) || !empty($selectedSubSlugs))
                <a href="{{ route('products.index') }}" class="btn-reset-filters-pill" title="Reset all applied filters">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif
        </div>
    </div>

    <!-- Active Filter Chips (supporting all 13 filters) -->
    @if(request()->hasAny(['category', 'categories', 'subcategory', 'series', 'type', 'capacity', 'readability', 'display', 'class', 'calibration', 'sensor', 'weighing_sensor', 'readability_mc', 'density_resolution', 'oiml_class', 'nominal_value', 'q']) || !empty($selectedSubSlugs))
        <div class="active-chips-strip">
            <span style="font-size:0.75rem; color:#64748B; font-weight:700;">Applied Filters:</span>
            @foreach($selectedSubSlugs as $slug)
                @php
                    $cbItem = collect($categoryCheckboxes)->firstWhere('slug', $slug);
                    $cbTitle = $cbItem ? $cbItem['name'] : $slug;
                    $remaining = array_values(array_diff($selectedSubSlugs, [$slug]));
                @endphp
                <a href="{{ request()->fullUrlWithQuery(['category' => $remaining ?: null, 'categories' => null]) }}" class="filter-chip">
                    Category: {{ $cbTitle }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @if(request('q'))
                <a href="{{ request()->fullUrlWithoutQuery(['q']) }}" class="filter-chip">
                    Keyword: "{{ request('q') }}" <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
            @foreach((array) request('series') as $s)
                <a href="{{ request()->fullUrlWithQuery(['series' => array_diff((array) request('series'), [$s])]) }}" class="filter-chip">
                    Series: {{ $s }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('type') as $tVal)
                <a href="{{ request()->fullUrlWithQuery(['type' => array_diff((array) request('type'), [$tVal])]) }}" class="filter-chip">
                    Type: {{ $tVal }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('capacity') as $cap)
                <a href="{{ request()->fullUrlWithQuery(['capacity' => array_diff((array) request('capacity'), [$cap])]) }}" class="filter-chip">
                    Capacity: {{ str_replace('_', ' ', $cap) }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('readability') as $r)
                <a href="{{ request()->fullUrlWithQuery(['readability' => array_diff((array) request('readability'), [$r])]) }}" class="filter-chip">
                    Readability: {{ $r }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('display') as $disp)
                <a href="{{ request()->fullUrlWithQuery(['display' => array_diff((array) request('display'), [$disp])]) }}" class="filter-chip">
                    Display: {{ $disp }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('class') as $cls)
                <a href="{{ request()->fullUrlWithQuery(['class' => array_diff((array) request('class'), [$cls])]) }}" class="filter-chip">
                    Class: {{ $cls }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('calibration') as $cal)
                <a href="{{ request()->fullUrlWithQuery(['calibration' => array_diff((array) request('calibration'), [$cal])]) }}" class="filter-chip">
                    Calibration: {{ $cal }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('sensor', request('weighing_sensor')) as $sen)
                <a href="{{ request()->fullUrlWithQuery(['sensor' => array_diff((array) request('sensor', request('weighing_sensor')), [$sen])]) }}" class="filter-chip">
                    Sensor: {{ $sen }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('readability_mc') as $rmc)
                <a href="{{ request()->fullUrlWithQuery(['readability_mc' => array_diff((array) request('readability_mc'), [$rmc])]) }}" class="filter-chip">
                    MC: {{ $rmc }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('density_resolution') as $dr)
                <a href="{{ request()->fullUrlWithQuery(['density_resolution' => array_diff((array) request('density_resolution'), [$dr])]) }}" class="filter-chip">
                    Density: {{ $dr }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('oiml_class') as $oc)
                <a href="{{ request()->fullUrlWithQuery(['oiml_class' => array_diff((array) request('oiml_class'), [$oc])]) }}" class="filter-chip">
                    OIML: {{ $oc }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach
            @foreach((array) request('nominal_value') as $nv)
                <a href="{{ request()->fullUrlWithQuery(['nominal_value' => array_diff((array) request('nominal_value'), [$nv])]) }}" class="filter-chip">
                    Nominal: {{ $nv }} <i class="fa-solid fa-xmark"></i>
                </a>
            @endforeach

            <a href="{{ route('products.index') }}" style="font-size:0.75rem; color:#EF4444; font-weight:700; text-decoration:none; margin-left:6px;">
                Clear All
            </a>
        </div>
    @endif
</section>

<!-- Mobile Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar(false)"></div>

<!-- 3. MAIN WORKSPACE: SIDEBAR & PRODUCT CARDS GRID -->
<div class="catalog-workspace">

    <!-- LEFT SIDEBAR: DYNAMIC FILTERS (Exact 8 Document Subcategories with Checkboxes) -->
    <aside class="catalog-sidebar" id="catalogSidebar">
        <div class="sidebar-header">
            <h3><i class="fa-solid fa-sliders"></i> Filter Catalog</h3>
            <div style="display:flex; align-items:center; gap:8px;">
                @if(request()->hasAny(['category', 'categories', 'subcategory', 'series', 'type', 'capacity', 'readability', 'display', 'class', 'calibration']) || !empty($selectedSubSlugs))
                    <a href="{{ route('products.index') }}" style="font-size:0.75rem; color:#EF4444; font-weight:700; text-decoration:none;">
                        Clear All
                    </a>
                @endif
                <button type="button" class="btn-close-sidebar" onclick="toggleSidebar(false)" style="background:none; border:none; color:#64748B; font-size:1.1rem; cursor:pointer; display:none;" id="mobileSidebarClose">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- 1. CATEGORY -->
        <div class="filter-group" id="filterGroupCategory">
            <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span class="filter-group-title">Category</span>
                    <span class="cat-count-circle-badge" id="catCountBadge" style="{{ empty($selectedSubSlugs) ? 'display:none;' : '' }}">{{ count($selectedSubSlugs) }}</span>
                    <i class="fa-solid fa-circle-info" style="font-size:12px; color:#00829B; margin-left:2px;" title="Laboratory Balances Categories"></i>
                </div>
                <i class="fa-solid fa-chevron-down filter-chevron"></i>
            </div>
            <div class="filter-group-body" id="categoryCheckboxesBody">
                @foreach($categoryCheckboxes as $cItem)
                    <label class="filter-item-label category-filter-item">
                        <div class="filter-checkbox-wrap">
                            <input type="checkbox" 
                                   class="category-filter-checkbox"
                                   name="category[]" 
                                   value="{{ $cItem['slug'] }}" 
                                   {{ $cItem['checked'] ? 'checked' : '' }} 
                                   onchange="handleCategoryFilterChange()">
                            <span class="category-name-text {{ $cItem['checked'] ? 'is-checked-text' : '' }}">{{ $cItem['name'] }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Dynamic Facet Groups (Series, Types, Capacity, etc.) -->
        <div id="sidebarFacetGroups">
            @include('frontend.products.partials.sidebar_facets')
        </div>
    </aside>

    <!-- RIGHT MAIN CONTENT: PRODUCTS GRID -->
    <main class="catalog-products-area">
        <!-- Active Filter Chips Area (Matching Image 2: Category: Microbalances v   Clear All) -->
        <div id="activeChipsContainer">
            @include('frontend.products.partials.active_filter_chips')
        </div>

        <div class="catalog-results-bar">
            <span class="catalog-results-count-text" id="resultsCountText">
                {{ $products->total() }} Results
            </span>
            <div class="view-per-page-wrap">
                <label for="perPageSelect" class="view-per-page-label">
                    <span id="perPageCurrentLabel">View {{ request('per_page', 12) }} per page</span>
                    <i class="fa-solid fa-chevron-down" style="font-size:11px;"></i>
                </label>
                <select id="perPageSelect" onchange="applyPerPageAjax(this.value)" aria-label="View per page">
                    <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12 per page</option>
                    <option value="24" {{ request('per_page', 12) == 24 ? 'selected' : '' }}>24 per page</option>
                    <option value="48" {{ request('per_page', 12) == 48 ? 'selected' : '' }}>48 per page</option>
                    <option value="96" {{ request('per_page', 12) == 96 ? 'selected' : '' }}>96 per page</option>
                </select>
            </div>
        </div>

        <div class="catalog-cards-grid" id="catalogCardsGrid">
            @include('frontend.products.partials.product_cards_list')
        </div>

        <!-- 4. PAGINATION -->
        <div id="catalogPaginationWrap" style="margin-top: 36px; width: 100%;">
            @if($products->hasPages())
                {{ $products->links() }}
            @endif
        </div>
    </main>

</div>
@endif

<!-- 5. FLOATING SMART COMPARISON DOCK (X/10) -->
<div class="floating-compare-dock" id="floatingCompareDock">
    <div class="compare-dock-container">
        <!-- Thumbnails Reel (Image, turquoise product title, remove x, and divider line |) -->
        <div class="compare-dock-left-wrap" id="compareDockThumbs"></div>

        <div class="compare-dock-actions">
            <span class="compare-dock-count-text" id="compareDockCountText">Compare Products (0/10)</span>
            <button type="button" class="btn-dock-compare" onclick="openComparisonModal()">
                Compare
            </button>
        </div>
    </div>
</div>

<!-- 6. CATEGORY INCOMPATIBILITY MODAL -->
<div class="modal-backdrop" id="categoryIncompatibleModal" onclick="if(event.target === this) closeModal('categoryIncompatibleModal')">
    <div class="modal-incompatible-card">
        <div class="incompatible-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h4>Category Mismatch</h4>
        <p id="incompatibleModalMsg">
            The selected product belongs to a different category and cannot be added to this comparison. Please select a similar product.
        </p>
        <button type="button" class="btn-primary" style="width:100%; justify-content:center; padding:12px;" onclick="closeModal('categoryIncompatibleModal')">
            Understood
        </button>
    </div>
</div>

<!-- 7. SIDE-BY-SIDE COMPARISON MATRIX MODAL (Matching Screenshot 2) -->
<div class="modal-backdrop" id="comparisonMatrixModal">
    <div class="modal-comparison-window">
        <div class="modal-compare-head">
            <h3 id="compareModalCategoryTitle">
                Laboratory Balance
            </h3>
            <div class="modal-compare-actions">
                <button type="button" class="btn-compare-head-action" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Print
                </button>
                <a href="#" id="btnDownloadComparePdf" target="_blank" class="btn-compare-head-action">
                    <i class="fa-solid fa-file-pdf"></i> Download PDF
                </a>
                <button type="button" class="btn-compare-head-close" onclick="closeModal('comparisonMatrixModal')" title="Close">
                    &times;
                </button>
            </div>
        </div>

        <div class="modal-compare-scroll-area">
            <div id="compareMatrixContainer">
                <!-- Dynamically injected comparison matrix -->
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT: FILTER LOGIC & SMART COMPARISON (10-ITEM VALIDATION, PRINT, PDF) -->
<script>
    // -------------------------------------------------------------
    // ACCORDION TOGGLE IN SIDEBAR
    // -------------------------------------------------------------
    function toggleFilterGroup(headerEl) {
        const group = headerEl.closest('.filter-group');
        if (group) group.classList.toggle('collapsed');
    }

    // -------------------------------------------------------------
    // MOBILE SIDEBAR TOGGLE
    // -------------------------------------------------------------
    function toggleSidebar(open) {
        const sidebar = document.getElementById('catalogSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const closeBtn = document.getElementById('mobileSidebarClose');
        if (open) {
            sidebar.classList.add('open');
            backdrop.classList.add('active');
            if (closeBtn) closeBtn.style.display = 'block';
        } else {
            sidebar.classList.remove('open');
            backdrop.classList.remove('active');
            if (closeBtn) closeBtn.style.display = 'none';
        }
    }

    // -------------------------------------------------------------
    // AJAX FILTERING ENGINE (NO PAGE RELOAD)
    // -------------------------------------------------------------
    async function applyFiltersAjax(targetUrl, updateHistory = true) {
        const grid = document.getElementById('catalogCardsGrid');
        if (grid) grid.classList.add('is-loading');

        try {
            const urlObj = new URL(targetUrl, window.location.origin);
            urlObj.searchParams.set('ajax', '1');

            const response = await fetch(urlObj.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }

            const data = await response.json();

            // 1. Update browser history/URL if requested
            if (updateHistory) {
                const cleanUrl = new URL(targetUrl, window.location.origin);
                cleanUrl.searchParams.delete('ajax');
                window.history.pushState({ path: cleanUrl.toString() }, '', cleanUrl.toString());
            }

            // 2. Update Product Cards Grid
            if (grid && data.cards_html !== undefined) {
                grid.innerHTML = data.cards_html;
            }

            // 3. Update Pagination Wrap
            const pagWrap = document.getElementById('catalogPaginationWrap');
            if (pagWrap && data.pagination_html !== undefined) {
                pagWrap.innerHTML = data.pagination_html;
                bindPaginationAjaxClicks();
            }

            // 4. Update Results Counts
            const resultsCountText = document.getElementById('resultsCountText');
            if (resultsCountText && data.results_text) {
                resultsCountText.textContent = data.results_text;
            }
            const topCounter = document.getElementById('topToolbarCounter');
            if (topCounter && data.results_text) {
                topCounter.innerHTML = '<strong>' + data.results_text + '</strong>';
            }

            // 5. Update Category Count Circle Badge
            const catBadge = document.getElementById('catCountBadge');
            if (catBadge) {
                if (data.selected_category_count > 0) {
                    catBadge.textContent = data.selected_category_count;
                    catBadge.style.display = 'inline-flex';
                } else {
                    catBadge.style.display = 'none';
                }
            }

            // 6. Update Active Chips
            const chipsWrap = document.getElementById('activeChipsContainer');
            if (chipsWrap && data.chips_html !== undefined) {
                chipsWrap.innerHTML = data.chips_html;
            }

            // 7. Update Sidebar Facets (preserving user open/collapsed states)
            const facetGroups = document.getElementById('sidebarFacetGroups');
            if (facetGroups && data.sidebar_facets_html !== undefined) {
                const openGroupIds = [];
                facetGroups.querySelectorAll('.filter-group').forEach(fg => {
                    if (!fg.classList.contains('collapsed') && fg.id) {
                        openGroupIds.push(fg.id);
                    }
                });
                facetGroups.innerHTML = data.sidebar_facets_html;
                if (openGroupIds.length > 0) {
                    openGroupIds.forEach(id => {
                        const fg = document.getElementById(id);
                        if (fg) fg.classList.remove('collapsed');
                    });
                }
            }

            // 8. Update Category Checkbox styles
            syncCategoryCheckboxUI(data.selected_category_slugs || []);

            // 9. Re-sync Compare Dock checkboxes
            syncCheckboxesWithStorage();

            // 10. Update Hero Banner and Breadcrumbs dynamically
            if (data.banner_eyebrow !== undefined) {
                const eyebrowEl = document.querySelector('.banner-category-eyebrow');
                if (eyebrowEl) eyebrowEl.textContent = data.banner_eyebrow;
            }
            if (data.banner_headline !== undefined) {
                const headlineEl = document.querySelector('.banner-balance-headline');
                if (headlineEl) headlineEl.textContent = data.banner_headline;
            }
            if (data.banner_description !== undefined) {
                const descEl = document.querySelector('.banner-sub-description p');
                if (descEl) descEl.textContent = data.banner_description;
            }
            if (data.banner_image_left !== undefined) {
                const imgLeftEl = document.querySelector('.banner-balance-stage img');
                if (imgLeftEl) imgLeftEl.src = data.banner_image_left;
            }
            if (data.breadcrumbs_html !== undefined) {
                const breadcrumbNav = document.querySelector('.catalog-breadcrumbs');
                if (breadcrumbNav) {
                    breadcrumbNav.outerHTML = data.breadcrumbs_html;
                }
            }
            if (data.quote_topic) {
                const safeTopic = data.quote_topic.replace(/'/g, "\\'");
                const reqBtn = document.querySelector('.btn-banner-req-info');
                if (reqBtn) reqBtn.setAttribute('onclick', `openQuoteModal('General Inquiry: ${safeTopic}')`);
                const quoteBtn = document.querySelector('.btn-banner-get-quote');
                if (quoteBtn) quoteBtn.setAttribute('onclick', `openQuoteModal('Quote Request: ${safeTopic}')`);
            }

        } catch (err) {
            console.error('AJAX Filter failed:', err);
            // Fallback to regular navigation
            window.location.href = targetUrl;
        } finally {
            if (grid) grid.classList.remove('is-loading');
        }
    }

    function syncCategoryCheckboxUI(selectedSlugs) {
        document.querySelectorAll('.category-filter-checkbox').forEach(cb => {
            const isChecked = selectedSlugs.includes(cb.value);
            cb.checked = isChecked;
            const textSpan = cb.closest('.filter-checkbox-wrap')?.querySelector('.category-name-text');
            if (textSpan) {
                if (isChecked) {
                    textSpan.classList.add('is-checked-text');
                } else {
                    textSpan.classList.remove('is-checked-text');
                }
            }
        });
    }

    function handleCategoryFilterChange() {
        const checkedBoxes = Array.from(document.querySelectorAll('.category-filter-checkbox:checked'));
        const currentUrl = new URL(window.location.href);
        const params = new URLSearchParams(currentUrl.search);

        // Remove old category params
        params.delete('category');
        params.delete('category[]');
        params.delete('categories');
        params.delete('categories[]');
        params.delete('subcategory');
        params.delete('page');

        if (checkedBoxes.length > 0) {
            checkedBoxes.forEach(cb => {
                params.append('category[]', cb.value);
            });
        }

        currentUrl.search = params.toString();
        applyFiltersAjax(currentUrl.toString(), true);
    }

    function removeCategoryFilterAjax(slug) {
        const cb = document.querySelector(`.category-filter-checkbox[value="${slug}"]`);
        if (cb) {
            cb.checked = false;
        }
        handleCategoryFilterChange();
    }

    function clearAllCategoryFiltersAjax() {
        document.querySelectorAll('.category-filter-checkbox').forEach(cb => {
            cb.checked = false;
        });
        handleCategoryFilterChange();
    }

    function toggleExtraCapacities(btn) {
        const extraItems = document.querySelectorAll('.capacity-extra-item');
        if (!extraItems.length) return;
        const isHidden = extraItems[0].style.display === 'none' || extraItems[0].style.display === '';
        extraItems.forEach(el => {
            el.style.display = isHidden ? 'flex' : 'none';
        });
        btn.textContent = isHidden ? 'Show Less...' : 'Show more...';
    }

    function removeFilterChipAjax(paramKey, val) {
        toggleFilterParam(paramKey, val, false);
    }

    function clearAllFiltersAjax() {
        const url = new URL(window.location.href);
        url.search = '';
        applyFiltersAjax(url.toString(), true);
    }

    function toggleFilterParam(paramKey, val, checked) {
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        
        let existing = params.getAll(paramKey + '[]');
        if (existing.length === 0) {
            existing = params.getAll(paramKey);
        }

        if (checked) {
            if (!existing.includes(val)) {
                params.append(paramKey + '[]', val);
            }
        } else {
            params.delete(paramKey);
            params.delete(paramKey + '[]');
            existing.filter(v => v !== val).forEach(v => {
                params.append(paramKey + '[]', v);
            });
        }
        params.delete('page');
        url.search = params.toString();
        applyFiltersAjax(url.toString(), true);
    }

    function applySort(val) {
        applySortAjax(val);
    }

    function applySortAjax(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', val);
        url.searchParams.delete('page');
        applyFiltersAjax(url.toString(), true);
    }

    function applyPerPage(val) {
        applyPerPageAjax(val);
    }

    function applyPerPageAjax(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', val);
        url.searchParams.delete('page');
        const lbl = document.getElementById('perPageCurrentLabel');
        if (lbl) lbl.textContent = 'View ' + val + ' per page';
        applyFiltersAjax(url.toString(), true);
    }

    function bindPaginationAjaxClicks() {
        const pagWrap = document.getElementById('catalogPaginationWrap');
        if (!pagWrap) return;
        pagWrap.querySelectorAll('a.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                applyFiltersAjax(this.href, true);
                const area = document.querySelector('.catalog-products-area');
                if (area) {
                    window.scrollTo({ top: area.offsetTop - 80, behavior: 'smooth' });
                }
            });
        });
    }

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        const targetUrl = window.location.href;
        const currentUrl = new URL(targetUrl);
        const params = currentUrl.searchParams;
        let selectedSlugs = params.getAll('category[]').concat(params.getAll('category'));
        syncCategoryCheckboxUI(selectedSlugs);
        applyFiltersAjax(targetUrl, false);
    });

    // -------------------------------------------------------------
    // SMART COMPARISON STORAGE & COMPATIBILITY VALIDATION
    // -------------------------------------------------------------
    const STORAGE_KEY = 'aczet_comparison_items_v2';

    function getComparisonItems() {
        try {
            let items = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            return items.map(it => {
                if (!it.comparisonGroup) {
                    const cb = document.querySelector(`.product-compare-checkbox[data-id="${it.id}"]`);
                    if (cb && cb.dataset.comparisonGroup) {
                        it.comparisonGroup = cb.dataset.comparisonGroup;
                    } else if (it.rootCategory === 1 || it.rootCategory === '1' || it.rootCategory === 'laboratory-balances') {
                        it.comparisonGroup = 'laboratory-balances';
                    } else {
                        it.comparisonGroup = it.rootCategory || 'general';
                    }
                }
                return it;
            });
        } catch (e) {
            return [];
        }
    }

    function saveComparisonItems(items) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        updateCompareDockUI();
        syncCheckboxesWithStorage();
    }

    function handleCompareCheckbox(cb) {
        let items = getComparisonItems();
        const prodId = cb.dataset.id;
        const model = cb.dataset.model;
        const name = cb.dataset.name;
        const image = cb.dataset.image;
        const comparisonGroup = cb.dataset.comparisonGroup || cb.dataset.rootCategory;
        const comparisonGroupName = cb.dataset.comparisonGroupName || 'Similar Products';
        const categoryName = cb.dataset.categoryName || comparisonGroupName;

        if (cb.checked) {
            // Category Validation Rule:
            // Only products from the same or similar product category can be compared.
            // If the user attempts to select an unrelated product, the system will not allow the selection and will display an appropriate message.
            if (items.length > 0) {
                const firstGroup = items[0].comparisonGroup || items[0].rootCategory;
                if (firstGroup && comparisonGroup && String(firstGroup) !== String(comparisonGroup)) {
                    cb.checked = false;
                    const msgEl = document.getElementById('incompatibleModalMsg');
                    if (msgEl) {
                        msgEl.innerHTML = 
                            'The selected product belongs to a different category (<strong>' + categoryName + '</strong>) and cannot be added to this comparison. Please select a similar product.';
                    }
                    openModal('categoryIncompatibleModal');
                    return;
                }
            }

            // Max 10 products limit
            if (items.length >= 10) {
                cb.checked = false;
                alert('You can compare up to 10 products at a time.');
                return;
            }

            // Add product to list
            if (!items.some(it => String(it.id) === String(prodId))) {
                items.push({ 
                    id: prodId, 
                    model, 
                    name, 
                    image, 
                    rootCategory: comparisonGroup,
                    comparisonGroup: comparisonGroup,
                    comparisonGroupName: comparisonGroupName,
                    categoryName: categoryName 
                });
            }
        } else {
            items = items.filter(it => String(it.id) !== String(prodId));
        }

        saveComparisonItems(items);
    }

    function clearAllComparison() {
        localStorage.removeItem(STORAGE_KEY);
        updateCompareDockUI();
        syncCheckboxesWithStorage();
    }

    function removeCompareItem(prodId) {
        let items = getComparisonItems();
        items = items.filter(it => String(it.id) !== String(prodId));
        saveComparisonItems(items);

        // If comparison matrix modal is open, dynamically remove column and sync PDF link
        const modal = document.getElementById('comparisonMatrixModal');
        if (modal && modal.classList.contains('active')) {
            const colTh = document.getElementById(`compare-col-prod-${prodId}`);
            if (colTh) {
                const parentTr = colTh.parentElement;
                const colIndex = Array.from(parentTr.children).indexOf(colTh);
                colTh.remove();

                const tbodyRows = document.querySelectorAll('#compareTable tbody tr');
                tbodyRows.forEach(row => {
                    if (row.children[colIndex]) {
                        row.children[colIndex].remove();
                    }
                });

                const pdfLink = document.getElementById('btnDownloadComparePdf');
                if (pdfLink) {
                    const remainingIds = items.map(it => it.id).join(',');
                    if (remainingIds) {
                        pdfLink.href = '{{ route('compare.pdf') }}?ids=' + remainingIds;
                    }
                }
            }

            if (items.length === 0) {
                const container = document.getElementById('compareMatrixContainer');
                if (container) {
                    container.innerHTML = `
                        <div class="compare-empty-state">
                            <div class="compare-empty-icon">
                                <i class="fa-solid fa-code-compare"></i>
                            </div>
                            <h3>No Products Selected</h3>
                            <p>Please select at least 2 similar products from the catalog to compare their technical specifications side-by-side.</p>
                        </div>
                    `;
                }
            }
        }
    }

    function updateCompareDockUI() {
        const dock = document.getElementById('floatingCompareDock');
        const countText = document.getElementById('compareDockCountText');
        const thumbsBox = document.getElementById('compareDockThumbs');
        const items = getComparisonItems();

        if (countText) countText.innerText = `Compare Products (${items.length}/10)`;

        if (items.length > 0) {
            dock.classList.add('visible');
            document.body.classList.add('has-compare-dock');
            if (thumbsBox) {
                thumbsBox.innerHTML = items.map((it, idx) => `
                    <div class="compare-dock-item-pill">
                        <img src="${it.image}" alt="${it.name || it.model}" class="compare-dock-item-img" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                        <span class="compare-dock-item-title">${it.name || it.model}</span>
                        <button type="button" class="btn-remove-compare-item" onclick="removeCompareItem('${it.id}')" title="Remove">&times;</button>
                        ${idx < items.length - 1 ? '<div class="compare-dock-divider"></div>' : ''}
                    </div>
                `).join('');
            }
        } else {
            dock.classList.remove('visible');
            document.body.classList.remove('has-compare-dock');
        }
    }

    function syncCheckboxesWithStorage() {
        const items = getComparisonItems();
        const ids = items.map(it => String(it.id));
        document.querySelectorAll('.product-compare-checkbox').forEach(cb => {
            cb.checked = ids.includes(String(cb.dataset.id));
        });
    }

    // -------------------------------------------------------------
    // MODAL COMPARISON MATRIX GENERATOR (Matching Screenshot 2)
    // -------------------------------------------------------------
    async function openComparisonModal() {
        const items = getComparisonItems();
        if (items.length < 2) {
            alert('Please select at least 2 products to compare.');
            return;
        }

        const ids = items.map(it => it.id).join(',');
        const container = document.getElementById('compareMatrixContainer');
        const modalTitle = document.getElementById('compareModalCategoryTitle');
        const pdfLink = document.getElementById('btnDownloadComparePdf');

        container.innerHTML = '<div style="text-align:center; padding:60px; color:#64748B;"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><p style="margin-top:12px; font-weight:600;">Loading comparison matrix...</p></div>';
        openModal('comparisonMatrixModal');

        try {
            const response = await fetch('{{ route('compare') }}?ids=' + ids, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('HTTP error: ' + response.status);
            }

            const data = await response.json();
            if (modalTitle && data.category_name) {
                modalTitle.textContent = data.category_name;
            }
            if (pdfLink && data.pdf_url) {
                pdfLink.href = data.pdf_url;
            }
            if (container && data.table_html) {
                container.innerHTML = data.table_html;
            }
        } catch (e) {
            console.error('Failed to load comparison data:', e);
            window.location.href = '{{ route('compare') }}?ids=' + ids;
        }
    }

    function toggleHighlightDiff() {
        const btn = document.getElementById('btnToggleDiff');
        const table = document.getElementById('compareTable');
        if (!table) return;

        btn.classList.toggle('active');
        const isActive = btn.classList.contains('active');

        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td:not(:first-child)');
            if (cells.length > 1) {
                const vals = Array.from(cells).map(c => c.innerText.trim());
                const allSame = vals.every(v => v === vals[0]);
                if (!allSame && isActive) {
                    row.classList.add('diff-highlight');
                } else {
                    row.classList.remove('diff-highlight');
                }
            }
        });
    }

    function exportComparisonPdf() {
        window.print();
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCompareDockUI();
        syncCheckboxesWithStorage();
        bindPaginationAjaxClicks();

        // Handle Breadcrumb link clicks smoothly without losing filter state
        document.addEventListener('click', function(e) {
            const link = e.target.closest('.catalog-breadcrumbs a.breadcrumb-nav-link');
            if (!link) return;

            const slug = link.getAttribute('data-slug');
            if (!slug) return; // e.g. Home link navigates naturally to '/'

            e.preventDefault();

            if (slug === 'laboratory-balances') {
                clearAllCategoryFiltersAjax();
                const url = new URL('{{ route('products.index') }}', window.location.origin);
                url.searchParams.set('category', 'laboratory-balances');
                applyFiltersAjax(url.toString(), true);
                return;
            }

            if (slug === 'laboratory-weighing') {
                clearAllCategoryFiltersAjax();
                const url = new URL('{{ route('products.index') }}', window.location.origin);
                url.searchParams.set('category', 'laboratory-weighing');
                applyFiltersAjax(url.toString(), true);
                return;
            }

            // Subcategory breadcrumb clicked: ensure matching checkbox is selected
            let matched = false;
            document.querySelectorAll('.category-filter-checkbox').forEach(cb => {
                const isMatch = (cb.value === slug || 
                                 cb.value === 'laboratory-balances-' + slug || 
                                 slug === 'laboratory-balances-' + cb.value ||
                                 slug === cb.value);
                cb.checked = isMatch;
                if (isMatch) matched = true;
            });

            if (matched) {
                handleCategoryFilterChange();
            } else {
                const url = new URL('{{ route('products.index') }}', window.location.origin);
                url.searchParams.set('category', slug);
                applyFiltersAjax(url.toString(), true);
            }
        });
    });
</script>
@endsection
