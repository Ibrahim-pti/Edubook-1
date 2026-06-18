<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'tag',
        'image',
        'color_start',
        'color_end',
        'sort_order',
        'is_active',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        $path = $this->image;

        // Already an absolute URL.
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // The admin panel stores the path with a leading "/storage/" already
        // (e.g. "/storage/banners/x.png"). Prefix only the host in that case —
        // otherwise we'd get a broken "/storage//storage/..." double prefix.
        if (str_starts_with($path, '/storage/') || str_starts_with($path, 'storage/')) {
            return url('/' . ltrim($path, '/'));
        }

        // Bare relative path like "banners/x.png".
        return asset('storage/' . $path);
    }
}
