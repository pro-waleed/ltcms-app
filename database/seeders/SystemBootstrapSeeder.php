<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SystemBootstrapSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            OpportunityTypesSeeder::class,
            UsersSeeder::class,
        ]);
    }
}
