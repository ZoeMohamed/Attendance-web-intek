<?php

use App\MProf;
use Illuminate\Database\Seeder;

class MProf_Seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MProf::create([
            'name' => 'neina',
            'position' => 'Staff Programmer',
            // 'created_at' => '',
            // 'updated_at' => '',
            'phone_number' => '628123456678' ,
            'division_id' => 1,
            'user_id' => 1,
            'status' => 1
        ]);


        MProf::create([
            'name' => 'zoe',
            'position' => 'Staff Programmer',
            // 'created_at' => '',
            // 'updated_at' => '',
            'phone_number' => '628133456678' ,
            'division_id' => 1,
            'user_id' => 2,
            'status' => 1
        ]);

        MProf::create([
            'name' => 'aldy',
            'position' => 'Staff Programmer',
            // 'created_at' => '',
            // 'updated_at' => '',
            'phone_number' => '628133456678' ,
            'division_id' => 1,
            'user_id' => 3,
            'status' => 1
        ]);
    }
}
