<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Brochure extends Model
{
    protected $fillable = [
        'file_name',
        'file_path',
    ];
}
