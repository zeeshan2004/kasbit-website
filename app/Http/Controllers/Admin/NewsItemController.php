<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsItem;
use App\Support\WebpImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class NewsItemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'news_items' => ['required', 'array', 'min:1'],
            'news_items.*.title' => ['required', 'string', 'max:255'],
            'news_items.*.description' => ['nullable', 'string'],
            'news_items.*.image' => ['required', 'image', 'max:4096'],
            'news_items.*.link' => ['nullable', 'string', 'max:255'],
            'news_items.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'news_items.*.is_active' => ['nullable', 'boolean'],
        ]);

        foreach ($validated['news_items'] as $index => $item) {
            NewsItem::create([
                'title' => $item['title'],
                'description' => $item['description'] ?? null,
                'image' => $this->storeImage($request->file("news_items.{$index}.image"), (string) $index),
                'link' => ($item['link'] ?? null) ?: '#',
                'sort_order' => $item['sort_order'] ?? 0,
                'is_active' => (bool) ($item['is_active'] ?? false),
            ]);
        }

        return $this->redirectToNews(count($validated['news_items']) . ' news slide(s) added.');
    }

    public function update(Request $request, NewsItem $newsItem)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($newsItem);
            $data['image'] = $this->storeImage($request->file('image'));
        }

        $data['link'] = ($data['link'] ?? null) ?: '#';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');
        $newsItem->update($data);

        return $this->redirectToNews('News slide updated.');
    }

    public function destroy(NewsItem $newsItem)
    {
        $this->deleteImage($newsItem);
        $newsItem->delete();

        return $this->redirectToNews('News slide deleted.');
    }

    private function storeImage(UploadedFile $file, string $suffix = ''): string
    {
        $suffix = $suffix !== '' ? '_' . $suffix : '';
        $path = app(WebpImageOptimizer::class)->store(
            $file,
            'uploads/news',
            time() . $suffix . '_' . uniqid() . '_news'
        );

        return basename($path);
    }

    private function deleteImage(NewsItem $newsItem): void
    {
        $path = public_path($newsItem->image_url);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function redirectToNews(string $message)
    {
        return redirect(route('home.cms.index') . '#news-cms')->with('success', $message);
    }
}
