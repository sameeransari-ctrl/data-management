<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Role as UserRole;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate(
            [
                'email' => 'backend@mailinator.com',
            ],
            [
                'name' => 'Admin',
                'email' => 'backend@mailinator.com',
                'phone_code' => '91',
                'phone_number' => '1234512345',
                'user_type' => User::TYPE_ADMIN,
                'password' => 'Test@123',
                'status' => User::STATUS_ACTIVE,
            ]
        );

        $user->assignRole(UserRole::TYPE_SUPER_ADMIN);
    }
}
