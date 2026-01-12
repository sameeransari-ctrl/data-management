<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'id' => 1,
                'setting_key' => 'app.logo',
                'setting_label' => 'logo',
                'setting_value' => null,
            ],
            [
                'id' => 2,
                'setting_key' => 'app.name',
                'setting_label' => 'app name',
                'setting_value' => config('app.name'),
            ],
            [
                'id' => 3,
                'setting_key' => 'app.google_analytics',
                'setting_label' => 'google analytics',
                'setting_value' => null,
            ]
        ];

        Setting::insert($settings);
    }
}
