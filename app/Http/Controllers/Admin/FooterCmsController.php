<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FooterCmsController extends Controller
{
    public function index()
    {
        return view('admin.cms.footer', [
            'footer' => FooterSetting::first() ?? new FooterSetting(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'logo' => ['nullable', 'image', 'max:4096'],
            'delete_logo' => ['nullable', 'boolean'],
            'address_1' => ['nullable', 'string'],
            'address_2' => ['nullable', 'string'],
            'address_3' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'string', 'max:2048'],
            'instagram_url' => ['nullable', 'string', 'max:2048'],
            'linkedin_url' => ['nullable', 'string', 'max:2048'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'max:4096'],
            'delete_existing_gallery' => ['nullable', 'array'],
            'delete_existing_gallery.*' => ['nullable', 'boolean'],
            'map_embed_url' => ['nullable', 'string', 'max:4096'],
            'map_title' => ['nullable', 'string', 'max:100'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'background_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'bottom_bar_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $footer = FooterSetting::first() ?? new FooterSetting();
        File::ensureDirectoryExists(public_path('uploads/footer'));

        if ($request->hasFile('logo')) {
            $this->deleteFile($footer->logo);
            $file = $request->file('logo');
            $name = time() . '_footer_logo.' . $file->extension();
            $file->move(public_path('uploads/footer'), $name);
            $footer->logo = 'uploads/footer/' . $name;
        } elseif ($request->boolean('delete_logo')) {
            $this->deleteFile($footer->logo);
            $footer->logo = null;
        }

        $existingGallery = array_values(array_filter($footer->gallery_images ?? []));
        $gallery = [];
        foreach ($existingGallery as $index => $path) {
            if ($request->boolean("delete_existing_gallery.{$index}")) {
                $this->deleteFile($path);
            } elseif ($path) {
                $gallery[] = $path;
            }
        }

        foreach ($request->file('gallery_images', []) as $index => $file) {
            if (!$file) {
                continue;
            }

            $name = time() . '_' . $index . '_' . uniqid() . '_footer_gallery.' . $file->extension();
            $file->move(public_path('uploads/footer'), $name);
            $gallery[] = 'uploads/footer/' . $name;
        }

        $footer->fill([
            'address_1' => $data['address_1'] ?? null,
            'address_2' => $data['address_2'] ?? null,
            'address_3' => $data['address_3'] ?? null,
            'useful_links' => [],
            'facebook_url' => $data['facebook_url'] ?? null,
            'instagram_url' => $data['instagram_url'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'gallery_images' => $gallery,
            'map_embed_url' => $data['map_embed_url'] ?? null,
            'map_title' => ($data['map_title'] ?? null) ?: 'Location Map',
            'copyright_text' => ($data['copyright_text'] ?? null) ?: '© ' . date('Y') . ' KASB Institute of Technology (PVT) Ltd. All Rights Reserved',
            'background_color' => $data['background_color'] ?? '#2756a5',
            'bottom_bar_color' => $data['bottom_bar_color'] ?? '#064f80',
            'is_active' => $request->boolean('is_active'),
        ]);
        $footer->save();

        return redirect()->route('footer-cms.index')->with('success', 'Footer updated.');
    }

    private function deleteFile(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}
