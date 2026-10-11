<?php

// 1. layouts/app.blade.php
$appBlade = file_get_contents('resources/views/layouts/app.blade.php');
$appBladeNew = preg_replace(
    '/<!-- Modern Global Stylesheet -->\s*<style>.*?<\/style>/s',
    "<!-- Modern Global Stylesheet (External CSS File) -->\n    <link rel=\"stylesheet\" href=\"{{ vasset('css/app.css') }}\">",
    $appBlade
);
if ($appBladeNew !== null && $appBladeNew !== $appBlade) {
    file_put_contents('resources/views/layouts/app.blade.php', $appBladeNew);
    echo "Updated layouts/app.blade.php\n";
} else {
    echo "FAILED layouts/app.blade.php\n";
}

// 2. frontend/home.blade.php
$homeBlade = file_get_contents('resources/views/frontend/home.blade.php');
$homeBladeNew = preg_replace(
    '/@section\(\'styles\'\)\s*<style>.*?<\/style>\s*@endsection/s',
    "@section('styles')\n    <link rel=\"stylesheet\" href=\"{{ vasset('css/home.css') }}\">\n@endsection",
    $homeBlade
);
if ($homeBladeNew !== null && $homeBladeNew !== $homeBlade) {
    file_put_contents('resources/views/frontend/home.blade.php', $homeBladeNew);
    echo "Updated frontend/home.blade.php\n";
} else {
    echo "FAILED frontend/home.blade.php\n";
}

// 3. frontend/products/index.blade.php
$prodIndex = file_get_contents('resources/views/frontend/products/index.blade.php');
$prodIndexNew = preg_replace(
    '/@section\(\'styles\'\)\s*<style>.*?<\/style>\s*@endsection/s',
    "@section('styles')\n    <link rel=\"stylesheet\" href=\"{{ vasset('css/products.css') }}\">\n@endsection",
    $prodIndex
);
if ($prodIndexNew !== null && $prodIndexNew !== $prodIndex) {
    file_put_contents('resources/views/frontend/products/index.blade.php', $prodIndexNew);
    echo "Updated frontend/products/index.blade.php\n";
} else {
    echo "FAILED frontend/products/index.blade.php\n";
}

// 4. frontend/products/show.blade.php
$prodShow = file_get_contents('resources/views/frontend/products/show.blade.php');
$prodShowNew = preg_replace(
    '/@section\(\'styles\'\)\s*<style>.*?<\/style>\s*@endsection/s',
    "@section('styles')\n    <link rel=\"stylesheet\" href=\"{{ vasset('css/product-detail.css') }}\">\n@endsection",
    $prodShow
);

// Clean up prominent inline style attributes in show.blade.php
$replacements = [
    'onclick="window.print()" title="Print Product Datasheet" style="color:#64748B; font-weight:700; font-size:0.8rem; display:inline-flex; align-items:center; gap:5px;"'
        => 'onclick="window.print()" title="Print Product Datasheet" class="btn-print-datasheet"',
    'style="font-size: 16px; font-weight: 700; color: #000000; margin-bottom: 12px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;"'
        => '',
    'style="display:flex; gap:12px; margin-bottom:22px; flex-wrap:wrap;"'
        => '',
    'style="flex:1 1 200px; padding:12px 24px; font-size:14px; font-weight:700;"'
        => '',
    '<span style="font-size:0.75rem; color:#64748B; font-weight:600;">PDF Format</span>'
        => '<span class="p-doc-badge-pdf">PDF Format</span>',
    'class="btn-super-quote" style="padding:10px 18px; font-size:0.82rem; flex:none; box-shadow:none;"'
        => 'class="btn-super-quote btn-consult-specialist"',
    '<span style="font-size:0.75rem; font-weight:800; text-transform:uppercase; color:#0EA5E9; letter-spacing:0.06em; display:block; margin-bottom:4px;">'
        => '<span class="p-tech-slider-badge">',
    '<div class="p-specs-header-controls" onclick="toggleSectionAccordion(\'specsContentArea\', \'specsChevron\')" style="cursor:pointer;">'
        => '<div class="p-specs-header-controls" onclick="toggleSectionAccordion(\'specsContentArea\', \'specsChevron\')">',
    '<h3 style="font-size:1.25rem; font-weight:800; color:#111827; margin:0 0 4px; display:inline-flex; align-items:center; gap:8px;">'
        => '<h3 class="p-specs-header-title">',
    '<p style="color:#64748B; font-size:0.85rem; margin:0;">'
        => '<p class="p-specs-header-desc">',
    '<div style="display:flex; gap:10px;" onclick="event.stopPropagation();">'
        => '<div class="p-specs-header-actions" onclick="event.stopPropagation();">',
    'class="btn-outline-compare" onclick="window.print()" style="padding:8px 16px; font-size:0.84rem;"'
        => 'class="btn-outline-compare btn-print-matrix" onclick="window.print()"',
    '<div class="p-end-cta-actions" style="display:flex; justify-content:center; align-items:center;">'
        => '<div class="p-end-cta-actions">',
    '<div class="modal-incompatible-card" style="text-align:left; max-width:500px; padding:28px;">'
        => '<div class="modal-incompatible-card service-manual-modal-card">',
    '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">'
        => '<div class="service-manual-modal-header">',
    '<h4 style="margin:0; font-size:1.15rem; color:#0F172A; display:flex; align-items:center; gap:8px;">'
        => '<h4>',
    'style="background:none; border:none; color:#64748B; font-size:1.3rem; cursor:pointer;"'
        => 'class="service-manual-modal-close"',
    '<p style="font-size:0.85rem; color:#64748B; line-height:1.6; margin-bottom:18px;">'
        => '<p class="service-manual-modal-desc">',
    '<div style="margin-bottom:14px;">'
        => '<div class="service-manual-form-group">',
    '<label style="display:block; font-size:0.82rem; font-weight:700; color:#1E293B; margin-bottom:6px;">'
        => '<label class="service-manual-label">',
    '<input type="password" id="smPasswordInput" placeholder="Enter security passphrase" style="width:100%; padding:10px 14px; font-size:0.9rem; border-radius:10px; border:1.5px solid #CBD5E1;" required>'
        => '<input type="password" id="smPasswordInput" placeholder="Enter security passphrase" class="service-manual-input" required>',
    '<div id="smAuthError" style="display:none; color:#EF4444; font-size:0.82rem; margin-bottom:12px; font-weight:600;"></div>'
        => '<div id="smAuthError" class="service-manual-error"></div>',
    'class="btn-primary" style="flex:1; justify-content:center; padding:11px; border-radius:10px;"'
        => 'class="btn-primary service-manual-btn-submit"',
    'class="btn-outline" style="padding:11px 16px; border-radius:10px;"'
        => 'class="btn-outline service-manual-btn-cancel"',
    'style="background:none; border:none; color:#94A3B8; font-size:0.78rem; font-weight:700; cursor:pointer;"'
        => 'class="p-recent-clear-btn"',
];

foreach ($replacements as $search => $replace) {
    $prodShowNew = str_replace($search, $replace, $prodShowNew);
}

if ($prodShowNew !== null && $prodShowNew !== $prodShow) {
    file_put_contents('resources/views/frontend/products/show.blade.php', $prodShowNew);
    echo "Updated frontend/products/show.blade.php\n";
} else {
    echo "FAILED frontend/products/show.blade.php\n";
}

