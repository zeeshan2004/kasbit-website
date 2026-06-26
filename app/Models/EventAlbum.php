<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAlbum extends Model
{
    protected $fillable = [
        'header_menu_page_id',
        'title',
        'slug',
        'cover_image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function page()
    {
        return $this->belongsTo(HeaderMenuPage::class, 'header_menu_page_id');
    }

    public function images()
    {
        return $this->hasMany(EventAlbumImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
