<?php

namespace App\Console\Commands;

use App\Models\FooterSetting;
use App\Models\HeaderMenuPage;
use App\Models\HeaderMenuPageSlide;
use App\Models\HeroSlide;
use App\Models\HomePage;
use App\Models\NewsItem;
use App\Support\WebpImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OptimizePublicImages extends Command
{
    protected $signature = 'images:optimize';

    protected $description = 'Convert public JPG/PNG images to WebP and update stored paths';

    public function handle(WebpImageOptimizer $optimizer): int
    {
        $files = collect(File::allFiles(public_path()))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png'], true));

        $paths = [];

        foreach ($files as $file) {
            $oldRelative = str_replace('\\', '/', $file->getRelativePathname());
            $newRelative = preg_replace('/\.(jpe?g|png)$/i', '.webp', $oldRelative);
            $optimizer->convert($file->getPathname(), public_path($newRelative));
            $paths[$oldRelative] = $newRelative;

            $this->line(sprintf(
                '%s -> %s (%dKB)',
                $oldRelative,
                $newRelative,
                (int) ceil(filesize(public_path($newRelative)) / 1024)
            ));
        }

        DB::transaction(function () use ($paths): void {
            HeroSlide::query()->each(function (HeroSlide $slide) use ($paths): void {
                $oldPath = str_contains($slide->image, '/')
                    ? $slide->image
                    : 'uploads/hero-slides/' . $slide->image;
                if (isset($paths[$oldPath])) {
                    $slide->image = str_contains($slide->image, '/')
                        ? $paths[$oldPath]
                        : basename($paths[$oldPath]);
                    $slide->save();
                }
            });

            NewsItem::query()->each(function (NewsItem $item) use ($paths): void {
                $oldPath = str_contains($item->image, '/')
                    ? $item->image
                    : 'uploads/news/' . $item->image;
                if (isset($paths[$oldPath])) {
                    $item->image = str_contains($item->image, '/')
                        ? $paths[$oldPath]
                        : basename($paths[$oldPath]);
                    $item->save();
                }
            });

            HomePage::query()->each(function (HomePage $home) use ($paths): void {
                foreach ([
                    'hero_image', 'about_image', 'news_bg', 'location1_image',
                    'location2_image', 'location3_image', 'video_tour_poster',
                ] as $column) {
                    if ($home->{$column} && isset($paths[$home->{$column}])) {
                        $home->{$column} = $paths[$home->{$column}];
                    }
                }

                if ($home->header_logo) {
                    $oldPath = str_contains($home->header_logo, '/')
                        ? $home->header_logo
                        : 'uploads/home/' . $home->header_logo;
                    if (isset($paths[$oldPath])) {
                        $home->header_logo = str_contains($home->header_logo, '/')
                            ? $paths[$oldPath]
                            : basename($paths[$oldPath]);
                    }
                }
                $home->save();
            });

            FooterSetting::query()->each(function (FooterSetting $footer) use ($paths): void {
                if ($footer->logo && isset($paths[$footer->logo])) {
                    $footer->logo = $paths[$footer->logo];
                }
                $footer->gallery_images = collect($footer->gallery_images ?? [])
                    ->map(fn ($path) => $paths[$path] ?? $path)
                    ->all();
                $footer->save();
            });

            HeaderMenuPage::query()->each(function (HeaderMenuPage $page) use ($paths): void {
                if ($page->image && isset($paths[$page->image])) {
                    $page->image = $paths[$page->image];
                    $page->save();
                }
            });

            HeaderMenuPageSlide::query()->each(function (HeaderMenuPageSlide $slide) use ($paths): void {
                if ($slide->image && isset($paths[$slide->image])) {
                    $slide->image = $paths[$slide->image];
                    $slide->save();
                }
            });
        });

        foreach (array_keys($paths) as $oldRelative) {
            File::delete(public_path($oldRelative));
        }

        HeroSlide::query()->each(function (HeroSlide $slide) use ($optimizer): void {
            $relativePath = str_contains($slide->image, '/')
                ? $slide->image
                : 'uploads/hero-slides/' . $slide->image;
            $absolutePath = public_path($relativePath);

            if (is_file($absolutePath) && strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION)) === 'webp') {
                $optimizer->createAvif($absolutePath);
            }
        });

        HomePage::query()->each(function (HomePage $home) use ($optimizer): void {
            if ($home->hero_image && is_file(public_path($home->hero_image))) {
                $optimizer->createAvif(public_path($home->hero_image));
            }
        });

        $this->info("Optimized {$files->count()} image(s).");

        return self::SUCCESS;
    }
}
