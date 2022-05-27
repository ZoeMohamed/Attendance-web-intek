<?php

use Illuminate\Database\Seeder;
use App\MAttend;

class m_attend_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        MAttend::create([
            'date' => '2022-12-23',
            'mprof_id' => 1,
            'tmtable_id' => 1,
            'in_time' => '00:00:00',
            'in_tolerance_time' => '08:58:01',
            'out_time' => '00:00:00',
            'over_time' => '00:00:00',
            'late_time' => '00:00:00',
            'first_attend' => '08:58:01',
            'last_attend' => '08:58:01',
            'created_at' => '2022-12-23 08:58:01',
            'updated_at' => '2022-12-23 08:58:01',
            'type_data' => 0,
            'noted' => "",
            'status_employee' => 0,
            'machine_id' => 4,
            'lat_attend' => "",
            'lon_attend' => "",
            // 'out_location' => null,
            // 'out_face' => null,
            'status_sync' => 0,
            'status_sync_in' =>0 
        ]);
        MAttend::create([
            'date' => '2022-12-23',
            'mprof_id' => 2,
            'tmtable_id' => 1,
            'in_time' => '00:00:00',
            'in_tolerance_time' => '08:58:01',
            'out_time' => '00:00:00',
            'over_time' => '00:00:00',
            'late_time' => '00:00:00',
            'first_attend' => '08:58:01',
            'last_attend' => '08:58:01',
            'created_at' => '2022-12-23 08:58:01',
            'updated_at' => '2022-12-23 08:58:01',
            'type_data' => 0,
            'noted' => "",
            'status_employee' => 0,
            'machine_id' => 4,
            'lat_attend' => "",
            'lon_attend' => "",
            // 'out_location' => null,
            // 'out_face' => null,
            'status_sync' => 0,
            'status_sync_in' =>0 
        ]);

        MAttend::create([
            'date' => '2022-12-23',
            'mprof_id' => 3,
            'tmtable_id' => 1,
            'in_time' => '00:00:00',
            'in_tolerance_time' => '08:58:01',
            'out_time' => '00:00:00',
            'over_time' => '00:00:00',
            'late_time' => '00:00:00',
            'first_attend' => '08:58:01',
            'last_attend' => '08:58:01',
            'created_at' => '2022-12-23 08:58:01',
            'updated_at' => '2022-12-23 08:58:01',
            'type_data' => 0,
            'noted' => "",
            'status_employee' => 0,
            'machine_id' => 4,
            'lat_attend' => "",
            'lon_attend' => "",
            // 'out_location' => null,
            // 'out_face' => null,
            'status_sync' => 0,
            'status_sync_in' =>0 
        ]);

    }
}
