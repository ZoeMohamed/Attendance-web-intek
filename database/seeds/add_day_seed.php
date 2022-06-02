<?php

use Illuminate\Database\Seeder;
USE App\day_off;
class add_day_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        day_off::Create([
            'name' => 'neina',
            'position'=>'staff programmer',
            'departement'=>'programmer',
            'supervisor'=>'ezhar mahesa',
            'replacement_pic'=>'nazwa',
            'reason'=>'kucingnya sakit',
            'submitted_job'=>'fixing attendance cuti function and view',
            'days_off_date'=>date('2022-03-30'),
            'total_days'=>3,
            'back_to_office'=>date('2022-04-01'),
            'remaining_days_off'=>12,
            'days_off_balance'=>0,
            'phone_number'=> '081234567890',
            'status'=>1,
            'user_id'=>1,
            'response_date'=>'2022-03-30 15:15:15',
            'created_at'=>'2022-03-02 13:24:50',
            'updated_at'=>'2022-03-02 13:24:50'
        ]);
        day_off::Create([
            'name' => 'zoe',
            'position'=>'staf programmer',
            'departement'=>'programmer',
            'supervisor'=>'ezhar mahesa',
            'replacement_pic'=>'neina',
            'reason'=>'kucingnya sakit pt 2',
            'submitted_job'=>'fixing attendance cuti function',
            'days_off_date'=>date('2022-04-30'),
            'total_days'=>2,
            'back_to_office'=>date('2022-05-01'),
            'remaining_days_off'=>12,
            'days_off_balance'=>0,
            'phone_number'=> '081234567891',
            'status'=>0,
            'user_id'=>2,
            'response_date'=>'2022-03-30 15:15:15',
            'created_at'=>'2022-03-02 13:24:50',
            'updated_at'=>'2022-03-02 13:24:50'
        ]);

        day_off::Create([
            'name' => 'aldy',
            'position'=>'staf programmer',
            'departement'=>'programmer',
            'supervisor'=>'ezhar mahesa',
            'replacement_pic'=>'neina',
            'reason'=>'Sakit',
            'submitted_job'=>'fixing attendance cuti function',
            'days_off_date'=>date('2022-04-30'),
            'total_days'=>2,
            'back_to_office'=>date('2022-05-01'),
            'remaining_days_off'=>12,
            'days_off_balance'=>0,
            'phone_number'=> '081234567891',
            'status'=>0,
            'user_id'=>3,
            'response_date'=>'2022-03-30 15:15:15',
            'created_at'=>'2022-03-02 13:24:50',
            'updated_at'=>'2022-03-02 13:24:50'
        ]);
    }
}
