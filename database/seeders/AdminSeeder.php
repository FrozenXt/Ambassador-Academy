<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Entities\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'info@papabargrill.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'info@papabargrill.com',
                'password' => Hash::make('papas123'),
                'is_admin' => true,
                'role'     => 'super_admin',
            ]
        );
    }
}
