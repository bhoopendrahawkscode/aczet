<?php

if (!function_exists('vasset')) {
    /**
     * Return asset URL with dynamic file modification timestamp query parameter for cache busting.
     *
     * @param string $path
     * @return string
     */
    function vasset($path)
    {
        if (empty($path)) {
            return asset('images/products/noimage.jpeg');
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }
        $cleanPath = ltrim($path, '/');
        $realPath = public_path($cleanPath);
        $version = file_exists($realPath) ? filemtime($realPath) : time();
        return asset($cleanPath) . '?v=' . $version;
    }
}

if (!function_exists('site_setting')) {
    /**
     * Retrieve a site setting by key with an optional fallback default.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function site_setting($key, $default = null)
    {
        try {
            return \App\Models\SiteSetting::get($key, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }
}
