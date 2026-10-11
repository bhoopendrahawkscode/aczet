{{-- resources/views/frontend/products/partials/active_filter_chips.blade.php --}}
@php
    $hasAnyFilters = (!empty($selectedSubSlugs) && count($selectedSubSlugs) > 0)
        || request()->hasAny(['series', 'type', 'types', 'capacity', 'capacities', 'readability', 'display', 'class', 'calibration', 'sensor', 'weighing_sensor', 'readability_mc', 'density_resolution', 'oiml_class', 'nominal_value', 'q']);
@endphp

@if($hasAnyFilters)
    <div class="active-category-chips-strip" id="activeCategoryChipsWrap">
        @foreach($selectedSubSlugs as $slug)
            @php
                $cbItem = collect($categoryCheckboxes)->firstWhere('slug', $slug);
                $cbTitle = $cbItem ? $cbItem['name'] : ucwords(str_replace(['laboratory-balances-', '-'], ['', ' '], $slug));
            @endphp
            <button type="button" class="active-filter-chip" onclick="removeCategoryFilterAjax('{{ $slug }}')" title="Remove {{ $cbTitle }} filter">
                <span class="chip-label">Category: {{ $cbTitle }}</span>
                <span class="chip-arrow"><i class="fa-solid fa-xmark"></i></span>
            </button>
        @endforeach

        @foreach((array) request('series') as $s)
            @if($s)
                <button type="button" class="active-filter-chip" onclick="removeFilterChipAjax('series', '{{ $s }}')" title="Remove Series: {{ $s }}">
                    <span class="chip-label">Series: {{ $s }}</span>
                    <span class="chip-arrow"><i class="fa-solid fa-xmark"></i></span>
                </button>
            @endif
        @endforeach

        @foreach((array) (request('types') ?: request('type')) as $t)
            @if($t)
                <button type="button" class="active-filter-chip" onclick="removeFilterChipAjax('type', '{{ $t }}')" title="Remove Type: {{ $t }}">
                    <span class="chip-label">Type: {{ $t }}</span>
                    <span class="chip-arrow"><i class="fa-solid fa-xmark"></i></span>
                </button>
            @endif
        @endforeach

        @foreach((array) (request('capacities') ?: request('capacity')) as $c)
            @if($c)
                <button type="button" class="active-filter-chip" onclick="removeFilterChipAjax('capacity', '{{ $c }}')" title="Remove Capacity: {{ $c }}">
                    <span class="chip-label">Capacity: {{ $c }}</span>
                    <span class="chip-arrow"><i class="fa-solid fa-xmark"></i></span>
                </button>
            @endif
        @endforeach

        @foreach((array) request('readability') as $r)
            @if($r)
                <button type="button" class="active-filter-chip" onclick="removeFilterChipAjax('readability', '{{ $r }}')" title="Remove Readability: {{ $r }}">
                    <span class="chip-label">Readability: {{ $r }}</span>
                    <span class="chip-arrow"><i class="fa-solid fa-xmark"></i></span>
                </button>
            @endif
        @endforeach

        @foreach((array) request('display') as $d)
            @if($d)
                <button type="button" class="active-filter-chip" onclick="removeFilterChipAjax('display', '{{ $d }}')" title="Remove Display: {{ $d }}">
                    <span class="chip-label">Display: {{ $d }}</span>
                    <span class="chip-arrow"><i class="fa-solid fa-xmark"></i></span>
                </button>
            @endif
        @endforeach

        @foreach((array) request('class') as $cl)
            @if($cl)
                <button type="button" class="active-filter-chip" onclick="removeFilterChipAjax('class', '{{ $cl }}')" title="Remove Class: {{ $cl }}">
                    <span class="chip-label">Class: {{ $cl }}</span>
                    <span class="chip-arrow"><i class="fa-solid fa-xmark"></i></span>
                </button>
            @endif
        @endforeach

        @if(request('q'))
            <button type="button" class="active-filter-chip" onclick="removeFilterChipAjax('q', '{{ request('q') }}')" title="Remove Keyword">
                <span class="chip-label">Keyword: "{{ request('q') }}"</span>
                <span class="chip-arrow"><i class="fa-solid fa-xmark"></i></span>
            </button>
        @endif

        <button type="button" class="btn-clear-all-chip" onclick="clearAllFiltersAjax()" title="Clear all filters">
            Clear All
        </button>
    </div>
@endif
