<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventAlbum;
use App\Models\EventAlbumImage;
use App\Models\HeaderMenuPage;
use App\Support\WebpImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class EventAlbumController extends Controller
{
    /* ---------------- Albums ---------------- */

    public function store(Request $request, HeaderMenuPage $page)
    {
        $this->ensureEventGalleryPage($page);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'cover_image' => ['nullable', 'image', 'max:10240'],
        ]);

        $page->eventAlbums()->create([
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title']),
            'cover_image' => $request->hasFile('cover_image')
                ? $this->storeImage($request->file('cover_image'))
                : null,
            'sort_order' => (int) $page->eventAlbums()->max('sort_order') + 1,
            'is_active' => true,
        ]);

        return $this->respond($request, 'Event album created.', route('header-menu.page.edit', $page->menu, false));
    }

    public function update(Request $request, EventAlbum $album)
    {
        $this->ensureEventGalleryPage($album->page);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'cover_image' => ['nullable', 'image', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('cover_image')) {
            $this->deleteFile($album->cover_image);
            $album->cover_image = $this->storeImage($request->file('cover_image'));
        }

        $album->title = $data['title'];
        $album->sort_order = $data['sort_order'] ?? 0;
        $album->is_active = $request->boolean('is_active');
        $album->save();

        return $this->respond($request, 'Event album updated.', route('header-menu.page.edit', $album->page->menu, false));
    }

    public function destroy(Request $request, EventAlbum $album)
    {
        $this->ensureEventGalleryPage($album->page);
        $menu = $album->page->menu;

        $this->deleteFile($album->cover_image);
        foreach ($album->images as $image) {
            $this->deleteFile($image->image);
        }
        $album->delete();

        return $this->respond($request, 'Event album deleted.', route('header-menu.page.edit', $menu, false));
    }

    /* ---------------- Album photos ---------------- */

    public function photos(EventAlbum $album)
    {
        $this->ensureEventGalleryPage($album->page);
        $album->load('images');

        return view('admin.cms.event-album', compact('album'));
    }

    public function storePhotos(Request $request, EventAlbum $album)
    {
        $this->ensureEventGalleryPage($album->page);

        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'max:10240'],
        ], [
            'images.required' => 'Please choose at least one image.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.max' => 'Each image must not be larger than 10MB.',
        ]);

        $order = (int) $album->images()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $album->images()->create([
                'image' => $this->storeImage($file),
                'sort_order' => ++$order,
                'is_active' => true,
            ]);
        }

        return $this->respond(
            $request,
            count($request->file('images')) . ' photo(s) added.',
            route('event-albums.photos', $album, false)
        );
    }

    public function updatePhoto(Request $request, EventAlbumImage $image)
    {
        $this->ensureEventGalleryPage($image->album->page);

        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteFile($image->image);
            $image->image = $this->storeImage($request->file('image'));
        }

        $image->caption = $data['caption'] ?? null;
        $image->sort_order = $data['sort_order'] ?? 0;
        $image->is_active = $request->boolean('is_active');
        $image->save();

        return $this->respond($request, 'Photo updated.', route('event-albums.photos', $image->album, false));
    }

    public function destroyPhoto(Request $request, EventAlbumImage $image)
    {
        $this->ensureEventGalleryPage($image->album->page);
        $album = $image->album;

        $this->deleteFile($image->image);
        $image->delete();

        return $this->respond($request, 'Photo deleted.', route('event-albums.photos', $album, false));
    }

    /* ---------------- Helpers ---------------- */

    private function ensureEventGalleryPage(?HeaderMenuPage $page): void
    {
        abort_unless(strcasecmp($page?->menu?->name ?? '', 'Event Gallery') === 0, 404);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'album';
        $slug = $base;
        $i = 2;

        while (EventAlbum::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function storeImage(UploadedFile $file): string
    {
        return app(WebpImageOptimizer::class)->store(
            $file,
            'uploads/event-gallery',
            time() . '_' . uniqid() . '_event'
        );
    }

    private function deleteFile(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }

    private function respond(Request $request, string $message, string $refreshUrl)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'refresh_url' => $refreshUrl,
            ]);
        }

        return redirect($refreshUrl)->with('success', $message);
    }
}
