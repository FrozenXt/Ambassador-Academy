<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Album extends Model
{

    protected $table = 'albums';

    protected $fillable = [
        'title',
        'code',
        'slug',
        'description',
        'cover_image',
        'status',
        'is_featured',
        'sort_order',
        'is_gallery_category',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer'
    ];
    const CODE_BANNER             = 'banner';
    const CODE_HERO               = 'hero';
    const CODE_GALLERY            = 'gallery';
    const CODE_MENU               = 'menu';
    const CODE_ABOUT_AMENITY_MEDIA = 'about-amenity-media';
    const CODE_CONTACT            = 'contact';

    const ALBUM_CODES = [
        ''                             => '— Not linked to a page section —',
        self::CODE_BANNER              => 'Home Page — Hero Banner Video/Image',
        self::CODE_HERO                => 'Home Page — Hero Bottle Images',
        self::CODE_GALLERY             => 'Home Page — Gallery Preview',
        self::CODE_MENU                => 'Home Page — Menu Section Images',
        self::CODE_ABOUT_AMENITY_MEDIA => 'About Page — Amenity Circle Images',
        self::CODE_CONTACT             => 'Contact Page — Image',
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
