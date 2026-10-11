<!DOCTYPE html>
<html lang="{{ $currentLocale ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <title>Aczet Product Comparison</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #1e293b; line-height: 1.4; }
        .container { padding: 20mm; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #0284C7; }
        .header h1 { font-size: 24px; font-weight: bold; color: #0f172a; margin-bottom: 5px; }
        .header .subtitle { font-size: 12px; color: #64748b; }
        .header .date { font-size: 10px; color: #94a3b8; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 9px; }
        th, td { border: 1px solid #e2e8f0; padding: 4px 6px; text-align: left; vertical-align: top; }
        th { background-color: #f1f5f9; font-weight: bold; color: #334155; }
        th:first-child, td:first-child { width: 180px; font-weight: bold; background-color: #f8fafc; }
        tr:nth-child(even) td { background-color: #f8fafc; }
        .product-header { text-align: center; }
        .product-image { max-width: 60px; max-height: 60px; margin: 0 auto 5px; }
        .product-name { font-weight: bold; font-size: 10px; margin: 3px 0; }
        .product-model { font-size: 9px; color: #0284C7; font-weight: bold; }
        .product-category { font-size: 8px; color: #64748b; text-transform: uppercase; }
        .spec-name { font-weight: bold; color: #334155; }
        .spec-value { font-weight: 500; }
        .spec-value.diff { background-color: #fef3c7; color: #b45309; font-weight: bold; }
        .category-row { background-color: #f1f5f9; }
        .category-row th, .category-row td { background-color: #f1f5f9; font-weight: bold; }
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .diff-badge { display: inline-block; background: #fef3c7; color: #b45309; padding: 1px 4px; border-radius: 3px; font-size: 7px; font-weight: bold; margin-left: 4px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Aczet Product Comparison</h1>
        <div class="subtitle">Precision Weighing & Laboratory Metrology Instruments</div>
        <div class="date">Generated on {{ date('F d, Y') }} | Aczet Metrology</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Specification</th>
                @foreach($products as $product)
                    <th>
                        <div class="product-header">
                            @if($product->main_image)
                                <img src="{{ $product->main_image }}" alt="{{ $product->getLocalizedName($currentLocale) }}" class="product-image">
                            @endif
                            <div class="product-name">{{ $product->getLocalizedName($currentLocale) }}</div>
                            <div class="product-model">{{ $product->model_number }}</div>
                            <div class="product-category">{{ $product->category?->getLocalizedName($currentLocale) }}</div>
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- Category Row -->
            <tr class="category-row">
                <th>Category</th>
                @foreach($products as $product)
                    <td>{{ $product->category?->getLocalizedName($currentLocale) }}</td>
                @endforeach
            </tr>

            <!-- Overview -->
            <tr>
                <td class="spec-name">Overview</td>
                @foreach($products as $product)
                    <td class="spec-value">{{ Str::limit($product->getLocalizedShortDescription($currentLocale), 200) }}</td>
                @endforeach
            </tr>

            <!-- Specifications -->
            @php
                $allSpecKeys = [];
                foreach($products as $p) {
                    foreach($p->specifications as $s) {
                        $allSpecKeys[$s->spec_key] = true;
                    }
                }
                ksort($allSpecKeys);
            @endphp

            @foreach(array_keys($allSpecKeys) as $specKey)
                @php
                    $values = [];
                    foreach($products as $p) {
                        $spec = $p->specifications->where('spec_key', $specKey)->first();
                        $values[$p->id] = $spec ? $spec->spec_value : null;
                    }
                    $uniqueValues = array_unique(array_filter($values));
                    $isDifferent = count($uniqueValues) > 1;
                @endphp
                <tr class="{{ $isDifferent ? 'diff-row' : '' }}">
                    <td class="spec-name {{ $isDifferent ? 'diff' : '' }}">
                        {{ $specKey }}
                        @if($isDifferent)
                            <span class="diff-badge">DIFFERS</span>
                        @endif
                    </td>
                    @foreach($products as $product)
                        <td class="spec-value {{ $isDifferent ? 'diff' : '' }}">
                            {{ $values[$product->id] ?? '—' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach

            <!-- Compliance -->
            <tr class="category-row">
                <th>Compliance & Certifications</th>
                @foreach($products as $product)
                    <td></td>
                @endforeach
            </tr>
            <tr>
                <td class="spec-name">ISO 9001</td>
                @foreach($products as $product)
                    <td class="spec-value">✓ Yes</td>
                @endforeach
            </tr>
            <tr>
                <td class="spec-name">ISO 17025 Calibration</td>
                @foreach($products as $product)
                    <td class="spec-value">Available</td>
                @endforeach
            </tr>
            <tr>
                <td class="spec-name">USP Chapter 41</td>
                @foreach($products as $product)
                    <td class="spec-value">
                        @if(str_contains(strtolower($product->name), 'analytical') || str_contains(strtolower($product->name), 'micro'))
                            ✓ Compliant
                        @else
                            — N/A
                        @endif
                    </td>
                @endforeach
            </tr>
            <tr>
                <td class="spec-name">CE Marked</td>
                @foreach($products as $product)
                    <td class="spec-value">✓ Yes</td>
                @endforeach
            </tr>
            <tr>
                <td class="spec-name">RoHS Compliant</td>
                @foreach($products as $product)
                    <td class="spec-value">✓ Yes</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Aczet Metrology - Precision Weighing & Laboratory Metrology Instruments</p>
        <p>www.aczet.com | sales@aczet.com | +91 22 2847 4800</p>
        <p>This comparison document is for reference only. Specifications subject to change without notice.</p>
    </div>
</div>
</body>
</html>