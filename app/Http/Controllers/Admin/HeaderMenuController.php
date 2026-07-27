<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenu;
use App\Models\HeaderMenuPage;
use App\Models\HomePage;
use App\Support\WebpImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HeaderMenuController extends Controller
{
    public function index()
    {
        return view('admin.cms.header-menu', [
            'menus' => $this->headerMenuTree(),
            'parents' => $this->availableParents(),
            'editMenu' => null,
            'home' => HomePage::first() ?? new HomePage(),
        ]);
    }

    public function store(Request $request)
    {
        $headerMenu = HeaderMenu::create($this->validatedData($request));
        $this->syncPage($headerMenu, true);

        return $this->menuResponse($request, 'Header menu item added.');
    }

    public function edit(HeaderMenu $headerMenu)
    {
        return view('admin.cms.header-menu', [
            'menus' => $this->headerMenuTree(),
            'parents' => $this->availableParents($headerMenu),
            'editMenu' => $headerMenu,
            'home' => HomePage::first() ?? new HomePage(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'header_logo' => ['nullable', 'image', 'max:2048'],
            'header_phone' => ['nullable', 'string', 'max:255'],
            'header_email' => ['nullable', 'email', 'max:255'],
            'top_location_1_name' => ['nullable', 'string', 'max:100'],
            'top_location_1_url' => ['nullable', 'string', 'max:2048'],
            'top_location_2_name' => ['nullable', 'string', 'max:100'],
            'top_location_2_url' => ['nullable', 'string', 'max:2048'],
            'top_location_3_name' => ['nullable', 'string', 'max:100'],
            'top_location_3_url' => ['nullable', 'string', 'max:2048'],
            'top_location_4_name' => ['nullable', 'string', 'max:100'],
            'top_location_4_url' => ['nullable', 'string', 'max:2048'],
            'header_facebook_url' => ['nullable', 'string', 'max:2048'],
            'header_twitter_url' => ['nullable', 'string', 'max:2048'],
            'header_instagram_url' => ['nullable', 'string', 'max:2048'],
            'top_header_is_active' => ['nullable', 'boolean'],
            'cursor_is_active' => ['nullable', 'boolean'],
            'cursor_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'delete_header_logo' => ['nullable', 'boolean'],
        ]);

        $home = HomePage::first() ?? new HomePage();

        if ($request->hasFile('header_logo')) {
            $this->deleteHeaderLogoFile($home);

            $home->header_logo = basename(app(WebpImageOptimizer::class)->store(
                $request->file('header_logo'),
                'uploads/home',
                time() . '_logo'
            ));
        } elseif ($request->boolean('delete_header_logo')) {
            $this->deleteHeaderLogoFile($home);
            $home->header_logo = null;
        } elseif ($home->header_logo && str_contains($home->header_logo, '/')) {
            $home->header_logo = basename($home->header_logo);
        }

        $home->header_phone = $request->input('header_phone');
        $home->header_email = $request->input('header_email');
        $home->top_location_1_name = $request->input('top_location_1_name');
        $home->top_location_1_url = $request->input('top_location_1_url');
        $home->top_location_2_name = $request->input('top_location_2_name');
        $home->top_location_2_url = $request->input('top_location_2_url');
        $home->top_location_3_name = $request->input('top_location_3_name');
        $home->top_location_3_url = $request->input('top_location_3_url');
        $home->top_location_4_name = $request->input('top_location_4_name');
        $home->top_location_4_url = $request->input('top_location_4_url');
        $home->header_facebook_url = $request->input('header_facebook_url');
        $home->header_twitter_url = $request->input('header_twitter_url');
        $home->header_instagram_url = $request->input('header_instagram_url');
        $home->top_header_is_active = $request->boolean('top_header_is_active');
        $home->cursor_is_active = $request->boolean('cursor_is_active');
        $home->cursor_color = strtolower($request->input('cursor_color', '#ffcc00'));
        $home->save();

        return $this->menuResponse($request, 'Header settings updated.');
    }

    public function updateLoaderSettings(Request $request)
    {
        $data = $request->validate([
            'loader_logo' => ['nullable', 'image', 'max:2048'],
            'delete_loader_logo' => ['nullable', 'boolean'],
            'loader_is_active' => ['nullable', 'boolean'],
            'loader_text' => ['nullable', 'string', 'max:100'],
        ]);

        $home = HomePage::first() ?? new HomePage();
        $loaderText = trim((string) ($data['loader_text'] ?? ''));

        if ($request->hasFile('loader_logo')) {
            $this->deleteLoaderLogoFile($home);

            $home->loader_logo = basename(app(WebpImageOptimizer::class)->store(
                $request->file('loader_logo'),
                'uploads/home',
                time() . '_loader_logo'
            ));
        } elseif ($request->boolean('delete_loader_logo')) {
            $this->deleteLoaderLogoFile($home);
            $home->loader_logo = null;
        } elseif ($home->loader_logo && str_contains($home->loader_logo, '/')) {
            $home->loader_logo = basename($home->loader_logo);
        }

        $home->loader_is_active = $request->boolean('loader_is_active');
        $home->loader_text = $loaderText !== '' ? $loaderText : null;
        $home->save();

        return $this->menuResponse($request, 'Loader settings updated.');
    }

    public function update(Request $request, HeaderMenu $headerMenu)
    {
        $headerMenu->update($this->validatedData($request));
        $this->syncPage($headerMenu, true);

        return $this->menuResponse($request, 'Header menu item updated.');
    }

    public function destroy(Request $request, HeaderMenu $headerMenu)
    {
        $headerMenu->delete();

        return $this->menuResponse($request, 'Header menu item deleted.');
    }

    public function toggle(Request $request, HeaderMenu $headerMenu)
    {
        $data = $request->validate([
            'field' => ['required', 'in:is_active,show_in_admin_sidebar'],
        ]);

        if ($data['field'] === 'show_in_admin_sidebar' && $headerMenu->parent_id) {
            return back()->withErrors([
                'show_in_admin_sidebar' => 'Subcategories are displayed with their parent section.',
            ]);
        }

        $field = $data['field'];
        $headerMenu->update([$field => !$headerMenu->{$field}]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Menu status updated.',
                'field' => $field,
                'value' => (bool) $headerMenu->{$field},
            ]);
        }

        return redirect()->route('header-menu.index')->with('success', 'Menu status updated.');
    }

    private function menuResponse(Request $request, string $message)
    {
        $url = route('header-menu.index', [], false);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'refresh_url' => $url,
            ]);
        }

        return redirect()->route('header-menu.index')->with('success', $message);
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'exists:header_menus,id'],
            'name' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'in:' . implode(',', array_keys($this->availableIcons()))],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['parent_id'] = $data['parent_id'] ?: null;

        if ($data['parent_id']) {
            $parent = HeaderMenu::findOrFail($data['parent_id']);
            abort_if($parent->parent?->parent_id, 422, 'Only three menu levels are supported.');
        }
        $data['link'] = $this->normalizeLink($data['link'] ?? null);
        $data['icon'] = $data['icon'] ?: 'fa-solid fa-folder';
        $data['show_in_admin_sidebar'] = empty($data['parent_id']);
        $data['management_context'] = 'header';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    public static function iconOptions(): array
    {
        return [
            'fa-solid fa-circle-info' => 'About / Information',
            'fa-solid fa-book-open' => 'Programs / Book',
            'fa-solid fa-file-signature' => 'Admissions / Form',
            'fa-solid fa-building-columns' => 'Academics / University',
            'fa-solid fa-circle-nodes' => 'Life at KASBIT',
            'fa-solid fa-shield-halved' => 'Quality / Shield',
            'fa-solid fa-flask' => 'Research / Flask',
            'fa-solid fa-right-to-bracket' => 'Login',
            'fa-solid fa-user-graduate' => 'Alumni / Graduate',
            'fa-solid fa-book' => 'Library',
            'fa-solid fa-images' => 'Gallery',
            'fa-solid fa-address-book' => 'Contact',
            'fa-solid fa-message' => 'Message',
            'fa-solid fa-globe' => 'International / Globe',
            'fa-solid fa-users' => 'People / Users',
            'fa-solid fa-calendar-days' => 'Calendar',
            'fa-solid fa-graduation-cap' => 'Education / Graduate',
            'fa-solid fa-laptop-code' => 'Computer / Technology',
            'fa-solid fa-chart-line' => 'Results / Growth',
            'fa-solid fa-landmark' => 'Institution / Landmark',
            'fa-solid fa-link' => 'Link',
            'fa-solid fa-circle' => 'Small Circle',
            'fa-solid fa-folder' => 'General / Folder',
        ];
    }

    private function availableIcons(): array
    {
        return self::iconOptions();
    }

    private function syncPage(HeaderMenu $headerMenu, bool $assignDefaultLink = false): void
    {
        if (!$headerMenu->parent_id) {
            return;
        }

        $page = HeaderMenuPage::firstOrCreate(
            ['header_menu_id' => $headerMenu->id],
            [
                'slug' => $this->uniquePageSlug($headerMenu),
                'eyebrow' => $headerMenu->parent?->parent?->name ?: $headerMenu->parent?->name,
                'title' => $headerMenu->name,
                'accent_color' => '#07559d',
                'show_image' => true,
            ]
        );

        if (!$page->wasRecentlyCreated && $page->title === '') {
            $page->update(['title' => $headerMenu->name]);
        }

        $currentLink = trim((string) $headerMenu->link);

        if ($assignDefaultLink && ($currentLink === '' || $currentLink === '#')) {
            $headerMenu->updateQuietly([
                'link' => $headerMenu->name === 'About Us' ? '/about' : '/pages/' . $page->slug,
            ]);
        }
    }

    private function normalizeLink(?string $link): ?string
    {
        $link = trim((string) $link);

        if ($link === '' || $link === '#') {
            return null;
        }

        if (str_starts_with($link, '/')) {
            $link = '/' . ltrim(preg_replace('/\s+/', '', $link), '/');
        }

        return $link;
    }

    private function uniquePageSlug(HeaderMenu $headerMenu): string
    {
        $base = Str::slug($headerMenu->name) ?: 'page-' . $headerMenu->id;
        $slug = $base;
        $suffix = 2;

        while (HeaderMenuPage::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    private function deleteHeaderLogoFile(HomePage $home): void
    {
        if (!$home->header_logo) {
            return;
        }

        $path = str_contains($home->header_logo, '/')
            ? public_path($home->header_logo)
            : public_path('uploads/home/' . $home->header_logo);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function deleteLoaderLogoFile(HomePage $home): void
    {
        if (!$home->loader_logo) {
            return;
        }

        $path = str_contains($home->loader_logo, '/')
            ? public_path($home->loader_logo)
            : public_path('uploads/home/' . $home->loader_logo);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function availableParents(?HeaderMenu $editing = null)
    {
        $blockedIds = collect();

        if ($editing) {
            $editing->loadMissing('children.children');
            $blockedIds = $this->menuBranchIds($editing);
        }

        return HeaderMenu::with(['children' => fn ($query) => $query
                ->forFrontendHeader()
                ->orderBy('sort_order')
                ->orderBy('name')
            ])
            ->whereNull('parent_id')
            ->forFrontendHeader()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->flatMap(function (HeaderMenu $menu) {
                return collect([$menu])->merge($menu->children);
            })
            ->reject(function (HeaderMenu $candidate) use ($blockedIds) {
                if ($candidate->parent?->parent_id) {
                    return true;
                }

                return $blockedIds->contains($candidate->id);
            })
            ->values();
    }

    private function headerMenuTree()
    {
        return HeaderMenu::with([
            'children' => fn ($query) => $query->forFrontendHeader(),
            'children.children' => fn ($query) => $query->forFrontendHeader(),
        ])
            ->whereNull('parent_id')
            ->forFrontendHeader()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    private function menuBranchIds(HeaderMenu $menu): \Illuminate\Support\Collection
    {
        return collect([$menu->id])
            ->merge($menu->children->flatMap(fn (HeaderMenu $child) => $this->menuBranchIds($child)));
    }
}
