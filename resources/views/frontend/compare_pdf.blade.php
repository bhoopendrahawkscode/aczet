<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Comparison - Aczet Metrology</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 10mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            color: #1F2937;
            margin: 0;
            padding: 0;
        }
        .pdf-header {
            border-bottom: 2px solid #00829B;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: table;
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 8pt;
            color: #6B7280;
        }
        .pdf-title {
            font-size: 14pt;
            font-weight: bold;
            color: #00829B;
            margin: 0;
        }
        .pdf-subtitle {
            font-size: 9pt;
            color: #4B5563;
            margin-top: 2px;
        }
        .compare-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .compare-table th, .compare-table td {
            border: 1px solid #D1D5DB;
            padding: 5px 7px;
            vertical-align: top;
            font-size: 8pt;
        }
        .col-key {
            width: 18%;
            background-color: #F9FAFB;
            font-weight: bold;
            color: #111827;
        }
        .col-product {
            text-align: center;
            background-color: #FFFFFF;
        }
        .prod-title {
            font-weight: bold;
            color: #00829B;
            font-size: 8.5pt;
            margin-top: 4px;
        }
        .prod-model {
            font-size: 7.5pt;
            color: #4B5563;
            margin-bottom: 4px;
        }
        .prod-img {
            max-height: 65px;
            max-width: 90px;
            object-fit: contain;
        }
        .pdf-footer {
            margin-top: 14px;
            border-top: 1px solid #E5E7EB;
            padding-top: 6px;
            font-size: 7.5pt;
            color: #9CA3AF;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="pdf-header">
        <div class="header-left">
            <h1 class="pdf-title">{{ $comparisonCategoryTitle ?? 'Laboratory Balance' }} - Technical Comparison</h1>
            <div class="pdf-subtitle">Aczet Precision Scientific Metrology & Weighing Solutions</div>
        </div>
        <div class="header-right">
            <div>Generated: {{ date('F d, Y') }}</div>
            <div>Official Comparison Sheet</div>
        </div>
    </div>

    <table class="compare-table">
        <thead>
            <tr>
                <th class="col-key">Specification</th>
                @foreach($products as $prod)
                    <th class="col-product">
                        @if($prod->main_image && file_exists(public_path($prod->main_image)))
                            <img src="{{ public_path($prod->main_image) }}" class="prod-img" alt="{{ $prod->name }}">
                        @endif
                        <div class="prod-title">{{ $prod->name }}</div>
                        <div class="prod-model">Model: {{ $prod->model_number }}</div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($specKeys as $keyName)
                @php
                    $hasVal = false;
                    foreach($products as $p) {
                        $sp = $p->specifications->firstWhere('spec_key', $keyName);
                        if ($sp && trim($sp->spec_value) !== '') {
                            $hasVal = true;
                            break;
                        }
                    }
                @endphp
                @if($hasVal)
                    <tr>
                        <td class="col-key">{{ $keyName }}</td>
                        @foreach($products as $prod)
                            @php
                                $sp = $prod->specifications->firstWhere('spec_key', $keyName);
                                $valStr = $sp ? trim($sp->spec_value) : '-';
                            @endphp
                            <td>{!! nl2br(e($valStr)) !!}</td>
                        @endforeach
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="pdf-footer">
        &copy; {{ date('Y') }} Aczet Inc. All Rights Reserved. ISO 9001:2015 & OIML Certified Metrology Instruments. Global Helpline: +91 22 4141 5555 | Email: sales@aczet.com
    </div>
</body>
</html>
