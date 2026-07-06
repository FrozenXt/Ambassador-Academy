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
            ['email' => 'sujallc30@gmail.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'sujallc30@gmail.com',
                'password' => Hash::make('sujal123'),
                'is_admin' => true,
                'role'     => 'super_admin',
            ]
        );
    }
}
