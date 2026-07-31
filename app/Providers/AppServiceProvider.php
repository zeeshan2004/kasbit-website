<?php

namespace App\Providers;

use App\Models\FooterSetting;
use App\Models\HeaderMenu;
use App\Models\HomePage;
use App\Models\Query;
use App\Models\ChatbotSetting;
use App\Models\ChatbotUnansweredQuestion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Fluent;
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
                    'children' => fn ($query) => $query->forFrontendHeader()->where('is_active', true),
                    'children.children' => fn ($query) => $query->forFrontendHeader()->where('is_active', true),
                ])
                    ->whereNull('parent_id')
                    ->forFrontendHeader()
                    ->where('show_in_admin_sidebar', true)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }

            $view->with([
                'adminWebsiteSections' => $adminWebsiteSections,
                'adminHome' => Schema::hasTable('home_pages') ? HomePage::first() : null,
                'feedbackPendingCount' => Schema::hasTable('queries')
                    ? Query::where('status', 'pending')->count()
                    : 0,
                'chatbotPendingCount' => Schema::hasTable('chatbot_unanswered_questions')
                    ? ChatbotUnansweredQuestion::where('status', 'pending')->count()
                    : 0,
            ]);
        });

        View::composer('frontend.partials.chatbot', function ($view) {
            $view->with([
                'chatbotSettings' => Schema::hasTable('chatbot_settings')
                    ? ChatbotSetting::query()->first()
                    : null,
            ]);
        });

        View::composer('frontend.partials.header', function ($view) {
            $view->with([
                'headerMenus' => $this->frontendHeaderMenus(),
            ]);
        });

        View::composer('frontend.partials.footer', function ($view) {
            $view->with([
                'footerSetting' => $this->frontendFooterSetting(),
            ]);
        });
    }

    private function frontendHeaderMenus(): Collection
    {
        $loadMenus = fn () => HeaderMenu::with([
            'children' => fn ($query) => $query->forFrontendHeader()->where('is_active', true),
            'children.children' => fn ($query) => $query->forFrontendHeader()->where('is_active', true),
        ])
            ->whereNull('parent_id')
            ->forFrontendHeader()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->toArray();

        $menus = $this->app->environment('testing')
            ? $loadMenus()
            : Cache::store('file')->rememberForever(HeaderMenu::FRONTEND_CACHE_KEY, $loadMenus);

        return $this->hydrateMenuItems($menus);
    }

    private function hydrateMenuItems(array $menus): Collection
    {
        return collect($menus)->map(function (array $menu): Fluent {
            $menu['children'] = $this->hydrateMenuItems($menu['children'] ?? []);

            return new Fluent($menu);
        });
    }

    private function frontendFooterSetting(): ?Fluent
    {
        $loadFooter = fn () => FooterSetting::first()?->toArray() ?? [];
        $footer = $this->app->environment('testing')
            ? $loadFooter()
            : Cache::store('file')->rememberForever(FooterSetting::FRONTEND_CACHE_KEY, $loadFooter);

        return $footer === [] ? null : new Fluent($footer);
    }
}
