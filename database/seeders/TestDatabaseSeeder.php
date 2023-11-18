<?php

namespace Database\Seeders;

use Database\Seeders\Tests\GameSeeder;
use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            GameSeeder::class,
        ]);
    }
}
