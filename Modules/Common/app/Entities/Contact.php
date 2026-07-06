<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'type',
        'name',
        'first_name',
        'last_name',
        'address',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_reply',
        'replied_at',
        'model'
    ];

    protected $casts = [
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
    public function getDisplayNameAttribute()
    {
        return $this->name
            ?? trim($this->first_name . ' ' . $this->last_name);
    }
}
