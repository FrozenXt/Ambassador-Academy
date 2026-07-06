<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'location',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->orderBy('order');
    }

    public function allItems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }
}
