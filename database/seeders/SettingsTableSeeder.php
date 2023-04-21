<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'key' => 'title',
            'value' => null,
        ]);

        DB::table('settings')->insert([
            'key' => 'customer_care_number',
            'value' => null,
        ]);

        DB::table('settings')->insert([
            'key' => 'website_url',
            'value' => null,
        ]);

        DB::table('settings')->insert([
            'key' => 'app_url',
            'value' => null,
        ]);

    }
}
