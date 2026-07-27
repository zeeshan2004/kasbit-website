<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FooterSetting extends Model
{
    public const FRONTEND_CACHE_KEY = 'frontend:footer-setting:v1';

    protected $fillable = [
        'logo',
        'address_1',
        'address_2',
        'address_3',
        'useful_links',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'gallery_images',
        'map_embed_url',
        'map_title',
        'copyright_text',
        'background_color',
        'bottom_bar_color',
        'is_active',
    ];

    protected $casts = [
        'useful_links' => 'array',
        'gallery_images' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        $clearFrontendCache = fn () => Cache::store('file')->forget(self::FRONTEND_CACHE_KEY);

        static::saved($clearFrontendCache);
        static::deleted($clearFrontendCache);
    }
}
