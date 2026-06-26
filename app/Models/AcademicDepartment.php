<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicDepartment extends Model
{
    protected $fillable = [
        'header_menu_page_id',
        'name',
        'description',
        'image',
        'head_of_department',
        'link',
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
