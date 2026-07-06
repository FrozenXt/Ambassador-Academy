<?php

namespace Database\Seeders;

use Modules\Common\Entities\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Entities\SiteSetting;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SiteSettingsSeeder::class,
            AdminSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
