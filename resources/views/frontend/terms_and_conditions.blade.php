@extends('layouts.app')

@section('title', ($t['terms_title'] ?? 'Terms & Conditions') . ' - Aczet')

@section('content')
<div class="page-banner">
    <h1>
        {{ $t['terms_title'] ?? 'Terms & Conditions' }}
    </h1>
    <p>
        {{ $t['terms_sub'] ?? 'Commercial Terms, Equipment Warranty & Legal Disclosures.' }}
    </p>
</div>

<div class="container-responsive" style="max-width: 900px;">
    <div style="background: #FFF; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: clamp(20px, 4vw, 40px); box-shadow: var(--shadow-sm); line-height: 1.8; color: #334155;">
        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 10px;">1. Commercial Quotations & Proforma Invoices</h2>
        <p style="margin-bottom: 20px; font-size: 0.95rem;">
            All product specifications, pricing, and freight delivery timelines generated through the consolidated RFQ portal are subject to formal confirmation by authorized Aczet sales engineers.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 10px;">2. Warranty & Technical Support</h2>
        <p style="margin-bottom: 20px; font-size: 0.95rem;">
            Aczet instruments include a standard 12-month manufacturer warranty covering load cells, motorized calibration assemblies, and electronic PCB boards. Warranty claims must be logged through the official Service Registration portal with valid serial numbers.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 10px;">3. Intellectual Property</h2>
        <p style="margin-bottom: 0; font-size: 0.95rem;">
            All product designs, trademarks, MonoTech sensor patents, and technical datasheets are the exclusive property of Aczet Inc. Unauthorized reproduction is strictly prohibited.
        </p>
    </div>
</div>
@endsection
