@forelse($products as $product)
    @php
        $currentLoc = $currentLocale ?? 'en';
        $prodName = $product->getLocalizedName($currentLoc);
        $compGroupKey = $product->getComparisonGroupKey();
        $compGroupName = $product->getComparisonGroupName();
        $catName = $product->category ? $product->category->name : $compGroupName;
        
        // Extract key specifications for the 3 key rows
        $capacitySpec = $product->specifications->first(function($s) {
            return stripos($s->spec_key, 'capacity') !== false;
        });
        $readabilitySpec = $product->specifications->first(function($s) {
            return stripos($s->spec_key, 'readability') !== false;
        });
        $minWeightSpec = $product->specifications->first(function($s) {
            return stripos($s->spec_key, 'min') !== false || stripos($s->spec_key, 'usp') !== false;
        });
        
        $allSpecs = $product->specifications;
        $spec1Label = 'Weighing Capacity';
        $spec1Val = $capacitySpec ? $capacitySpec->spec_value : ($allSpecs[0]->spec_value ?? '—');
        
        $spec2Label = 'Readability';
        $spec2Val = $readabilitySpec ? $readabilitySpec->spec_value : ($allSpecs[1]->spec_value ?? '—');
        
        $spec3Label = 'Minimum Weight (USP, 0.1%, typical)';
        $spec3Val = $minWeightSpec ? $minWeightSpec->spec_value : ($allSpecs[2]->spec_value ?? '—');
    @endphp
    <article class="catalog-card-v2" id="prod-card-{{ $product->id }}">
        <!-- Left Stage: Clean Product Image (~175px wide) -->
        <div class="card-stage-v2">
            <a href="{{ route('products.show', $product->slug) }}" style="display:flex; align-items:center; justify-content:center; width:100%; height:100%;">
                <img src="{{ asset($product->main_image ?: 'images/products/noimage.jpeg') }}" alt="{{ $prodName }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
            </a>
        </div>

        <!-- Right Content: Title (Cyan/Turquoise), Short Description, 2-Column Key Specs, and Bottom-Right Compare -->
        <div class="card-body-v2">
            <h3 class="card-title-v2">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $prodName }}
                </a>
            </h3>
            
            <p class="card-desc-v2">
                {{ Str::limit($product->getLocalizedShortDescription($currentLoc) ?: $product->description, 320) }}
            </p>

            <div class="card-specs-list">
                <div class="card-spec-row">
                    <span class="spec-k">{{ $spec1Label }}</span>
                    <span class="spec-v">{{ $spec1Val }}</span>
                </div>
                <div class="card-spec-row">
                    <span class="spec-k">{{ $spec2Label }}</span>
                    <span class="spec-v">{{ $spec2Val }}</span>
                </div>
                <div class="card-spec-row">
                    <span class="spec-k">{{ $spec3Label }}</span>
                    <span class="spec-v">{{ $spec3Val }}</span>
                </div>
            </div>

            <div class="card-compare-strip">
                <label class="card-compare-label">
                    <input type="checkbox" class="product-compare-checkbox" 
                           data-id="{{ $product->id }}"
                           data-model="{{ $product->model_number }}"
                           data-name="{{ $prodName }}"
                           data-image="{{ $product->main_image }}"
                           data-root-category="{{ $compGroupKey }}"
                           data-comparison-group="{{ $compGroupKey }}"
                           data-comparison-group-name="{{ $compGroupName }}"
                           data-category-name="{{ $catName }}"
                           onchange="handleCompareCheckbox(this)">
                    <span class="compare-label-text">Compare</span>
                    <span style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;">Add to Compare</span>
                </label>
            </div>

        </div>
    </article>
@empty
    <div style="grid-column:1/-1; background:#FFFFFF; border:1px solid #E2E8F0; border-radius:20px; padding:60px 30px; text-align:center;">
        <div style="width:68px; height:68px; border-radius:18px; background:#F0F9FF; color:#0284C7; display:inline-flex; align-items:center; justify-content:center; font-size:1.8rem; margin-bottom:18px;">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <h3 style="font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size:20px; font-weight:700; color:#000000; margin:0 0 10px;">
            No instruments found matching your criteria.
        </h3>
        <p style="font-size:0.9rem; color:#64748B; max-width:480px; margin:0 auto 24px; line-height:1.6;">
            Try adjusting your filters, selecting a different category, or resetting all search parameters.
        </p>
        <button type="button" class="btn-primary" style="padding:10px 24px; cursor:pointer;" onclick="clearAllCategoryFiltersAjax()">
            <i class="fa-solid fa-rotate-left"></i> Reset All Filters
        </button>
    </div>
@endforelse
