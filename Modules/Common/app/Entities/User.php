<?php

namespace Modules\Common\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory,
        HasRoles,
        Notifiable;


    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_admin'          => 'boolean',
        'email_verified_at' => 'datetime',
    ];
}
