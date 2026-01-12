<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                CountriesTableSeeder::class,
                StatesTableSeeder::class,
                RoleSeeder::class,
                AdminUserSeeder::class,
                CmsPagesTableSeeder::class,
                SettingTableSeeder::class,
                ProductClassTableSeeder::class,
                PermissionTableSeeder::class,
                ClientRolesTableSeeder::class,
                BasicUdidSeeder::class,
                CitiesTableSeeder::class,
            ]
        );
    }
}
