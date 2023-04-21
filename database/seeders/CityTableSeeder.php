<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('districts')->delete();
        $cities = array(

            array('district_id' => 'AL-Alappuzha','name' => "Alappuzha"),
            array('district_id' => 'ER-Ernakulam','name' => "Ernakulam"),
            array('district_id' => 'ID-Idukki','name' => "Idukki"),
            array('district_id' => 'KN-Kannur','name' => "Kannur"),
            array('district_id' => 'KS-Kasaragod','name' => "Kasaragod"),
            array('district_id' => 'KL-Kollam','name' => "Kollam"),
            array('district_id' => 'KT-Kottayam','name' => "Kottayam"),
            array('district_id' => 'KZ-Kozhikode','name' => "Kozhikode"),
            array('district_id' => 'MA-Malappuram','name' => "Malappuram"),
            array('district_id' => 'PL-Palakkad','name' => "Palakkad"),
            array('district_id' => 'PT-Pathanamthitta','name' => "Pathanamthitta"),
            array('district_id' => 'TV-Thiruvananthapuram','name' => "Thiruvananthapuram"),
            array('district_id' => 'TS-Thrissur','name' => "Thrissur"),
            array('district_id' => 'WA-Wayanad','name' => "Wayanad"),
        );
        DB::table('districts')->insert($cities);
    }
}
