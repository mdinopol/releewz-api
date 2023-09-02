<?php

namespace Database\Seeders;

use App\Enum\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OAuthClientSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('oauth_clients')->insertOrIgnore([
            'id'                     => 1,
            'name'                   => 'Password Grant App Client',
            'secret'                 => is_local_or_testing() ? 'app_secret' : Str::random(40),
            'provider'               => 'users',
            'redirect'               => config('app.url'),
            'personal_access_client' => 0,
            'password_client'        => 1,
            'minimum_role'           => null,
            'for_role'               => null,
            'created_at'             => Carbon::now(),
            'updated_at'             => Carbon::now(),
        ]);

        DB::table('oauth_clients')->insertOrIgnore([
            'id'                     => 2,
            'name'                   => 'Password Grant Admin Client',
            'secret'                 => is_local_or_testing() ? 'admin_secret' : Str::random(40),
            'redirect'               => config('app.url'),
            'provider'               => 'users',
            'personal_access_client' => 0,
            'password_client'        => 1,
            'minimum_role'           => Role::ADMIN->value,
            'for_role'               => null,
            'created_at'             => Carbon::now(),
            'updated_at'             => Carbon::now(),
        ]);
    }
}
