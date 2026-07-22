<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'subtitle',
        'slug',
        'base',
        'served',
        'style',
        'image',
        'url',
        'features',
        'price',
        'stock',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'features' => 'array',
    ];

    public function getFeaturesListAttribute(): array
    {
        return $this->features ?? [];
    }

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class);
    // }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $baseSlug = \Illuminate\Support\Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $product->slug = $slug;
            }
        });
    }
}
