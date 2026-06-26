<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
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

    public function getImageDimensionsAttribute(): array
    {
        $size = @getimagesize(public_path($this->image_url));

        return [
            'width' => $size[0] ?? 1600,
            'height' => $size[1] ?? 563,
        ];
    }
}
