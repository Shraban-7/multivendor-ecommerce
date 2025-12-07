<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const SECTION_HERO = 'hero';
    const SECTION_MID_PROMO = 'mid_promo';
    const SECTION_FLASH_SALE = 'flash_sale';
    const SECTION_CATEGORY_TOP = 'category_top';
    const SECTION_FOOTER_BANNER = 'footer_banner';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSection($query, $section)
    {
        return $query->where('section', $section)
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc');
    }
}
