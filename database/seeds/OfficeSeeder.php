<?php

use App\OfficeLocat;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        OfficeLocat::create([
            'id' => 4,
            'name_office' => 'Mobile Apps',
            // 'created_at' => '',
            // 'updated_at' => '',
            'lat' => 0 ,
            'long' => 0 ,
            'address' => "Mobile Apps",
            'radius_allow' => 10000,
            // 'status' => 1
        ]);
    }
}
