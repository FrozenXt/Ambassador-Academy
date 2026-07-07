<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
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

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
