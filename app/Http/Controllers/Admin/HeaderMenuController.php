<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenu;
use App\Models\HomePage;
use Illuminate\Http\Request;

class HeaderMenuController extends Controller
{
    public function index()
    {
        return view('admin.cms.header-menu', [
            'menus' => HeaderMenu::with('children')->whereNull('parent_id')->orderBy('sort_order')->orderBy('name')->get(),
            'parents' => HeaderMenu::whereNull('parent_id')->orderBy('sort_order')->orderBy('name')->get(),
            'editMenu' => null,
            'home' => HomePage::first() ?? new HomePage(),
        ]);
    }

    public function store(Request $request)
    {
        HeaderMenu::create($this->validatedData($request));

        return redirect()->route('header-menu.index')->with('success', 'Header menu item added.');
    }

    public function edit(HeaderMenu $headerMenu)
    {
        return view('admin.cms.header-menu', [
            'menus' => HeaderMenu::with('children')->whereNull('parent_id')->orderBy('sort_order')->orderBy('name')->get(),
            'parents' => HeaderMenu::whereNull('parent_id')->where('id', '!=', $headerMenu->id)->orderBy('sort_order')->orderBy('name')->get(),
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
            'header_facebook_url' => ['nullable', 'string', 'max:2048'],
            'header_twitter_url' => ['nullable', 'string', 'max:2048'],
            'header_instagram_url' => ['nullable', 'string', 'max:2048'],
            'top_header_is_active' => ['nullable', 'boolean'],
            'delete_header_logo' => ['nullable', 'boolean'],
        ]);

        $home = HomePage::first() ?? new HomePage();

        if ($request->hasFile('header_logo')) {
            $this->deleteHeaderLogoFile($home);

            $file = $request->file('header_logo');
            $name = time() . '_logo.' . $file->extension();
            $file->move(public_path('uploads/home'), $name);
            $home->header_logo = $name;
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
        $home->header_facebook_url = $request->input('header_facebook_url');
        $home->header_twitter_url = $request->input('header_twitter_url');
        $home->header_instagram_url = $request->input('header_instagram_url');
        $home->top_header_is_active = $request->boolean('top_header_is_active');
        $home->save();

        return redirect()->route('header-menu.index')->with('success', 'Header settings updated.');
    }

    public function update(Request $request, HeaderMenu $headerMenu)
    {
        $headerMenu->update($this->validatedData($request));

        return redirect()->route('header-menu.index')->with('success', 'Header menu item updated.');
    }

    public function destroy(HeaderMenu $headerMenu)
    {
        $headerMenu->delete();

        return redirect()->route('header-menu.index')->with('success', 'Header menu item deleted.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'exists:header_menus,id'],
            'name' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['parent_id'] = $data['parent_id'] ?: null;
        $data['link'] = $data['link'] ?: '#';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
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
}
