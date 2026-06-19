<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderMenuPage extends Model
{
    protected $fillable = [
        'header_menu_id',
        'slug',
        'eyebrow',
        'title',
        'subtitle',
        'content',
        'image',
        'pdf_file',
        'pdf_original_name',
        'accent_color',
        'show_image',
    ];

    protected $casts = [
        'show_image' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(HeaderMenu::class, 'header_menu_id');
    }

    public function slides()
    {
        return $this->hasMany(HeaderMenuPageSlide::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function programSchemaTables()
    {
        return $this->hasMany(ProgramSchemaTable::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
