<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElibraryResource;
use App\Models\HeaderMenuPage;
use App\Support\WebpImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ElibraryResourceController extends Controller
{
    public function store(Request $request, HeaderMenuPage $page)
    {
        $this->ensureElibraryResourcesPage($page);

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:10240'],
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_link' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $page->elibraryResources()->create([
            'title' => $data['title'] ?? null,
            'image' => $this->storeImage($request->file('image')),
            'button_text' => filled($data['button_text'] ?? null) ? $data['button_text'] : 'Explore',
            'button_link' => $this->normalizeLink($data['button_link'] ?? null),
            'sort_order' => $data['sort_order'] ?? ((int) $page->elibraryResources()->max('sort_order') + 1),
            'is_active' => $request->boolean('is_active'),
        ]);

        return $this->respond($request, 'E-Library resource added.', $page);
    }

    public function update(Request $request, ElibraryResource $resource)
    {
        $this->ensureElibraryResourcesPage($resource->page);

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:10240'],
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_link' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($resource);
            $resource->image = $this->storeImage($request->file('image'));
        }

        $resource->title = $data['title'] ?? null;
        $resource->button_text = filled($data['button_text'] ?? null) ? $data['button_text'] : 'Explore';
        $resource->button_link = $this->normalizeLink($data['button_link'] ?? null);
        $resource->sort_order = $data['sort_order'] ?? 0;
        $resource->is_active = $request->boolean('is_active');
        $resource->save();

        return $this->respond($request, 'E-Library resource updated.', $resource->page);
    }

    public function destroy(Request $request, ElibraryResource $resource)
    {
        $page = $resource->page;
        $this->ensureElibraryResourcesPage($page);
        $this->deleteImage($resource);
        $resource->delete();

        return $this->respond($request, 'E-Library resource deleted.', $page);
    }

    private function ensureElibraryResourcesPage(HeaderMenuPage $page): void
    {
        abort_unless(strtolower($page->slug) === 'e-library-resources', 404);
    }

    private function storeImage(UploadedFile $file): string
    {
        return app(WebpImageOptimizer::class)->store(
            $file,
            'uploads/elibrary-resources',
            time().'_'.uniqid().'_elibrary_resource'
        );
    }

    private function deleteImage(ElibraryResource $resource): void
    {
        if ($resource->image && file_exists(public_path($resource->image))) {
            unlink(public_path($resource->image));
        }
    }

    private function normalizeLink(?string $link): ?string
    {
        $link = trim((string) $link);

        if ($link === '') {
            return null;
        }

        if (str_starts_with($link, '/') || preg_match('/^https?:\/\//i', $link)) {
            return $link;
        }

        return 'https://'.$link;
    }

    private function respond(Request $request, string $message, HeaderMenuPage $page)
    {
        $url = route('header-menu.page.edit', $page->menu, false);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'refresh_url' => $url,
            ]);
        }

        return redirect($url)->with('success', $message);
    }
}
