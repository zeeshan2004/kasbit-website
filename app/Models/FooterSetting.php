<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
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
}
