<?php

namespace App\Providers;

use App\Models\FooterSetting;
use App\Models\HeaderMenu;
use App\Models\HomePage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.admin-layout', function ($view) {
            $adminWebsiteSections = collect();

            if (Schema::hasTable('header_menus')
                && Schema::hasColumn('header_menus', 'show_in_admin_sidebar')) {
                $adminWebsiteSections = HeaderMenu::with([
                    'children' => fn ($query) => $query->where('is_active', true),
                    'children.children' => fn ($query) => $query->where('is_active', true),
                ])
                    ->whereNull('parent_id')
                    ->where('show_in_admin_sidebar', true)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }

            $view->with([
                'adminWebsiteSections' => $adminWebsiteSections,
                'adminHome' => Schema::hasTable('home_pages') ? HomePage::first() : null,
            ]);
        });

        View::composer('frontend.*', function ($view) {
            $headerMenus = collect();

            if (Schema::hasTable('header_menus')) {
                $headerMenus = HeaderMenu::with([
                    'children' => fn ($query) => $query->where('is_active', true),
                    'children.children' => fn ($query) => $query->where('is_active', true),
                ])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }

            $view->with([
                'headerMenus' => $headerMenus,
                'footerSetting' => Schema::hasTable('footer_settings')
                    ? FooterSetting::first()
                    : null,
            ]);
        });
    }
}
