<?php

namespace Database\Seeders;

use App\Enum\Country;
use App\Enum\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Normal user
        // User::firstOrCreate([
        //     'user_name' => 'normal_user_releewz',
        //     'email'     => 'normal_user@releewz.com',
        // ], [
        //     'first_name'   => 'Normal',
        //     'last_name'    => 'User',
        //     'password'     => 'password',
        //     'role'         => Role::USER,
        //     'country_code' => Country::US,
        // ]);

        // Superadmin
        User::firstOrCreate([
            'user_name' => 'superadmin_user_releewz',
            'email'     => 'superadmin_user@releewz.com',
        ], [
            'first_name'   => 'Super',
            'last_name'    => 'Admin',
            'password'     => 'password',
            'role'         => Role::SUPER_ADMIN,
            'country_code' => Country::ES,
        ]);

        // Admin
        // User::firstOrCreate([
        //     'user_name' => 'admin_user_releewz',
        //     'email'     => 'admin_user@releewz.com',
        // ], [
        //     'first_name'   => 'User',
        //     'last_name'    => 'Admin',
        //     'password'     => 'password',
        //     'role'         => Role::ADMIN,
        //     'country_code' => Country::ES,
        // ]);
    }
}
