<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category_id',
        'author_id',
        'status',
        'is_featured',
        'allow_comments',
        'views',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
        'order',
    ];

    protected $casts = [
        'is_featured'    => 'boolean',
        'allow_comments' => 'boolean',
        'tags'           => 'array',
        'published_at'   => 'datetime',
        'views'          => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public static function generateSlug(string $title, int $ignoreId = null): string
    {
        $slug  = Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->count();
        return $count ? $slug . '-' . $count : $slug;
    }

    public function getReadingTimeAttribute(): string
    {
        $words   = str_word_count(strip_tags($this->content ?? ''));
        $minutes = max(1, ceil($words / 200));
        return $minutes . ' min read';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
