<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'icon',
        'target',
        'order',
        'status',
    ];

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
