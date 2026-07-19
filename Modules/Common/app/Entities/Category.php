<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'image_2',
        'status',
        'slug',
        'sort_order'
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    // public function getImage2UrlAttribute(): ?string
    // {
    //     return $this->image_2 ? Storage::url($this->image_2) : null;
    // }
}
