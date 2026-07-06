<?php


namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_default',
        'is_system',
        'level'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_default' => 'boolean',
        'is_system' => 'boolean',
        'level' => 'integer'
    ];

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    /**
     * Check if role has permission
     */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Grant permission to role
     */
    public function grantPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
            $this->save();
        }
        return $this;
    }

    /**
     * Revoke permission from role
     */
    public function revokePermission($permission)
    {
        $permissions = $this->permissions ?? [];
        $permissions = array_diff($permissions, [$permission]);
        $this->permissions = $permissions;
        $this->save();
        return $this;
    }

    /**
     * Sync permissions
     */
    public function syncPermissions(array $permissions)
    {
        $this->permissions = $permissions;
        $this->save();
        return $this;
    }
}
