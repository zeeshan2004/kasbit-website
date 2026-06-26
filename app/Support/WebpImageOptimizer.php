<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use RuntimeException;

class WebpImageOptimizer
{
    public const MAX_BYTES = 300 * 1024;
    public const MAX_DIMENSION = 1600;

    public function store(UploadedFile $file, string $directory, string $stem): string
    {
        $relativeDirectory = trim(str_replace('\\', '/', $directory), '/');
        $absoluteDirectory = public_path($relativeDirectory);
        File::ensureDirectoryExists($absoluteDirectory);

        $filename = $stem . '.webp';
        $webpPath = $absoluteDirectory . DIRECTORY_SEPARATOR . $filename;
        $this->convert($file->getRealPath(), $webpPath);
        $this->createAvif($webpPath);

        return $relativeDirectory . '/' . $filename;
    }

    public function createAvif(string $source, ?string $destination = null): ?string
    {
        if (!function_exists('imageavif')) {
            return null;
        }

        $contents = @file_get_contents($source);
        $image = $contents !== false ? @imagecreatefromstring($contents) : false;

        if (!$image) {
            return null;
        }

        $destination ??= preg_replace('/\.[^.]+$/', '.avif', $source);
        $quality = 58;

        do {
            imageavif($image, $destination, $quality, 6);
            $quality -= 6;
        } while (filesize($destination) > self::MAX_BYTES && $quality >= 40);

        imagedestroy($image);

        return $destination;
    }

    public function convert(string $source, string $destination): void
    {
        $contents = @file_get_contents($source);
        $image = $contents !== false ? @imagecreatefromstring($contents) : false;

        if (!$image) {
            throw new RuntimeException('The uploaded image could not be processed.');
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $image = $this->resizeToFit($image, self::MAX_DIMENSION, self::MAX_DIMENSION);
        File::ensureDirectoryExists(dirname($destination));

        $quality = 82;
        do {
            if (!imagewebp($image, $destination, $quality)) {
                imagedestroy($image);
                throw new RuntimeException('The image could not be saved as WebP.');
            }
            $quality -= 6;
        } while (filesize($destination) > self::MAX_BYTES && $quality >= 46);

        while (filesize($destination) > self::MAX_BYTES && imagesx($image) > 640) {
            $image = $this->resizeToFit(
                $image,
                (int) floor(imagesx($image) * 0.88),
                (int) floor(imagesy($image) * 0.88)
            );
            imagewebp($image, $destination, 68);
        }

        imagedestroy($image);

        if (filesize($destination) > self::MAX_BYTES) {
            throw new RuntimeException('The optimized image is still larger than 300KB.');
        }
    }

    private function resizeToFit(\GdImage $image, int $maxWidth, int $maxHeight): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $scale = min(1, $maxWidth / $width, $maxHeight / $height);

        if ($scale === 1.0) {
            return $image;
        }

        $resized = imagecreatetruecolor(
            max(1, (int) round($width * $scale)),
            max(1, (int) round($height * $scale))
        );
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefill($resized, 0, 0, $transparent);
        imagecopyresampled(
            $resized,
            $image,
            0,
            0,
            0,
            0,
            imagesx($resized),
            imagesy($resized),
            $width,
            $height
        );
        imagedestroy($image);

        return $resized;
    }
}
