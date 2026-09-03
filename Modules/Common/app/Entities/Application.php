<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'applying_for',
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'gender',
        'guardian_name',
        'email',
        'phone',
        'address',
        'status',
        'admin_reply',
        'replied_at',
    ];

    protected $casts = [
        'dob'        => 'date',
        'replied_at' => 'datetime',
    ];

    public function isUnread(): bool
    {
        return $this->status === 'unread';
    }

    public function isRead(): bool
    {
        return $this->status === 'read';
    }

    public function isReplied(): bool
    {
        return $this->status === 'replied';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'unread'  => 'danger',
            'read'    => 'warning',
            'replied' => 'success',
            default   => 'secondary',
        };
    }

    public function getStudentNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }
}
