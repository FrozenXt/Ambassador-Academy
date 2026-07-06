<?php


namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notice extends Model
{
    protected $table = 'notices';

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'content',
        'featured_image',
        'type',
        'priority',
        'is_featured',
        'status',
        'published_date',
        'expiry_date',
        'views',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'tags' => 'array',
        'published_date' => 'date',
        'expiry_date' => 'date',
        'views' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notice) {
            if (empty($notice->slug)) {
                $notice->slug = Str::slug($notice->title);
            }
            if (empty($notice->published_date)) {
                $notice->published_date = now();
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
            });
    }

    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_date ? $this->published_date->format('M d, Y') : null;
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = ['low' => 'info', 'medium' => 'warning', 'high' => 'danger', 'urgent' => 'dark'];
        return $badges[$this->priority] ?? 'secondary';
    }

    public function getTypeBadgeAttribute()
    {
        return $this->type == 'notice' ? 'primary' : 'success';
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
