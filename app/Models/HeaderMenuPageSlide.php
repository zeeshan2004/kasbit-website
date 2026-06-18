<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderMenuPageSlide extends Model
{
    protected $fillable = [
        'header_menu_page_id',
        'title',
        'description',
        'image',
        'image_position',
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
