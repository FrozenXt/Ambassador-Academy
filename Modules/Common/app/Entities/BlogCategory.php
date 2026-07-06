<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'order',
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }

    public static function generateSlug(string $name, int $ignoreId = null): string
    {
        $slug  = Str::slug($name);
        $count = static::where('slug', 'like', $slug . '%')
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->count();
        return $count ? $slug . '-' . $count : $slug;
    }
}
