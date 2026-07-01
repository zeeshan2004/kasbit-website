<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    private const RESPONSIVE_WIDTHS = [480, 768, 1200];

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'button_text',
        'button_link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        if (str_contains($this->image, '/')) {
            return $this->image;
        }

        return 'uploads/hero-slides/' . $this->image;
    }

    public function getImageAvifUrlAttribute(): ?string
    {
        $avif = preg_replace('/\.[^.]+$/', '.avif', $this->image_url);

        return $avif && is_file(public_path($avif)) ? $avif : null;
    }

    public function getImageSrcsetAttribute(): string
    {
        return $this->buildSrcset($this->image_url, 'webp');
    }

    public function getImageAvifSrcsetAttribute(): ?string
    {
        $avif = $this->image_avif_url;

        return $avif ? $this->buildSrcset($avif, 'avif') : null;
    }

    public function getImageSizesAttribute(): string
    {
        return '(max-width: 575px) 100vw, (max-width: 1199px) 100vw, 1600px';
    }

    public function getImageDimensionsAttribute(): array
    {
        $size = @getimagesize(public_path($this->image_url));

        return [
            'width' => $size[0] ?? 1600,
            'height' => $size[1] ?? 563,
        ];
    }

    private function buildSrcset(string $path, string $extension): string
    {
        $srcset = [];

        foreach (self::RESPONSIVE_WIDTHS as $width) {
            $variant = preg_replace('/\.[^.]+$/', '-' . $width . '.' . $extension, $path);

            if ($variant && is_file(public_path($variant))) {
                $srcset[] = asset($variant) . ' ' . $width . 'w';
            }
        }

        $dimensions = $this->image_dimensions;
        $srcset[] = asset($path) . ' ' . $dimensions['width'] . 'w';

        return implode(', ', array_unique($srcset));
    }
}
