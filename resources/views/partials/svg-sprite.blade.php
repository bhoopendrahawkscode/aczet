<!-- SVG Sprite for Icons -->
@php
    $spritePath = public_path('build/sprite.svg');
    if (file_exists($spritePath)) {
        echo file_get_contents($spritePath);
    }
@endphp