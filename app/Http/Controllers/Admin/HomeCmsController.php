<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePage;
use App\Models\HeroSlide;
use App\Models\NewsItem;
use App\Support\WebpImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeCmsController extends Controller
{
    public function index()
    {
        $home = HomePage::first();
        $heroSlides = HeroSlide::orderBy('sort_order')->orderBy('id')->get();
        $newsItems = NewsItem::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.cms.home', compact('home', 'heroSlides', 'newsItems'));
    }

    public function storeHeroSlide(Request $request)
    {
        if ($request->has('slides')) {
            $request->validate([
                'slides' => ['required', 'array', 'min:1'],
                'slides.*.title' => ['nullable', 'string', 'max:255'],
                'slides.*.subtitle' => ['nullable', 'string'],
                'slides.*.image' => ['required', 'image', 'max:4096'],
                'slides.*.button_text' => ['nullable', 'string', 'max:255'],
                'slides.*.button_link' => ['nullable', 'string', 'max:255'],
                'slides.*.sort_order' => ['nullable', 'integer', 'min:0'],
                'slides.*.is_active' => ['nullable', 'boolean'],
            ]);

            File::ensureDirectoryExists(public_path('uploads/hero-slides'));

            foreach ($request->input('slides', []) as $index => $slideData) {
                $file = $request->file("slides.{$index}.image");
                $name = basename(app(WebpImageOptimizer::class)->store(
                    $file,
                    'uploads/hero-slides',
                    time() . '_' . $index . '_hero_slide'
                ));

                HeroSlide::create([
                    'title' => $slideData['title'] ?? null,
                    'subtitle' => $slideData['subtitle'] ?? null,
                    'image' => $name,
                    'button_text' => $slideData['button_text'] ?? null,
                    'button_link' => ($slideData['button_link'] ?? null) ?: '#',
                    'sort_order' => $slideData['sort_order'] ?? 0,
                    'is_active' => (bool) ($slideData['is_active'] ?? false),
                ]);
            }

            return back()->with('success', 'Hero carousel slides added.');
        }

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'image' => ['required', 'image', 'max:4096'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $file = $request->file('image');
        $name = basename(app(WebpImageOptimizer::class)->store(
            $file,
            'uploads/hero-slides',
            time() . '_hero_slide'
        ));

        $data['image'] = $name;
        $data['button_link'] = ($data['button_link'] ?? null) ?: '#';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        HeroSlide::create($data);

        return back()->with('success', 'Hero carousel slide added.');
    }

    public function updateNewsBackground(Request $request)
    {
        $request->validate([
            'news_bg' => ['nullable', 'image', 'max:4096'],
            'delete_news_bg' => ['nullable', 'boolean'],
        ]);

        $home = HomePage::first() ?? new HomePage();

        if ($request->hasFile('news_bg')) {
            if ($home->news_bg && file_exists(public_path($home->news_bg))) {
                unlink(public_path($home->news_bg));
            }

            $home->news_bg = app(WebpImageOptimizer::class)->store(
                $request->file('news_bg'),
                'uploads/home',
                time() . '_news'
            );
        } elseif ($request->boolean('delete_news_bg')) {
            if ($home->news_bg && file_exists(public_path($home->news_bg))) {
                unlink(public_path($home->news_bg));
            }

            $home->news_bg = null;
        }

        $home->save();

        return redirect(route('home.cms.index') . '#news-cms')
            ->with('success', 'News & Events background updated.');
    }

    public function updateVideoTour(Request $request)
    {
        $data = $request->validate([
            'video_tour_title' => ['nullable', 'string', 'max:255'],
            'video_tour_file' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:512000'],
            'video_tour_url' => ['nullable', 'string', 'max:2048'],
            'video_tour_poster' => ['nullable', 'image', 'max:4096'],
            'delete_video_tour_file' => ['nullable', 'boolean'],
            'delete_video_tour_poster' => ['nullable', 'boolean'],
            'video_tour_is_active' => ['nullable', 'boolean'],
        ]);

        $home = HomePage::first() ?? new HomePage();
        File::ensureDirectoryExists(public_path('uploads/video-tour'));

        if ($request->hasFile('video_tour_file')) {
            $this->deletePublicFile($home->video_tour_file);
            $file = $request->file('video_tour_file');
            $name = time() . '_kasbit_tour.' . $file->extension();
            $file->move(public_path('uploads/video-tour'), $name);
            $home->video_tour_file = 'uploads/video-tour/' . $name;
            $home->video_tour_url = null;
        } elseif (trim((string) ($data['video_tour_url'] ?? '')) !== '') {
            $this->deletePublicFile($home->video_tour_file);
            $home->video_tour_file = null;
            $home->video_tour_url = trim($data['video_tour_url']);
        } elseif ($request->boolean('delete_video_tour_file')) {
            $this->deletePublicFile($home->video_tour_file);
            $home->video_tour_file = null;
            $home->video_tour_url = null;
        }

        if ($request->hasFile('video_tour_poster')) {
            $this->deletePublicFile($home->video_tour_poster);
            $home->video_tour_poster = app(WebpImageOptimizer::class)->store(
                $request->file('video_tour_poster'),
                'uploads/video-tour',
                time() . '_kasbit_tour_poster'
            );
        } elseif ($request->boolean('delete_video_tour_poster')) {
            $this->deletePublicFile($home->video_tour_poster);
            $home->video_tour_poster = null;
        }

        $home->video_tour_title = ($data['video_tour_title'] ?? null) ?: 'VIDEO TOUR OF KASBIT';
        $home->video_tour_is_active = $request->boolean('video_tour_is_active');
        $home->save();

        return redirect(route('home.cms.index') . '#video-tour-cms')
            ->with('success', 'Video tour updated.');
    }

    public function updateHeroSlide(Request $request, HeroSlide $heroSlide)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteHeroSlideImage($heroSlide);

            $data['image'] = basename(app(WebpImageOptimizer::class)->store(
                $request->file('image'),
                'uploads/hero-slides',
                time() . '_hero_slide'
            ));
        }

        $data['button_link'] = ($data['button_link'] ?? null) ?: '#';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        $heroSlide->update($data);

        return back()->with('success', 'Hero carousel slide updated.');
    }

    public function destroyHeroSlide(HeroSlide $heroSlide)
    {
        $this->deleteHeroSlideImage($heroSlide);
        $heroSlide->delete();

        return back()->with('success', 'Hero carousel slide deleted.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'location1_map_url' => ['nullable', 'string', 'max:2048'],
            'location2_map_url' => ['nullable', 'string', 'max:2048'],
            'location3_map_url' => ['nullable', 'string', 'max:2048'],
        ]);

        $home = HomePage::first();

        if(!$home){
            $home = new HomePage();
        }

        $home->fill($request->except([
            'hero_image',
            'about_image',
            'news_bg',
            'location1_image',
            'location2_image',
            'location3_image',
            'header_logo',
            'delete_hero_image',
            'delete_about_image',
            'delete_header_logo',
            'delete_news_bg',
            'delete_location1_image',
            'delete_location2_image',
            'delete_location3_image'
        ]));

        // Handle Header Logo
        if($request->hasFile('header_logo'))
        {
            $oldLogoPath = $home->header_logo && str_contains($home->header_logo, '/')
                ? public_path($home->header_logo)
                : public_path('uploads/home/'.$home->header_logo);

            if($home->header_logo && file_exists($oldLogoPath)) {
                unlink($oldLogoPath);
            }
            $home->header_logo = basename(app(WebpImageOptimizer::class)->store(
                $request->file('header_logo'),
                'uploads/home',
                time() . '_logo'
            ));
        } elseif($request->input('delete_header_logo') == '1') {
            $oldLogoPath = $home->header_logo && str_contains($home->header_logo, '/')
                ? public_path($home->header_logo)
                : public_path('uploads/home/'.$home->header_logo);

            if($home->header_logo && file_exists($oldLogoPath)) {
                unlink($oldLogoPath);
            }
            $home->header_logo = null;
        } elseif($home->header_logo && str_contains($home->header_logo, '/')) {
            $home->header_logo = basename($home->header_logo);
        }

        if($request->hasFile('hero_image'))
        {
            if($home->hero_image && file_exists(public_path($home->hero_image))) {
                unlink(public_path($home->hero_image));
            }
            $home->hero_image = app(WebpImageOptimizer::class)->store(
                $request->file('hero_image'),
                'uploads/home',
                time() . '_hero'
            );
        } elseif($request->input('delete_hero_image') == '1') {
            if($home->hero_image && file_exists(public_path($home->hero_image))) {
                unlink(public_path($home->hero_image));
            }
            $home->hero_image = null;
        }

        if($request->hasFile('about_image'))
        {
            if($home->about_image && file_exists(public_path($home->about_image))) {
                unlink(public_path($home->about_image));
            }

            $home->about_image = app(WebpImageOptimizer::class)->store(
                $request->file('about_image'),
                'uploads/home',
                time() . '_about'
            );
        } elseif($request->input('delete_about_image') == '1') {
            if($home->about_image && file_exists(public_path($home->about_image))) {
                unlink(public_path($home->about_image));
            }

            $home->about_image = null;
        }

        if($request->hasFile('news_bg'))
        {
            if($home->news_bg && file_exists(public_path($home->news_bg))) {
                unlink(public_path($home->news_bg));
            }
            $home->news_bg = app(WebpImageOptimizer::class)->store(
                $request->file('news_bg'),
                'uploads/home',
                time() . '_news'
            );
        } elseif($request->input('delete_news_bg') == '1') {
            if($home->news_bg && file_exists(public_path($home->news_bg))) {
                unlink(public_path($home->news_bg));
            }
            $home->news_bg = null;
        }

        // Handle Location 1 Image
        if($request->hasFile('location1_image'))
        {
            if($home->location1_image && file_exists(public_path($home->location1_image))) {
                unlink(public_path($home->location1_image));
            }
            $home->location1_image = app(WebpImageOptimizer::class)->store(
                $request->file('location1_image'),
                'uploads/home',
                time() . '_location1'
            );
        } elseif($request->input('delete_location1_image') == '1') {
            if($home->location1_image && file_exists(public_path($home->location1_image))) {
                unlink(public_path($home->location1_image));
            }
            $home->location1_image = null;
        }

        // Handle Location 2 Image
        if($request->hasFile('location2_image'))
        {
            if($home->location2_image && file_exists(public_path($home->location2_image))) {
                unlink(public_path($home->location2_image));
            }
            $home->location2_image = app(WebpImageOptimizer::class)->store(
                $request->file('location2_image'),
                'uploads/home',
                time() . '_location2'
            );
        } elseif($request->input('delete_location2_image') == '1') {
            if($home->location2_image && file_exists(public_path($home->location2_image))) {
                unlink(public_path($home->location2_image));
            }
            $home->location2_image = null;
        }

        // Handle Location 3 Image
        if($request->hasFile('location3_image'))
        {
            if($home->location3_image && file_exists(public_path($home->location3_image))) {
                unlink(public_path($home->location3_image));
            }
            $home->location3_image = app(WebpImageOptimizer::class)->store(
                $request->file('location3_image'),
                'uploads/home',
                time() . '_location3'
            );
        } elseif($request->input('delete_location3_image') == '1') {
            if($home->location3_image && file_exists(public_path($home->location3_image))) {
                unlink(public_path($home->location3_image));
            }
            $home->location3_image = null;
        }

        $home->save();

        return back()->with('success','Updated');
    }

    private function deleteHeroSlideImage(HeroSlide $heroSlide): void
    {
        $path = str_contains($heroSlide->image, '/')
            ? public_path($heroSlide->image)
            : public_path('uploads/hero-slides/' . $heroSlide->image);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}
