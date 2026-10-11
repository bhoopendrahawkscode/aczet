<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exhibition extends Model
{
    protected $fillable = [
        'title',
        'location',
        'hall_booth',
        'event_date',
        'description',
        'image',
        'is_upcoming',
        'translations',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_upcoming' => 'boolean',
        'translations' => 'array',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return asset('images/products/noimage.jpeg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://') || str_starts_with($this->image, '//')) {
            return $this->image;
        }

        return asset(ltrim($this->image, '/'));
    }

    public function getLocalizedTitle($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if (!empty($this->translations[$locale]['title'])) {
            return $this->translations[$locale]['title'];
        }
        return $this->title;
    }

    public function getLocalizedDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if (!empty($this->translations[$locale]['description'])) {
            return $this->translations[$locale]['description'];
        }
        return $this->description;
    }
}
