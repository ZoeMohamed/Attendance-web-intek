<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TimeTableSample::class);
	    $this->call(UserSeed::class);
        $this->call(MProf_Seed::class);
        $this->call(add_day_seed::class);
        $this->call(m_attend_seeder::class);
        $this->call(OfficeSeeder::class);
        $this->call(NotificationSeeder::class);
    }
}
