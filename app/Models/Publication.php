<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'summary',
        'file',
        'translations',
    ];

    protected $casts = [
        'translations' => 'array',
    ];

    public function getLocalizedTitle($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if (!empty($this->translations[$locale]['title'])) {
            return $this->translations[$locale]['title'];
        }
        return $this->title;
    }

    public function getLocalizedSummary($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if (!empty($this->translations[$locale]['summary'])) {
            return $this->translations[$locale]['summary'];
        }
        return $this->summary;
    }
}
