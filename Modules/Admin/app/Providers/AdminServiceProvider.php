<?php

namespace Modules\Admin\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Support\Facades\Blade; // <- import Blade
use Modules\Common\Entities\User;


class AdminServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Admin';
    protected string $nameLower = 'admin';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();

        // Admin
        $this->app->bind(
            \Modules\Admin\Repositories\AdminRepositoryInterface::class,
            \Modules\Admin\Repositories\AdminRepository::class
        );

        $this->app->bind(
            \Modules\Admin\Repositories\UserManagementRepositoryInterface::class,
            \Modules\Admin\Repositories\UserManagementRepository::class
        );
    }

    public function boot(): void
    {
        parent::boot();

        Blade::if('canCreate', function () {
            $admin = session('admin_id') ? User::find(session('admin_id')) : null;
            return $admin && in_array($admin->getRoleNames()->first(), ['superadmin', 'admin', 'manager']);
        });

        Blade::if('canEdit', function () {
            $admin = session('admin_id') ? User::find(session('admin_id')) : null;
            return $admin && in_array($admin->getRoleNames()->first(), ['superadmin', 'admin', 'manager']);
        });

        Blade::if('canDelete', function () {
            $admin = session('admin_id') ? User::find(session('admin_id')) : null;
            return $admin && in_array($admin->getRoleNames()->first(), ['superadmin', 'admin']);
        });

        Blade::if('canViewOnly', function () {
            $admin = session('admin_id') ? User::find(session('admin_id')) : null;
            return $admin && $admin->hasRole('staff');
        });
    }
}
