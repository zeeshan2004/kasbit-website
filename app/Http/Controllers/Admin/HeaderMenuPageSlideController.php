<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenuPage;
use App\Models\HeaderMenuPageSlide;
use App\Support\WebpImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class HeaderMenuPageSlideController extends Controller
{
    public function store(Request $request, HeaderMenuPage $page)
    {
        $validated = $request->validate([
            'slides' => ['required', 'array', 'min:1'],
            'slides.*.title' => ['nullable', 'string', 'max:255'],
            'slides.*.description' => ['nullable', 'string'],
            'slides.*.image' => ['nullable', 'image', 'max:10240'],
            'slides.*.image_position' => ['required', 'in:left,right'],
            'slides.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'slides.*.is_active' => ['nullable', 'boolean'],
        ]);

        foreach ($validated['slides'] as $index => $slide) {
            $hasTitle = filled($slide['title'] ?? null);
            $hasDescription = filled($slide['description'] ?? null);
            $hasImage = $request->hasFile("slides.{$index}.image");

            if (! $hasTitle && ! $hasDescription && ! $hasImage) {
                return back()
                    ->withErrors(["slides.{$index}.description" => 'Please add text or image for this content block.'])
                    ->withInput();
            }
        }

        $createdSlides = collect();

        foreach ($validated['slides'] as $index => $slide) {
            $createdSlides->push($page->slides()->create([
                'title' => $slide['title'] ?? '',
                'description' => $slide['description'] ?? null,
                'image' => $request->hasFile("slides.{$index}.image")
                    ? $this->storeImage($request->file("slides.{$index}.image"), (string) $index)
                    : null,
                'image_position' => $slide['image_position'],
                'sort_order' => $slide['sort_order'] ?? 0,
                'is_active' => (bool) ($slide['is_active'] ?? false),
            ]));
        }

        return $this->respond(
            $request,
            count($validated['slides']) . ' content block(s) added.',
            $page->menu,
            [
                'append_html' => $createdSlides
                    ->map(fn (HeaderMenuPageSlide $slide) => view('admin.cms.partials.page-section-card', compact('slide'))->render())
                    ->implode(''),
                'append_target' => '#page-section-list',
                'remove_selector' => '#page-section-empty',
                'reset_form' => true,
            ]
        );
    }

    public function update(Request $request, HeaderMenuPageSlide $slide)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:10240'],
            'image_position' => ['required', 'in:left,right'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'image.image' => 'Please select a valid JPG, PNG, GIF, BMP or WebP image.',
            'image.max' => 'The image must not be larger than 10MB.',
        ]);

        if (! filled($data['title'] ?? null)
            && ! filled($data['description'] ?? null)
            && ! $request->hasFile('image')
            && ! $slide->image) {
            return back()
                ->withErrors(['description' => 'Please add text or image for this content block.'])
                ->withInput();
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($slide);
            $data['image'] = $this->storeImage($request->file('image'));
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['title'] = $data['title'] ?? '';
        $data['is_active'] = $request->boolean('is_active');
        $slide->update($data);
        $slide->refresh();

        return $this->respond($request, 'Content block updated.', $slide->page->menu, [
            'replace_html' => view('admin.cms.partials.page-section-card', compact('slide'))->render(),
            'replace_selector' => '#page-section-card-' . $slide->id,
        ]);
    }

    public function destroy(Request $request, HeaderMenuPageSlide $slide)
    {
        $menu = $slide->page->menu;
        $this->deleteImage($slide);
        $slide->delete();

        return $this->respond($request, 'Content block deleted.', $menu);
    }

    private function storeImage(UploadedFile $file, string $suffix = ''): string
    {
        $suffix = $suffix !== '' ? '_' . $suffix : '';
        return app(WebpImageOptimizer::class)->store(
            $file,
            'uploads/page-slides',
            time() . $suffix . '_' . uniqid() . '_page_slide'
        );
    }

    private function deleteImage(HeaderMenuPageSlide $slide): void
    {
        if ($slide->image && file_exists(public_path($slide->image))) {
            unlink(public_path($slide->image));
        }
    }

    private function respond(Request $request, string $message, $menu, array $payload = [])
    {
        $url = route('header-menu.page.edit', $menu, false);

        if ($request->expectsJson()) {
            return response()->json(array_merge([
                'message' => $message,
            ], $payload));
        }

        return redirect($url)->with('success', $message);
    }
}
