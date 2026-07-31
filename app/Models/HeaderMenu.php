<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class HeaderMenu extends Model
{
    public const FRONTEND_CACHE_KEY = 'frontend:header-menus:v1';
    public const ADMIN_DROPDOWN_ONLY_MENU = 'Associate Degree Program 2 Years';
    public const ADMIN_DROPDOWN_CONTENT_TARGET = 'Associate Degree in Computer Science';

    protected $fillable = [
        'parent_id',
        'name',
        'link',
        'icon',
        'show_in_admin_sidebar',
        'management_context',
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

    public function isAdminDropdownOnly(): bool
    {
        if (strcasecmp($this->name, self::ADMIN_DROPDOWN_ONLY_MENU) !== 0) {
            return false;
        }

        return $this->relationLoaded('children')
            ? $this->children->isNotEmpty()
            : $this->children()->exists();
    }

    public function adminContentTarget(): ?self
    {
        if (! $this->isAdminDropdownOnly()) {
            return null;
        }

        if ($this->relationLoaded('children')) {
            return $this->children->first(
                fn (self $child) => strcasecmp(
                    $child->name,
                    self::ADMIN_DROPDOWN_CONTENT_TARGET
                ) === 0
            );
        }

        return $this->children()
            ->whereRaw('LOWER(name) = ?', [strtolower(self::ADMIN_DROPDOWN_CONTENT_TARGET)])
            ->first();
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

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'program_id');
    }

    public function scopeForFrontendHeader(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereNull('management_context')
                ->orWhere('management_context', 'header');
        });
    }

    public function scopeForRegistration(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereNull('management_context')
                ->orWhere('management_context', 'registration');
        });
    }

    public static function registrationProgramGroups(bool $includeInactive = false): Collection
    {
        $programsMenu = static::query()
            ->whereNull('parent_id')
            ->where('name', 'Programs')
            ->first();

        if (! $programsMenu || (! $includeInactive && ! $programsMenu->is_active)) {
            return new Collection();
        }

        return $programsMenu->children()
            ->when(! $includeInactive, fn ($query) => $query->where('is_active', true))
            ->whereHas('children', fn ($query) => $query
                ->forRegistration()
                ->when(! $includeInactive, fn ($query) => $query->where('is_active', true)))
            ->with(['children' => fn ($query) => $query
                ->forRegistration()
                ->when(! $includeInactive, fn ($query) => $query->where('is_active', true))
                ->orderBy('sort_order')
                ->orderBy('name')])
            ->get();
    }

    public static function registrationProgramIds(bool $includeInactive = false): array
    {
        return static::registrationProgramGroups($includeInactive)
            ->flatMap(fn (HeaderMenu $group) => $group->children)
            ->pluck('id')
            ->all();
    }

    protected static function booted(): void
    {
        $clearFrontendCache = fn () => Cache::store('file')->forget(self::FRONTEND_CACHE_KEY);

        static::saved($clearFrontendCache);
        static::deleted($clearFrontendCache);
    }
}
