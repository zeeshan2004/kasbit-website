<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSchemaTable extends Model
{
    protected $fillable = [
        'header_menu_page_id',
        'title',
        'qec_serial_label',
        'qec_col1_label',
        'qec_col2_label',
        'qec_col3_label',
        'qec_col4_label',
        'qec_show_col4',
        'qec_col5_label',
        'qec_show_col5',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'qec_show_col4' => 'boolean',
        'qec_show_col5' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(HeaderMenuPage::class, 'header_menu_page_id');
    }

    public function rows()
    {
        return $this->hasMany(ProgramSchemaRow::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
