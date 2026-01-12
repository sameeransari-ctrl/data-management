<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\ProductClass;
use Illuminate\Database\Seeder;

class ProductClassTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = [
            [
                'id' => 1,
                'name' => 'Class 1',
                'status' => ProductClass::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Class 2',
                'status' => ProductClass::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Class 3',
                'status' => ProductClass::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Class 4',
                'status' => ProductClass::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'Class 5',
                'status' => ProductClass::STATUS_ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        ProductClass::insert($classes);
    }
}
