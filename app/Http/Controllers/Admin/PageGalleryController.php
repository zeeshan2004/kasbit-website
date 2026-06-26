<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenuPage;
use App\Models\PageGalleryImage;
use App\Support\WebpImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PageGalleryController extends Controller
{
    public function store(Request $request, HeaderMenuPage $page)
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'max:10240'],
        ], [
            'images.required' => 'Please choose at least one image.',
            'images.*.image' => 'Each file must be a valid JPG, PNG, GIF, BMP or WebP image.',
            'images.*.max' => 'Each image must not be larger than 10MB.',
        ]);

        $order = (int) $page->galleryImages()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $page->galleryImages()->create([
                'image' => $this->storeImage($file),
                'sort_order' => ++$order,
                'is_active' => true,
            ]);
        }

        $count = count($request->file('images'));

        return $this->respond($request, $count . ' image(s) added to the gallery.', $page->menu);
    }

    public function update(Request $request, PageGalleryImage $galleryImage)
    {
        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($galleryImage);
            $galleryImage->image = $this->storeImage($request->file('image'));
        }

        $galleryImage->caption = $data['caption'] ?? null;
        $galleryImage->sort_order = $data['sort_order'] ?? 0;
        $galleryImage->is_active = $request->boolean('is_active');
        $galleryImage->save();

        return $this->respond($request, 'Gallery image updated.', $galleryImage->page->menu);
    }

    public function destroy(Request $request, PageGalleryImage $galleryImage)
    {
        $menu = $galleryImage->page->menu;
        $this->deleteImage($galleryImage);
        $galleryImage->delete();

        return $this->respond($request, 'Gallery image deleted.', $menu);
    }

    private function storeImage(UploadedFile $file): string
    {
        return app(WebpImageOptimizer::class)->store(
            $file,
            'uploads/page-gallery',
            time() . '_' . uniqid() . '_gallery'
        );
    }

    private function deleteImage(PageGalleryImage $galleryImage): void
    {
        if ($galleryImage->image && file_exists(public_path($galleryImage->image))) {
            unlink(public_path($galleryImage->image));
        }
    }

    private function respond(Request $request, string $message, $menu)
    {
        $url = route('header-menu.page.edit', $menu, false);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'refresh_url' => $url,
            ]);
        }

        return redirect($url)->with('success', $message);
    }
}
