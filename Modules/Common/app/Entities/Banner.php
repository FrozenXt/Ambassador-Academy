<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'button_text',
        'button_url',
        'button_text_2',
        'button_url_2',
        'status',
        'order',
    ];
}
