<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSchemaTable extends Model
{
    protected $fillable = [
        'header_menu_page_id',
        'title',
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
        return $this->hasMany(ProgramSchemaRow::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
