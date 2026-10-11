{{-- resources/views/frontend/partials/compare_table.blade.php --}}
@php
    $currentLocale = $currentLocale ?? app()->getLocale();
@endphp

@if($products->count() >= 1)
    <div class="compare-scroll-container">
        <table class="table-side-by-side-compare" id="compareTable">
            <thead>
                <tr>
                    <th class="col-spec-title-cell">
                        <span class="compare-header-label">Parameters</span>
                    </th>
                    @foreach($products as $prod)
                        <th class="col-product-card-cell" id="compare-col-prod-{{ $prod->id }}">
                            <button type="button" 
                                    class="btn-remove-compare-col" 
                                    onclick="removeCompareItem('{{ $prod->id }}')" 
                                    title="Remove {{ $prod->model_number }}">&times;</button>
                            
                            <div class="compare-card-image-box">
                                <a href="{{ route('products.show', $prod->slug) }}">
                                    <img src="{{ asset($prod->main_image ?: 'images/products/noimage.jpeg') }}" 
                                         alt="{{ $prod->name }}" 
                                         loading="lazy" 
                                         onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                                </a>
                            </div>

                            <div class="compare-card-spec-link">
                                <a href="{{ route('products.show', $prod->slug) }}">
                                    Specifications - {{ $prod->getLocalizedName($currentLocale) }}
                                </a>
                            </div>

                            <div class="compare-card-quote-wrap">
                                <button type="button" 
                                        class="btn-compare-get-quote" 
                                        onclick="openQuoteModal('Comparison Quote: {{ addslashes($prod->model_number) }}', {{ $prod->id }}, '{{ addslashes($prod->model_number) }}')">
                                    Get a Quote
                                </button>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($specKeys as $keyName)
                    @php
                        // Check if at least one product has this spec value
                        $hasAnyValue = false;
                        foreach($products as $prod) {
                            $sp = $prod->specifications->firstWhere('spec_key', $keyName);
                            if ($sp && trim($sp->spec_value) !== '') {
                                $hasAnyValue = true;
                                break;
                            }
                        }
                    @endphp
                    @if($hasAnyValue)
                        <tr>
                            <td class="compare-row-key-cell">{{ $keyName }}</td>
                            @foreach($products as $prod)
                                @php
                                    $sp = $prod->specifications->firstWhere('spec_key', $keyName);
                                    $valStr = $sp ? trim($sp->spec_value) : '-';
                                @endphp
                                <td class="compare-row-val-cell">
                                    {!! nl2br(e($valStr)) !!}
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="compare-empty-state">
        <div class="compare-empty-icon">
            <i class="fa-solid fa-code-compare"></i>
        </div>
        <h3>No Products Selected</h3>
        <p>Please select at least 2 similar products from the catalog to compare their technical specifications side-by-side.</p>
    </div>
@endif
