<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$widths = [480, 768, 1200];
$slides = App\Models\HeroSlide::query()->get();

foreach ($slides as $slide) {
    $source = public_path($slide->image_url);

    if (! is_file($source)) {
        echo "missing {$slide->image_url}\n";
        continue;
    }

    $contents = @file_get_contents($source);
    $image = $contents !== false ? @imagecreatefromstring($contents) : false;

    if (! $image) {
        echo "unreadable {$slide->image_url}\n";
        continue;
    }

    imagepalettetotruecolor($image);
    imagealphablending($image, true);
    imagesavealpha($image, true);

    $sourceWidth = imagesx($image);
    $sourceHeight = imagesy($image);
    $sourceInfo = pathinfo($source);

    foreach ($widths as $width) {
        if ($width >= $sourceWidth) {
            continue;
        }

        $height = max(1, (int) round($sourceHeight * ($width / $sourceWidth)));
        $resized = imagecreatetruecolor($width, $height);

        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

        $webp = $sourceInfo['dirname'] . DIRECTORY_SEPARATOR . $sourceInfo['filename'] . '-' . $width . '.webp';
        imagewebp($resized, $webp, 76);
        echo str_replace(public_path() . DIRECTORY_SEPARATOR, '', $webp) . "\n";

        if (function_exists('imageavif')) {
            $avif = $sourceInfo['dirname'] . DIRECTORY_SEPARATOR . $sourceInfo['filename'] . '-' . $width . '.avif';
            imageavif($resized, $avif, 54, 8);
            echo str_replace(public_path() . DIRECTORY_SEPARATOR, '', $avif) . "\n";
        }

        imagedestroy($resized);
    }

    imagedestroy($image);
}
