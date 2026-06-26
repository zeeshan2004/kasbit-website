<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendarTable extends Model
{
    protected $fillable = [
        'header_menu_page_id',
        'title',
        'type',
        'col1_label',
        'col2_label',
        'col3_label',
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

    public function rows()
    {
        return $this->hasMany(AcademicCalendarRow::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
