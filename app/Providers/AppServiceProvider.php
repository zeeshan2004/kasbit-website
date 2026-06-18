<?php

namespace App\Providers;

use App\Models\FooterSetting;
use App\Models\HeaderMenu;
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
                $adminWebsiteSections = HeaderMenu::with(['children' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('name');
                }])
                    ->whereNull('parent_id')
                    ->where('show_in_admin_sidebar', true)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }

            $view->with('adminWebsiteSections', $adminWebsiteSections);
        });

        View::composer('frontend.*', function ($view) {
            $headerMenus = collect();

            if (Schema::hasTable('header_menus')) {
                $headerMenus = HeaderMenu::with(['children' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                }])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }

            $footerSetting = Schema::hasTable('footer_settings')
                ? FooterSetting::first()
                : null;

            $view->with([
                'headerMenus' => $headerMenus,
                'footerSetting' => $footerSetting,
            ]);
        });
    }
}
