<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_image',

        'about_label',
        'about_title',
        'about_description',
        'about_image',

        'vision_title',
        'vision',
        'mission_title',
        'mission',

        'news_bg',
        
        'location_title',
        'location_description',
        'location1_name',
        'location1_image',
        'location1_map_url',
        'location2_name',
        'location2_image',
        'location2_map_url',
        'location3_name',
        'location3_image',
        'location3_map_url',

        'video_tour_title',
        'video_tour_file',
        'video_tour_url',
        'video_tour_poster',
        'video_tour_is_active',
        
        'header_logo',
        'header_phone',
        'header_email',
        'top_location_1_name',
        'top_location_1_url',
        'top_location_2_name',
        'top_location_2_url',
        'top_location_3_name',
        'top_location_3_url',
        'header_facebook_url',
        'header_twitter_url',
        'header_instagram_url',
        'top_header_is_active',
    ];

    protected $casts = [
        'video_tour_is_active' => 'boolean',
        'top_header_is_active' => 'boolean',
    ];

    public function getHeaderLogoUrlAttribute(): ?string
    {
        if (!$this->header_logo) {
            return null;
        }

        if (str_contains($this->header_logo, '/')) {
            return $this->header_logo;
        }

        return 'uploads/home/' . $this->header_logo;
    }
}
