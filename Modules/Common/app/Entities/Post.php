<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'code',
        'position',
        'description',
        'content',
        'image',
        'image_2',
        'status',
        'is_featured',
        'sort_order',
        'views',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
        'sort_order'   => 'integer',
        'views'        => 'integer',
    ];
    const CODE_STORY       = 'story';
    const CODE_ECA         = 'ECA';
    const CODE_ABOUT_PAGE  = 'about-page';
    const CODE_CHAIRMAN   = 'chairman';
    const CODE_ECA_PAGE   = 'eca-page';
    const POST_CODES = [
        ''                     => '— Not linked to a page section —',
        self::CODE_STORY       => 'Story Section (Home page)',
        self::CODE_ECA         => 'ECA Section',
        self::CODE_ABOUT_PAGE  => 'About Page Intro',
        self::CODE_CHAIRMAN => 'Chairman Section',
        self::CODE_ECA_PAGE => 'ECA Page'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });

        static::updating(function (Post $post) {
            if ($post->isDirty('title') && empty($post->getOriginal('slug'))) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
        });
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 1;

        while (
            static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    public function getImage2UrlAttribute(): ?string
    {
        return $this->image_2 ? Storage::url($this->image_2) : null;
    }
}
