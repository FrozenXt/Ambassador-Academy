<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $table = 'counters';

    protected $fillable = [
        'title',
        'number',
        'suffix',
        'prefix',
        'icon',
        'description',
        'color',
        'status',
        'order',
    ];

    // Get full display e.g. "$500+"
    public function getDisplayNumberAttribute(): string
    {
        return ($this->prefix ?? '') . $this->number . ($this->suffix ?? '');
    }
}
