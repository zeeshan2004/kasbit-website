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

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function isDescendantOf(string $name): bool
    {
        $menu = $this;

        while ($menu) {
            if (strcasecmp($menu->name, $name) === 0) {
                return true;
            }

            $menu = $menu->parent;
        }

        return false;
    }

    public function page()
    {
        return $this->hasOne(HeaderMenuPage::class);
    }
}
