<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'model_number',
        'slug',
        'short_description',
        'full_description',
        'translations',
        'main_image',
        'gallery',
        'pdf_datasheet',
        'pdf_manual',
        'pdf_brochure',
        'pdf_service_manual',
        'apn_number',
        'service_password',
        'price',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'translations' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected $appends = [
        'raw_main_image',
        'image_url',
        'primary_image',
    ];

    public function getLocalizedName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if (!empty($this->translations[$locale]['name'])) {
            return $this->translations[$locale]['name'];
        }
        return $this->name;
    }

    public function getLocalizedShortDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if (!empty($this->translations[$locale]['short_description'])) {
            return $this->translations[$locale]['short_description'];
        }
        return $this->short_description;
    }

    public function getLocalizedFullDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if (!empty($this->translations[$locale]['full_description'])) {
            return $this->translations[$locale]['full_description'];
        }
        return $this->full_description ?? $this->short_description;
    }

    public function getMainImageAttribute($value)
    {
        if (empty($value)) {
            return vasset('images/products/noimage.jpeg');
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '//')) {
            return $value;
        }

        $clean = ltrim($value, '/');
        if (!file_exists(public_path($clean))) {
            $aliasMap = [
                'frontend/images/products/cw-h.png' => 'images/products/checkweigher-cwh.jpg',
                'frontend/images/products/is-600s.png' => 'images/products/is600.svg',
                'frontend/images/products/tts-31.png' => 'images/products/table-top-scale.jpg',
                'frontend/images/products/cub-10.png' => 'images/products/ultrasonic-small.jpg',
                'frontend/images/products/axiom-cube-x.png' => 'images/products/gold-cube.jpg',
                'frontend/images/products/compact-eco.png' => 'images/products/gold-compact-eco.jpg',
                'frontend/images/products/cm-21u.png' => 'images/products/cm21.svg',
                'frontend/images/products/gxz-600.png' => 'images/products/gxz600.svg',
                'frontend/images/products/mb-200t.png' => 'images/products/mb200.svg',
                'frontend/images/products/cy-224c.png' => 'images/products/cy224.svg',
            ];
            if (isset($aliasMap[$clean]) && file_exists(public_path($aliasMap[$clean]))) {
                return vasset($aliasMap[$clean]);
            }
            return vasset('images/products/noimage.jpeg');
        }

        return vasset($value);
    }

    public function getRawMainImageAttribute()
    {
        return $this->getRawOriginal('main_image') ?: '';
    }

    public function getImageUrlAttribute()
    {
        return $this->main_image;
    }

    public function getPrimaryImageAttribute()
    {
        return $this->main_image;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order', 'asc');
    }

    public function faqs()
    {
        return $this->hasMany(ProductFaq::class)->orderBy('sort_order', 'asc');
    }

    public function regionVisibilities()
    {
        return $this->hasMany(ProductRegionVisibility::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function isVisibleInRegion(string $regionName): bool
    {
        $reg = $this->regionVisibilities()->where('region', $regionName)->first();
        return $reg ? (bool)$reg->is_visible : true;
    }

    /**
     * Comparison Compatibility Group Key
     */
    public function getComparisonGroupKey(): string
    {
        if ($this->category) {
            return $this->category->getComparisonGroupKey();
        }
        return 'general';
    }

    /**
     * Comparison Category Name
     */
    public function getComparisonGroupName(): string
    {
        if ($this->category) {
            return $this->category->getComparisonGroupName();
        }
        return 'Equipment';
    }
}

