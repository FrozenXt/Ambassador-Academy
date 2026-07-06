<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;

    protected $table = 'gallery';

    protected $fillable = [
        'album_id',
        'image_type',
        'image_position',
        'type_settings',
        'title',
        'file_type',      // image | video | youtube
        'path',           // file path (image/video)
        'youtube_url',
        'description',
        // 'image_path',
        'image_alt',
        'sort_order',
        'status',
        'is_featured',
        'metadata'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'metadata' => 'array',
        'type_settings' => 'array'
    ];

    protected $appends = ['image_url', 'thumbnail_url'];


    const TYPE_BANNER         = 'banner';
    const TYPE_FLOATING_IMAGE = 'floating_image';

    const TYPE_LOGO           = 'logo';
    const TYPE_ICON           = 'icon';

    const TYPE_SERVICES       = 'services';
    const TYPE_SERVICE_ICON   =  'service_icon';
    const TYPE_CATEGORIES     = 'categories';
    const TYPE_PRODUCTS       = 'products';
    const TYPE_WORKS       = 'works';
    const TYPE_CLIENTS        = 'clients';
    const TYPE_EVENTS         = 'events';
    const TYPE_NOTICES        = 'notices';
    const TYPE_FAQS           = 'faqs';

    const TYPE_PAGES          = 'pages';

    const TYPE_USERS          = 'users';

    const TYPE_ALBUMS         = 'albums';
    const TYPE_GALLERY        = 'gallery';

    const TYPE_MODAL          = 'modal';

    const TYPE_OTHER          = 'other';


    public static function getImageTypes()
    {
        return [
            self::TYPE_BANNER         => 'Banner (Hero Image)',
            self::TYPE_FLOATING_IMAGE => 'Floating Image (Hero Cards)',

            self::TYPE_LOGO           => 'Logo (Navbar / Footer)',
            self::TYPE_ICON           => 'Icon (Small Icons)',

            self::TYPE_SERVICES       => 'Services',
            self::TYPE_SERVICE_ICON       => 'Service_icon',
            self::TYPE_CATEGORIES     => 'Categories',
            self::TYPE_PRODUCTS       => 'Products',
            self::TYPE_WORKS       => 'Works',
            self::TYPE_CLIENTS        => 'Clients / Trusted Companies',
            self::TYPE_EVENTS         => 'Events',
            self::TYPE_NOTICES        => 'Notices & News',
            self::TYPE_FAQS           => 'FAQs',

            self::TYPE_PAGES          => 'Pages (General)',

            self::TYPE_USERS          => 'User Avatars',

            self::TYPE_ALBUMS         => 'Album Covers',
            self::TYPE_GALLERY        => 'Gallery / Portfolio',

            self::TYPE_MODAL          => 'Modal Popups',

            self::TYPE_OTHER          => 'Other',
        ];
    }

    public static function getFloatingPositions()
    {
        return [
            'top-left' => 'Top Left',
            'top-right' => 'Top Right',
            'bottom-left' => 'Bottom Left',
            'bottom-right' => 'Bottom Right',
            'center' => 'Center',
            'floating-1' => 'Floating 1',
            'floating-2' => 'Floating 2',
            'floating-3' => 'Floating 3',
            'floating-4' => 'Floating 4',
        ];
    }

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }



    public function getThumbnailUrlAttribute()
    {
        if ($this->image_path) {
            $pathInfo = pathinfo($this->image_path);
            $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];

            if (file_exists(storage_path('app/public/' . $thumbnailPath))) {
                return asset('storage/' . $thumbnailPath);
            }
        }

        return $this->image_url;
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
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    public function scopeByAlbum($query, $albumId)
    {
        return $query->where('album_id', $albumId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('image_type', $type);
    }

    public function scopeFloatingImages($query)
    {
        return $query->where('image_type', self::TYPE_FLOATING_IMAGE);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($gallery) {
            // If admin explicitly set a sort_order, respect it
            if (!empty($gallery->sort_order) && $gallery->sort_order > 0) {
                // Shift all existing items at or below this position down by 1
                static::where('image_type', $gallery->image_type)
                    ->where('sort_order', '>=', $gallery->sort_order)
                    ->increment('sort_order');
            } else {
                // No sort_order given — place at top (position 1)
                static::where('image_type', $gallery->image_type)
                    ->increment('sort_order');

                $gallery->sort_order = 1;
            }
        });

        static::updating(function ($gallery) {
            if ($gallery->isDirty('sort_order')) {
                $oldOrder = $gallery->getOriginal('sort_order');
                $newOrder = $gallery->sort_order;

                if ($newOrder > $oldOrder) {
                    // Moving down: shift items between old+1 and new up by 1
                    static::where('image_type', $gallery->image_type)
                        ->where('id', '!=', $gallery->id)
                        ->whereBetween('sort_order', [$oldOrder + 1, $newOrder])
                        ->decrement('sort_order');
                } else {
                    // Moving up: shift items between new and old-1 down by 1
                    static::where('image_type', $gallery->image_type)
                        ->where('id', '!=', $gallery->id)
                        ->whereBetween('sort_order', [$newOrder, $oldOrder - 1])
                        ->increment('sort_order');
                }
            }
        });

        static::deleting(function ($gallery) {
            // Close the gap left by deleted item
            static::where('image_type', $gallery->image_type)
                ->where('sort_order', '>', $gallery->sort_order)
                ->decrement('sort_order');
        });
    }
    public function isImage()
    {
        return $this->file_type === 'image';
    }

    public function isVideo()
    {
        return $this->file_type === 'video';
    }

    public function isYoutube()
    {
        return $this->file_type === 'youtube';
    }

    // Helper: get full URL safely
    public function getMediaUrlAttribute()
    {
        if ($this->file_type === 'youtube') {
            return $this->youtube_url;
        }

        return asset('storage/' . $this->path);
    }
    // Gallery.php
    public function getImageUrlAttribute()
    {
        $path = $this->path ?: $this->image_path;

        if ($path && file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        return asset('vendor/admin/img/default-image.jpg');
    }
}
