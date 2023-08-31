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
        User::firstOrCreate([
            'user_name' => 'user_1',
            'email'     => 'user@gmail.com',
        ], [
            'first_name'   => 'User',
            'last_name'    => 'Normal',
            'password'     => '123456',
            'role'         => Role::USER->value,
            'country_code' => Country::US->value,
        ]);

        // Superadmin
        User::firstOrCreate([
            'user_name' => 'superadmin_1',
            'email'     => 'superadmin@gmail.com',
        ], [
            'first_name'   => 'Super',
            'last_name'    => 'Admin',
            'password'     => '123456',
            'role'         => Role::SUPER_ADMIN->value,
            'country_code' => Country::ES->value,
        ]);
    }
}
