<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Role as UserRole;

class RoleSeeder extends Seeder
{
    /**
     * Method run
     *
     * @return void
     */
    public function run(): void
    {
        Role::create(['name' => UserRole::TYPE_SUPER_ADMIN]);
        Role::create(['name' => UserRole::TYPE_ADMIN]);
    }
}
