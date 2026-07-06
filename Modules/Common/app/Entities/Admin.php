<?php


namespace Modules\Common\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'avatar',
        'custom_permissions'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'custom_permissions' => 'array'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Check if admin has a specific role
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->role && $this->role->slug === $role;
        }

        return $this->role && in_array($this->role->slug, $role);
    }

    /**
     * Check if admin has permission
     */
    public function hasPermission($permission)
    {
        // Super admin has all permissions
        if ($this->hasRole('super-admin')) {
            return true;
        }

        // Check custom permissions first (individual overrides)
        if ($this->custom_permissions && isset($this->custom_permissions[$permission])) {
            return $this->custom_permissions[$permission];
        }

        // Check role permissions
        return $this->role && $this->role->hasPermission($permission);
    }

    /**
     * Check if admin has any of the given permissions
     */
    public function hasAnyPermission(array $permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if admin has all given permissions
     */
    public function hasAllPermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Grant custom permission
     */
    public function grantPermission($permission)
    {
        $permissions = $this->custom_permissions ?? [];
        $permissions[$permission] = true;
        $this->custom_permissions = $permissions;
        $this->save();
    }

    /**
     * Revoke custom permission
     */
    public function revokePermission($permission)
    {
        $permissions = $this->custom_permissions ?? [];
        unset($permissions[$permission]);
        $this->custom_permissions = $permissions;
        $this->save();
    }

    /**
     * Set custom permissions
     */
    public function setCustomPermissions(array $permissions)
    {
        $this->custom_permissions = $permissions;
        $this->save();
    }

    /**
     * Log activity
     */
    public function logActivity($action, $module, $description = null, $oldData = null, $newData = null)
    {
        return ActivityLog::create([
            'admin_id' => $this->id,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Check if admin can manage other admins
     */
    public function canManageAdmins()
    {
        return $this->hasPermission('manage_admins') && $this->role && $this->role->level > 0;
    }

    /**
     * Check if admin can manage a specific admin
     */
    public function canManageAdmin(Admin $admin)
    {
        // Can't manage yourself
        if ($this->id === $admin->id) {
            return false;
        }

        // Super admin can manage all
        if ($this->hasRole('super-admin')) {
            return true;
        }

        // Admin can only manage users with lower role level
        return $this->role && $admin->role && $this->role->level > $admin->role->level;
    }
}
