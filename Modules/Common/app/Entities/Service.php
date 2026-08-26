<?php


namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'slug',
        'description',
        'content',
        'icon',
        'image',
        'status',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'type' => 'string',
    ];
    const TYPE_SERVICE_PAGE_ICON = 'service-page-icon';
    const TYPE_HOME_AMENITIES = 'home-amenities';
    const TYPE_SERVICE_FEATURES = 'service-features';

    const SERVICE_TYPES = [


        self::TYPE_SERVICE_PAGE_ICON  => 'Service Page — icons',
        self::TYPE_HOME_AMENITIES => 'Home Page - Amenities icons',
        self::TYPE_SERVICE_FEATURES => 'Service Page - Features List'
    ];
    /**
     * Generate unique slug
     */
    public static function generateSlug($title, $id = null)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', $slug)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Scope active services
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
