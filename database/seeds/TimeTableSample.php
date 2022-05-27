<?php

use Illuminate\Database\Seeder;

class TimeTableSample extends Seeder
{
    /**
     * Run the database seeds:
     *
     * @return void
     */
    public function run()
    {
        \App\Tmtable::create(['day'=>'Monday', 'type'=>'in', 'start_at' => '05:01:00', 'end_at' => '09:00:00']);
        \App\Tmtable::create(['day'=>'Monday', 'type'=>'in_tolerance', 'start_at' => '09:01:00', 'end_at' => '10:00:00']);
        \App\Tmtable::create(['day'=>'Monday', 'type'=>'late', 'start_at' => '10:01:00', 'end_at' => '13:00:00']);
        \App\Tmtable::create(['day'=>'Monday', 'type'=>'out', 'start_at' => '17:01:00', 'end_at' => '18:00:00']);
        \App\Tmtable::create(['day'=>'Monday', 'type'=>'over', 'start_at' => '18:01:00', 'end_at' => '23:59:00']);

        \App\Tmtable::create(['day'=>'Tuesday', 'type'=>'in', 'start_at' => '05:01:00', 'end_at' => '09:00:00']);
        \App\Tmtable::create(['day'=>'Tuesday', 'type'=>'in_tolerance', 'start_at' => '09:01:00', 'end_at' => '10:00:00']);
        \App\Tmtable::create(['day'=>'Tuesday', 'type'=>'late', 'start_at' => '10:01:00', 'end_at' => '13:00:00']);
        \App\Tmtable::create(['day'=>'Tuesday', 'type'=>'out', 'start_at' => '17:01:00', 'end_at' => '18:00:00']);
        \App\Tmtable::create(['day'=>'Tuesday', 'type'=>'over', 'start_at' => '18:01:00', 'end_at' => '23:59:00']);

        \App\Tmtable::create(['day'=>'Wednesday', 'type'=>'in', 'start_at' => '05:01:00', 'end_at' => '09:00:00']);
        \App\Tmtable::create(['day'=>'Wednesday', 'type'=>'in_tolerance', 'start_at' => '09:01:00', 'end_at' => '10:00:00']);
        \App\Tmtable::create(['day'=>'Wednesday', 'type'=>'late', 'start_at' => '10:01:00', 'end_at' => '13:00:00']);
        \App\Tmtable::create(['day'=>'Wednesday', 'type'=>'out', 'start_at' => '17:01:00', 'end_at' => '18:00:00']);
        \App\Tmtable::create(['day'=>'Wednesday', 'type'=>'over', 'start_at' => '18:01:00', 'end_at' => '23:59:00']);

        \App\Tmtable::create(['day'=>'Thursday', 'type'=>'in', 'start_at' => '05:01:00', 'end_at' => '09:00:00']);
        \App\Tmtable::create(['day'=>'Thursday', 'type'=>'in_tolerance', 'start_at' => '09:01:00', 'end_at' => '10:00:00']);
        \App\Tmtable::create(['day'=>'Thursday', 'type'=>'late', 'start_at' => '10:01:00', 'end_at' => '13:00:00']);
        \App\Tmtable::create(['day'=>'Thursday', 'type'=>'out', 'start_at' => '17:01:00', 'end_at' => '18:00:00']);
        \App\Tmtable::create(['day'=>'Thursday', 'type'=>'over', 'start_at' => '18:01:00', 'end_at' => '23:59:00']);

        \App\Tmtable::create(['day'=>'Friday', 'type'=>'in', 'start_at' => '05:01:00', 'end_at' => '09:00:00']);
        \App\Tmtable::create(['day'=>'Friday', 'type'=>'in_tolerance', 'start_at' => '09:01:00', 'end_at' => '10:00:00']);
        \App\Tmtable::create(['day'=>'Friday', 'type'=>'late', 'start_at' => '10:01:00', 'end_at' => '13:00:00']);
        \App\Tmtable::create(['day'=>'Friday', 'type'=>'out', 'start_at' => '17:01:00', 'end_at' => '18:00:00']);
        \App\Tmtable::create(['day'=>'Friday', 'type'=>'over', 'start_at' => '18:01:00', 'end_at' => '23:59:00']);

        
        \App\Tmtable::create(['day'=>'Saturday', 'type'=>'in', 'start_at' => '05:01:00', 'end_at' => '09:00:00']);
        \App\Tmtable::create(['day'=>'Saturday', 'type'=>'in_tolerance', 'start_at' => '09:01:00', 'end_at' => '10:00:00']);
        \App\Tmtable::create(['day'=>'Saturday', 'type'=>'late', 'start_at' => '10:01:00', 'end_at' => '13:00:00']);
        \App\Tmtable::create(['day'=>'Saturday', 'type'=>'out', 'start_at' => '17:01:00', 'end_at' => '18:00:00']);
        \App\Tmtable::create(['day'=>'Saturday', 'type'=>'over', 'start_at' => '18:01:00', 'end_at' => '23:59:00']);

        \App\Tmtable::create(['day'=>'Sunday', 'type'=>'in', 'start_at' => '05:01:00', 'end_at' => '09:00:00']);
        \App\Tmtable::create(['day'=>'Sunday', 'type'=>'in_tolerance', 'start_at' => '09:01:00', 'end_at' => '10:00:00']);
        \App\Tmtable::create(['day'=>'Sunday', 'type'=>'late', 'start_at' => '10:01:00', 'end_at' => '13:00:00']);
        \App\Tmtable::create(['day'=>'Sunday', 'type'=>'out', 'start_at' => '17:01:00', 'end_at' => '18:00:00']);
        \App\Tmtable::create(['day'=>'Sunday', 'type'=>'over', 'start_at' => '18:01:00', 'end_at' => '23:59:00']);
        // \App\Tmtable::create(['day'=>'Saturday', 'type'=>'over', 'start_at' => '05:01', 'end_at' => '23:59']);

        // \App\Tmtable::create(['day'=>'Sunday', 'type'=>'over', 'start_at' => '05:01', 'end_at' => '23:59']);
    }
}
