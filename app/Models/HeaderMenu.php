<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderMenu extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'link',
        'icon',
        'show_in_admin_sidebar',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_admin_sidebar' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function page()
    {
        return $this->hasOne(HeaderMenuPage::class);
    }
}
