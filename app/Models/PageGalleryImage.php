<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageGalleryImage extends Model
{
    protected $fillable = [
        'header_menu_page_id',
        'image',
        'caption',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(HeaderMenuPage::class, 'header_menu_page_id');
    }
}
