<?php


namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'module',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
