<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Album extends Model
{
    use SoftDeletes;

    protected $table = 'albums';

    protected $fillable = [
        'title',
        'code',
        'slug',
        'description',
        'cover_image',
        'status',
        'is_featured',
        'sort_order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer'
    ];

    protected $appends = ['cover_image_url'];

    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'album_id');
    }

    public function getGalleryCountAttribute()
    {
        return $this->gallery()->count();
    }

    public function scopeWithGalleryCount($query)
    {
        return $query->withCount('gallery');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->title);
            }
        });

        static::updating(function ($album) {
            if ($album->isDirty('title') && empty($album->slug)) {
                $album->slug = Str::slug($album->title);
            }
        });
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image && file_exists(storage_path('app/public/' . $this->cover_image))) {
            return asset('storage/' . $this->cover_image);
        }

        return asset('vendor/admin/img/default-album.jpg');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
