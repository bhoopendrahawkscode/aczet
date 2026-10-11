@extends('layouts.app')

@section('title', ($t['compare_title'] ?? 'Product Comparison Matrix') . ' - Aczet Metrology')

@section('styles')
<style>
    .compare-hero-banner {
        background: #FFFFFF;
        color: #0F172A;
        padding: 40px 0 32px;
        border-bottom: 1px solid #E2E8F0;
        position: relative;
        overflow: hidden;
    }

    .compare-hero-wrap {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 4%;
    }

    .compare-hero-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 800;
        margin: 0 0 8px;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .compare-hero-title i {
        color: #0284C7;
    }

    .compare-hero-sub {
        font-size: 0.95rem;
        color: #64748B;
        margin: 0;
    }

    .compare-container {
        max-width: 1440px;
        margin: 32px auto 80px;
        padding: 0 4%;
    }

    /* Control Toolbar */
    .compare-control-bar {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
    }

    .compare-table-wrapper {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        overflow-x: auto;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.04);
    }

    .table-compare-matrix {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .table-compare-matrix th, .table-compare-matrix td {
        padding: 16px 20px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.88rem;
    }

    .col-feature-label {
        width: 240px;
        background: #F8FAFC;
        position: sticky;
        left: 0;
        z-index: 5;
        font-weight: 700;
        color: #1E293B;
        border-right: 1px solid #E2E8F0;
    }

    .col-product-head {
        min-width: 220px;
        text-align: center;
        vertical-align: top;
        background: #FFFFFF;
        position: relative;
        padding-top: 24px !important;
    }

    .btn-remove-column {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #F1F5F9;
        color: #64748B;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-remove-column:hover {
        background: #EF4444;
        color: #FFFFFF;
    }

    .product-thumb-box {
        background: linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%);
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 12px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-thumb-box img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        filter: drop-shadow(0 4px 10px rgba(15, 23, 42, 0.08));
    }

    .spec-group-header-row td {
        background: #0F172A !important;
        color: #FFFFFF !important;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 800;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 10px 20px;
    }

    .diff-highlight {
        background-color: #FEF3C7 !important;
    }

    .diff-highlight td:first-child {
        background-color: #FDE68A !important;
    }

    @media print {
        .compare-hero-banner, .compare-control-bar, .navbar-container, footer, .btn-remove-column {
            display: none !important;
        }
        .compare-container {
            margin: 0;
            padding: 0;
            max-width: 100%;
        }
        .compare-table-wrapper {
            border: none;
            box-shadow: none;
        }
        .table-compare-matrix th, .table-compare-matrix td {
            font-size: 9pt;
            padding: 6px 10px;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Banner -->
<div class="compare-hero-banner">
    <div class="compare-hero-wrap">
        <h1 class="compare-hero-title">
            <i class="fa-solid fa-code-compare"></i> Equipment Comparison Matrix
        </h1>
        <p class="compare-hero-sub">
            Side-by-side technical evaluation across capacities, resolutions, interfaces, and legal metrology standards. Compare up to 10 products.
        </p>
    </div>
</div>

<div class="compare-container">
    <!-- Category Validation Alert -->
    @if(session('category_error'))
        <div style="background:#FEF2F2; border:1.5px solid #FCA5A5; color:#991B1B; padding:16px 20px; border-radius:14px; margin-bottom:20px; display:flex; align-items:center; gap:14px;">
            <i class="fa-solid fa-triangle-exclamation fa-xl" style="color:#EF4444;"></i>
            <div>
                <strong style="font-size:0.95rem; display:block; margin-bottom:2px;">Category Validation Notice</strong>
                <span style="font-size:0.88rem;">{{ session('category_error') }}</span>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div style="background:#FFFBEB; border:1.5px solid #FCD34D; color:#92400E; padding:14px 18px; border-radius:12px; margin-bottom:20px;">
            <i class="fa-solid fa-circle-info"></i> {{ session('warning') }}
        </div>
    @endif

    <!-- Control Bar -->
    <div class="compare-control-bar">
        <form action="{{ route('compare') }}" method="GET" style="display:flex; gap:10px; align-items:center; flex:1 1 320px;">
            @php
                // Filter dropdown to match category of first product if selected
                $firstGroupKey = $products->first() ? $products->first()->getComparisonGroupKey() : null;
                $selectableProducts = $allProducts->filter(function($p) use ($firstGroupKey, $productIds) {
                    if (in_array($p->id, $productIds)) return false;
                    if (!$firstGroupKey) return true;
                    return $p->getComparisonGroupKey() === $firstGroupKey;
                });
            @endphp
            <select name="add_id" onchange="if(this.value){ window.location.href='{{ route('compare') }}?ids={{ implode(',', $productIds) ? implode(',', $productIds) . ',' : '' }}' + this.value; }" class="form-control" style="padding:10px 14px; font-size:0.88rem; border-radius:10px; border:1.5px solid #CBD5E1;">
                <option value="">+ Add Product to Comparison ({{ count($productIds) }}/10)...</option>
                @foreach($selectableProducts as $p)
                    <option value="{{ $p->id }}">{{ $p->getLocalizedName($currentLocale) }} ({{ $p->model_number }})</option>
                @endforeach
            </select>
        </form>

        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <button type="button" id="btnToggleDiff" onclick="toggleHighlightDiff()" class="btn-secondary" style="background:#F1F5F9; color:#1E293B; border:1px solid #CBD5E1; padding:9px 15px; border-radius:10px; font-weight:700; font-size:0.84rem; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-highlighter"></i> Highlight Differences
            </button>
            <button type="button" onclick="window.print()" class="btn-secondary" style="background:#F1F5F9; color:#1E293B; border:1px solid #CBD5E1; padding:9px 15px; border-radius:10px; font-weight:700; font-size:0.84rem; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-print"></i> Print / PDF Export
            </button>
            <a href="{{ route('compare.pdf', ['ids' => implode(',', $productIds)]) }}" class="btn-secondary" style="background:#00829B; color:#FFFFFF; border:none; padding:9px 15px; border-radius:10px; font-weight:700; font-size:0.84rem; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
            <button type="button" onclick="exportCompareCsv()" class="btn-primary" style="padding:9px 16px; font-size:0.84rem; border-radius:10px; display:inline-flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-file-excel"></i> Excel CSV
            </button>
            <a href="{{ route('products.index') }}" class="btn-secondary" style="background:#F8FAFC; color:#64748B; border:1px solid #E2E8F0; padding:9px 14px; border-radius:10px; font-size:0.84rem; text-decoration:none;">
                Back to Catalog
            </a>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="compare-table-wrapper">
            <table class="table-compare-matrix" id="compareTable">
                <thead>
                    <tr>
                        <th class="col-feature-label">Instrument Feature</th>
                        @foreach($products as $prod)
                            @php
                                $remainingIds = array_diff($productIds, [$prod->id]);
                                $removeUrl = route('compare', ['ids' => implode(',', $remainingIds)]);
                            @endphp
                            <th class="col-product-head">
                                <a href="{{ $removeUrl }}" class="btn-remove-column" title="Remove {{ $prod->model_number }}">&times;</a>
                                
                                <div class="product-thumb-box">
                                    <img src="{{ asset($prod->main_image ?: 'images/products/noimage.jpeg') }}" alt="{{ $prod->model_number }}" onerror="this.onerror=null;this.src='{{ asset('images/products/noimage.jpeg') }}';">
                                </div>
                                
                                <div style="font-family:'Space Grotesk',sans-serif; font-size:0.95rem; font-weight:800; color:#0F172A; margin-bottom:3px;">
                                    {{ $prod->model_number }}
                                </div>
                                <div style="font-size:0.82rem; color:#64748B; margin-bottom:10px; line-height:1.3;">
                                    {{ Str::limit($prod->getLocalizedName($currentLocale), 35) }}
                                </div>
                                
                                <div style="display:flex; gap:6px; justify-content:center; flex-wrap:wrap;">
                                    <a href="{{ route('products.show', $prod->slug) }}" class="btn-secondary" style="padding:5px 10px; font-size:0.75rem; border-radius:6px; text-decoration:none;">
                                        Details
                                    </a>
                                    <button type="button" class="btn-primary" style="padding:5px 10px; font-size:0.75rem; border-radius:6px;" onclick="openQuoteModal('Comparison Quote: {{ $prod->model_number }}', {{ $prod->id }}, '{{ addslashes($prod->model_number) }}')">
                                        RFQ
                                    </button>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-feature-label">Category</td>
                        @foreach($products as $prod)
                            <td style="text-align:center; font-weight:600; color:#0EA5E9;">
                                {{ $prod->category ? $prod->category->getLocalizedName($currentLocale) : '-' }}
                            </td>
                        @endforeach
                    </tr>

                    <tr>
                        <td class="col-feature-label">Overview</td>
                        @foreach($products as $prod)
                            <td style="text-align:center; font-size:0.82rem; color:#64748B; line-height:1.5;">
                                {{ Str::limit($prod->getLocalizedShortDescription($currentLocale), 120) }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Grouped Specifications -->
                    @php
                        $groupedKeys = [];
                        foreach($products as $p) {
                            foreach($p->specifications as $s) {
                                $grp = $s->group_name ?: 'Technical Specifications';
                                $groupedKeys[$grp][$s->spec_key] = true;
                            }
                        }
                    @endphp

                    @foreach($groupedKeys as $grpName => $keys)
                        <tr class="spec-group-header-row">
                            <td colspan="{{ $products->count() + 1 }}">{{ $grpName }}</td>
                        </tr>
                        @foreach(array_keys($keys) as $keyName)
                            <tr>
                                <td class="col-feature-label">{{ $keyName }}</td>
                                @foreach($products as $prod)
                                    @php
                                        $val = $prod->specifications->where('spec_key', $keyName)->first();
                                    @endphp
                                    <td style="text-align:center; font-weight:600; color:#334155;">
                                        {{ $val ? $val->spec_value : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="background:#FFFFFF; border:1px solid #E2E8F0; border-radius:20px; padding:60px 20px; text-align:center;">
            <div style="width:68px; height:68px; border-radius:20px; background:#F1F5F9; color:#94A3B8; display:inline-flex; align-items:center; justify-content:center; font-size:1.8rem; margin-bottom:18px;">
                <i class="fa-solid fa-code-compare"></i>
            </div>
            <h3 style="font-family:'Space Grotesk',sans-serif; font-size:1.3rem; font-weight:800; color:#0F172A; margin:0 0 8px;">
                No Products in Comparison
            </h3>
            <p style="font-size:0.9rem; color:#64748B; max-width:440px; margin:0 auto 20px;">
                Select up to 10 products from the catalog to compare specifications, dimensions, and calibration compliance side-by-side.
            </p>
            <a href="{{ route('products.index') }}" class="btn-primary" style="padding:10px 22px;">
                <i class="fa-solid fa-arrow-left"></i> Browse Equipment Catalog
            </a>
        </div>
    @endif
</div>

<script>
    function toggleHighlightDiff() {
        const btn = document.getElementById('btnToggleDiff');
        const table = document.getElementById('compareTable');
        if (!table) return;

        btn.classList.toggle('active');
        const isActive = btn.classList.contains('active');

        const rows = table.querySelectorAll('tbody tr:not(.spec-group-header-row)');
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

    function exportCompareCsv() {
        let table = document.getElementById('compareTable');
        if (!table) return;
        let rows = table.querySelectorAll('tr');
        let csv = [];
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll('td, th');
            for (let j = 0; j < cols.length; j++) {
                csv.push('"' + cols[j].innerText.replace(/"/g, '""').replace(/\n/g, ' ') + '"');
            }
            csv.push('\n');
        }
        let blob = new Blob([csv.join('')], { type: 'text/csv;charset=utf-8;' });
        let link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Aczet_Equipment_Comparison.csv';
        link.click();
    }
</script>
@endsection
