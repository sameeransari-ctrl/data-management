<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Method run
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('permissions')->delete();

        $permissions = [
            [
                'id' => 1,
                'name' => 'admin.dashboard.index',
                'title' => 'View',
                'module_name' => 'Dashboard',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // [
            //     'id' => 2,
            //     'name' => 'admin.user.index',
            //     'title' => 'List',
            //     'module_name' => 'User Management',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 3,
            //     'name' => 'admin.user.show',
            //     'title' => 'Read',
            //     'module_name' => 'User Management',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 4,
            //     'name' => 'admin.user.create',
            //     'title' => 'Create',
            //     'module_name' => 'User Management',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 5,
            //     'name' => 'admin.user.edit',
            //     'title' => 'Edit',
            //     'module_name' => 'User Management',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            [
                'id' => 6,
                'name' => 'admin.role.index',
                'title' => 'List',
                'module_name' => 'Role Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'name' => 'admin.role.create',
                'title' => 'Create',
                'module_name' => 'Role Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'name' => 'admin.role.edit',
                'title' => 'Edit',
                'module_name' => 'Role Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'name' => 'admin.staff.index',
                'title' => 'List',
                'module_name' => 'Staff Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 10,
                'name' => 'admin.staff.create',
                'title' => 'Create',
                'module_name' => 'Staff Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 11,
                'name' => 'admin.staff.edit',
                'title' => 'Edit',
                'module_name' => 'Staff Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // [
            //     'id' => 12,
            //     'name' => 'admin.client.index',
            //     'title' => 'List',
            //     'module_name' => 'Client Management',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 13,
            //     'name' => 'admin.client.show',
            //     'title' => 'Read',
            //     'module_name' => 'Client Management',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 14,
            //     'name' => 'admin.client.create',
            //     'title' => 'Create',
            //     'module_name' => 'Client Management',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 15,
            //     'name' => 'admin.client.edit',
            //     'title' => 'Edit',
            //     'module_name' => 'Client Management',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 16,
            //     'name' => 'admin.product.index',
            //     'title' => 'List',
            //     'module_name' => 'Product',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 17,
            //     'name' => 'admin.product.show',
            //     'title' => 'Read',
            //     'module_name' => 'Product',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 18,
            //     'name' => 'admin.product.create',
            //     'title' => 'Create',
            //     'module_name' => 'Product',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'id' => 19,
            //     'name' => 'admin.product.edit',
            //     'title' => 'Edit',
            //     'module_name' => 'Product',
            //     'guard_name' => 'web',
            //     'status' => '1', //active
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ],
            [
                'id' => 20,
                'name' => 'admin.data.index',
                'title' => 'List',
                'module_name' => 'Data Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 21,
                'name' => 'admin.data.show',
                'title' => 'Read',
                'module_name' => 'Data Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 22,
                'name' => 'admin.data.create',
                'title' => 'Create',
                'module_name' => 'Data Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 23,
                'name' => 'admin.data.edit',
                'title' => 'Edit',
                'module_name' => 'Data Management',
                'guard_name' => 'web',
                'status' => '1', //active
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
