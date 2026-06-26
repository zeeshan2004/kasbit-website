<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAlbumImage extends Model
{
    protected $fillable = [
        'event_album_id',
        'image',
        'caption',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function album()
    {
        return $this->belongsTo(EventAlbum::class, 'event_album_id');
    }
}
