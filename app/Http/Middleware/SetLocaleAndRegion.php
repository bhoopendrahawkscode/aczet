<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SetLocaleAndRegion
{
    public function handle(Request $request, Closure $next): Response
    {
        foreach (['utm_source', 'utm_medium', 'utm_campaign'] as $utmKey) {
            if ($request->filled($utmKey)) {
                session([$utmKey => $request->query($utmKey)]);
            }
        }

        // 1. Region-to-Default-Language Mapping
        $regionLangDefaults = [
            'India' => 'en',
            'GCC' => 'ar',
            'Europe' => 'de',
            'Asia Pacific' => 'en',
            'Thailand' => 'th',
            'USA' => 'en',
        ];

        $allowedRegions = ['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'];
        
        // 2. 12 Supported Languages
        $allowedLocales = [
            'en' => 'English',
            'ar' => 'العربية (Arabic)',
            'es' => 'Español (Spanish)',
            'zh' => '中文 (Chinese)',
            'th' => 'ไทย (Thai)',
            'fr' => 'Français (French)',
            'de' => 'Deutsch (German)',
            'it' => 'Italiano (Italian)',
            'ja' => '日本語 (Japanese)',
            'ru' => 'Русский (Russian)',
            'pt' => 'Português (Portuguese)',
            'ko' => '한국어 (Korean)',
        ];

        // Explicit query parameters (used by automated tests, language switcher & deep links)
        if ($request->has('region') && in_array($request->query('region'), $allowedRegions)) {
            $selectedRegion = $request->query('region');
            session(['aczet_region' => $selectedRegion]);

            if (!$request->has('lang')) {
                $defaultLang = $regionLangDefaults[$selectedRegion] ?? 'en';
                session(['aczet_lang' => $defaultLang]);
            }
        }

        if ($request->has('lang') && array_key_exists($request->query('lang'), $allowedLocales)) {
            session(['aczet_lang' => $request->query('lang')]);
        }

        // Automatically detect language and region from user location if not already in session
        $detected = $this->autoDetectLocationAndLanguage($request);

        if (! session()->has('aczet_region')) {
            session(['aczet_region' => $detected['region']]);
        }

        if (! session()->has('aczet_lang')) {
            session(['aczet_lang' => $detected['locale']]);
        }

        $currentRegion = session('aczet_region', 'India');
        $currentLocale = session('aczet_lang', $regionLangDefaults[$currentRegion] ?? 'en');
        App::setLocale($currentLocale);

        $isRtl = ($currentLocale === 'ar');

        // Load language dictionary from lang/{locale}/messages.php with fallback to English
        $langFile = base_path("lang/{$currentLocale}/messages.php");
        $enFile = base_path("lang/en/messages.php");
        
        $trans = [];
        if (file_exists($langFile)) {
            $trans = require $langFile;
        } elseif (file_exists($enFile)) {
            $trans = require $enFile;
        }
        $fallbackTrans = file_exists($enFile) ? require $enFile : [];
        $mergedTrans = array_merge($fallbackTrans, $trans);

        // Share globally across all Blade views
        View::share([
            'currentRegion' => $currentRegion,
            'allowedRegions' => $allowedRegions,
            'currentLocale' => $currentLocale,
            'allowedLocales' => $allowedLocales,
            'detectedCountryCode' => $detected['countryCode'] ?? 'IN',
            'detectedCountryName' => $detected['countryName'] ?? 'India',
            'detectedFlag' => $detected['flag'] ?? '🇮🇳',
            'detectedRegion' => $detected['region'] ?? 'India',
            'detectedLocale' => $detected['locale'] ?? 'en',
            'isRtl' => $isRtl,
            't' => $mergedTrans,
        ]);

        return $next($request);
    }

    /**
     * Automatically detect user region and locale from geo-headers, client IP, or network gateway.
     */
    private function autoDetectLocationAndLanguage(Request $request): array
    {
        // 1. Detect 2-Letter ISO Country Code from CDN / Cloudflare / Proxy Geo-Headers
        $countryCode = null;
        $headerCandidates = [
            'CF-IPCountry',
            'HTTP_CF_IPCOUNTRY',
            'X-Country-Code',
            'X-Geo-Country',
            'CloudFront-Viewer-Country',
            'GEOIP_COUNTRY_CODE',
        ];

        foreach ($headerCandidates as $header) {
            $val = $request->header($header) ?: $request->server($header);
            if (!empty($val) && strlen(trim($val)) === 2 && strtoupper(trim($val)) !== 'XX') {
                $countryCode = strtoupper(trim($val));
                break;
            }
        }

        // 2. Client IP Lookup via geoip service
        if (!$countryCode) {
            $clientIp = $request->ip();
            $isPublicIp = $clientIp && filter_var(
                $clientIp,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            );

            if ($isPublicIp) {
                $countryCode = Cache::remember('aczet:ip_geo:' . hash('sha256', $clientIp), now()->addDays(7), function () use ($clientIp) {
                    try {
                        $res = Http::timeout(1.5)->acceptJson()->get("http://ip-api.com/json/{$clientIp}?fields=status,countryCode");
                        if ($res->successful() && ($res->json('status') === 'success')) {
                            return strtoupper(trim($res->json('countryCode')));
                        }
                    } catch (\Throwable) {
                    }
                    return null;
                });
            } else {
                // Local / LAN / Dev Network (query gateway public IP once per day)
                $countryCode = Cache::remember('aczet:lan_gateway_geo', now()->addDay(), function () {
                    try {
                        $res = Http::timeout(1.5)->acceptJson()->get('http://ip-api.com/json/?fields=status,countryCode');
                        if ($res->successful() && ($res->json('status') === 'success')) {
                            return strtoupper(trim($res->json('countryCode')));
                        }
                    } catch (\Throwable) {
                    }
                    return null;
                });
            }
        }

        // 3. Browser Accept-Language Analysis for Locale and Country Fallback
        $browserLang = 'en';
        $acceptHeader = strtolower($request->header('Accept-Language', ''));
        if (!empty($acceptHeader)) {
            if (str_contains($acceptHeader, 'de')) {
                $browserLang = 'de';
                if (!$countryCode) $countryCode = 'DE';
            } elseif (str_contains($acceptHeader, 'fr')) {
                $browserLang = 'fr';
                if (!$countryCode) $countryCode = 'FR';
            } elseif (str_contains($acceptHeader, 'ar')) {
                $browserLang = 'ar';
                if (!$countryCode) $countryCode = 'AE';
            } elseif (str_contains($acceptHeader, 'th')) {
                $browserLang = 'th';
                if (!$countryCode) $countryCode = 'TH';
            } elseif (str_contains($acceptHeader, 'zh')) {
                $browserLang = 'zh';
                if (!$countryCode) $countryCode = 'CN';
            } elseif (str_contains($acceptHeader, 'ja')) {
                $browserLang = 'ja';
                if (!$countryCode) $countryCode = 'JP';
            } elseif (str_contains($acceptHeader, 'ko')) {
                $browserLang = 'ko';
                if (!$countryCode) $countryCode = 'KR';
            } elseif (str_contains($acceptHeader, 'es')) {
                $browserLang = 'es';
                if (!$countryCode) $countryCode = 'ES';
            } elseif (str_contains($acceptHeader, 'it')) {
                $browserLang = 'it';
                if (!$countryCode) $countryCode = 'IT';
            } elseif (str_contains($acceptHeader, 'ru')) {
                $browserLang = 'ru';
                if (!$countryCode) $countryCode = 'RU';
            } elseif (str_contains($acceptHeader, 'pt')) {
                $browserLang = 'pt';
                if (!$countryCode) $countryCode = 'PT';
            } elseif (str_contains($acceptHeader, 'hi') || str_contains($acceptHeader, 'en-in')) {
                $browserLang = 'en';
                if (!$countryCode) $countryCode = 'IN';
            }
        }

        if (!$countryCode) {
            $countryCode = 'IN';
        }

        // 4. ISO Country Code to Region, Locale, Name, and Flag Mapping
        $countryMap = [
            // India & South Asia
            'IN' => ['India', 'en', 'India', '🇮🇳'],
            'NP' => ['India', 'en', 'Nepal', '🇳🇵'],
            'BD' => ['India', 'en', 'Bangladesh', '🇧🇩'],
            'LK' => ['India', 'en', 'Sri Lanka', '🇱🇰'],
            'BT' => ['India', 'en', 'Bhutan', '🇧🇹'],

            // GCC & Middle East
            'AE' => ['GCC', 'ar', 'United Arab Emirates', '🇦🇪'],
            'SA' => ['GCC', 'ar', 'Saudi Arabia', '🇸🇦'],
            'QA' => ['GCC', 'ar', 'Qatar', '🇶🇦'],
            'KW' => ['GCC', 'ar', 'Kuwait', '🇰🇼'],
            'BH' => ['GCC', 'ar', 'Bahrain', '🇧🇭'],
            'OM' => ['GCC', 'ar', 'Oman', '🇴🇲'],
            'EG' => ['GCC', 'ar', 'Egypt', '🇪🇬'],
            'JO' => ['GCC', 'ar', 'Jordan', '🇯🇴'],

            // Thailand
            'TH' => ['Thailand', 'th', 'Thailand', '🇹🇭'],

            // USA & North America
            'US' => ['USA', 'en', 'United States', '🇺🇸'],
            'CA' => ['USA', 'en', 'Canada', '🇨🇦'],
            'MX' => ['USA', 'es', 'Mexico', '🇲🇽'],

            // Europe
            'DE' => ['Europe', 'de', 'Germany', '🇩🇪'],
            'AT' => ['Europe', 'de', 'Austria', '🇦🇹'],
            'CH' => ['Europe', 'de', 'Switzerland', '🇨🇭'],
            'FR' => ['Europe', 'fr', 'France', '🇫🇷'],
            'BE' => ['Europe', 'fr', 'Belgium', '🇧🇪'],
            'LU' => ['Europe', 'fr', 'Luxembourg', '🇱🇺'],
            'IT' => ['Europe', 'it', 'Italy', '🇮🇹'],
            'ES' => ['Europe', 'es', 'Spain', '🇪🇸'],
            'GB' => ['Europe', 'en', 'United Kingdom', '🇬🇧'],
            'UK' => ['Europe', 'en', 'United Kingdom', '🇬🇧'],
            'IE' => ['Europe', 'en', 'Ireland', '🇮🇪'],
            'NL' => ['Europe', 'en', 'Netherlands', '🇳🇱'],
            'SE' => ['Europe', 'en', 'Sweden', '🇸🇪'],
            'NO' => ['Europe', 'en', 'Norway', '🇳🇴'],
            'DK' => ['Europe', 'en', 'Denmark', '🇩🇰'],
            'FI' => ['Europe', 'en', 'Finland', '🇫🇮'],
            'PL' => ['Europe', 'en', 'Poland', '🇵🇱'],
            'PT' => ['Europe', 'pt', 'Portugal', '🇵🇹'],
            'RU' => ['Europe', 'ru', 'Russia', '🇷🇺'],

            // Asia Pacific
            'CN' => ['Asia Pacific', 'zh', 'China', '🇨🇳'],
            'TW' => ['Asia Pacific', 'zh', 'Taiwan', '🇹🇼'],
            'HK' => ['Asia Pacific', 'zh', 'Hong Kong', '🇭🇰'],
            'JP' => ['Asia Pacific', 'ja', 'Japan', '🇯🇵'],
            'KR' => ['Asia Pacific', 'ko', 'South Korea', '🇰🇷'],
            'SG' => ['Asia Pacific', 'en', 'Singapore', '🇸🇬'],
            'MY' => ['Asia Pacific', 'en', 'Malaysia', '🇲🇾'],
            'AU' => ['Asia Pacific', 'en', 'Australia', '🇦🇺'],
            'NZ' => ['Asia Pacific', 'en', 'New Zealand', '🇳🇿'],
            'ID' => ['Asia Pacific', 'en', 'Indonesia', '🇮🇩'],
            'VN' => ['Asia Pacific', 'en', 'Vietnam', '🇻🇳'],
            'PH' => ['Asia Pacific', 'en', 'Philippines', '🇵🇭'],
        ];

        $region = $countryMap[$countryCode][0] ?? 'India';
        $locale = $countryMap[$countryCode][1] ?? $browserLang ?? 'en';
        $countryName = $countryMap[$countryCode][2] ?? 'India';
        $flag = $countryMap[$countryCode][3] ?? '🇮🇳';

        return [
            'countryCode' => $countryCode,
            'countryName' => $countryName,
            'flag' => $flag,
            'region' => $region,
            'locale' => $locale,
        ];
    }
}
