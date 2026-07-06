<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Modules\Common\Entities\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        // ── Create Permissions ──
        $permissions = [
            // Dashboard
            'view dashboard',

            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',

            // Categories
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // Pages
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',

            // Banners
            'view banners',
            'create banners',
            'edit banners',
            'delete banners',

            // Media
            'view media',
            'upload media',
            'delete media',

            // Contacts
            'view contacts',
            'reply contacts',
            'delete contacts',

            // Events
            'view events',
            'create events',
            'edit events',
            'delete events',

            // Notices
            'view notices',
            'create notices',
            'edit notices',
            'delete notices',

            // Testimonials
            'view testimonials',
            'create testimonials',
            'edit testimonials',
            'delete testimonials',

            // FAQs
            'view faqs',
            'create faqs',
            'edit faqs',
            'delete faqs',

            // Clients
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',

            // Settings
            'view settings',
            'edit settings',

            // Email Settings
            'view email settings',
            'edit email settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── Create Roles ──

        // Super Admin — all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin — almost all except user delete and settings
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'view dashboard',
            'view users',
            'create users',
            'edit users',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',
            'view banners',
            'create banners',
            'edit banners',
            'delete banners',
            'view media',
            'upload media',
            'delete media',
            'view contacts',
            'reply contacts',
            'delete contacts',
            'view events',
            'create events',
            'edit events',
            'delete events',
            'view notices',
            'create notices',
            'edit notices',
            'delete notices',
            'view testimonials',
            'create testimonials',
            'edit testimonials',
            'delete testimonials',
            'view faqs',
            'create faqs',
            'edit faqs',
            'delete faqs',
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            'view settings',
        ]);

        // Manager — content management only
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'view dashboard',
            'view products',
            'create products',
            'edit products',
            'view categories',
            'create categories',
            'edit categories',
            'view pages',
            'create pages',
            'edit pages',
            'view banners',
            'create banners',
            'edit banners',
            'view media',
            'upload media',
            'view contacts',
            'reply contacts',
            'view events',
            'create events',
            'edit events',
            'view notices',
            'create notices',
            'edit notices',
            'view testimonials',
            'create testimonials',
            'edit testimonials',
            'view faqs',
            'create faqs',
            'edit faqs',
            'view clients',
            'create clients',
            'edit clients',
        ]);

        // Staff — read + limited create
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->syncPermissions([
            'view dashboard',
            'view products',
            'view categories',
            'view pages',
            'view media',
            'upload media',
            'view contacts',
            'view events',
            'view notices',
            'view testimonials',
            'view faqs',
            'view clients',
        ]);

        // ── Assign superadmin role to existing admin user ──
        $superAdminUser = User::where('is_admin', true)->first();
        if ($superAdminUser) {
            $superAdminUser->syncRoles('superadmin');
            $superAdminUser->role = 'superadmin';
            $superAdminUser->save();
        }

        $this->command->info('✅ Roles and permissions seeded successfully!');
    }
}
