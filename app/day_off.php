<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class day_off extends Model
{
    protected $table='days_off';
    protected $fillable =['name','position','departement','supervisor','replacement_pic','reason','submitted_job','days_off_date','total_days','back_to_office','phone_number','remaining_days_off','days_off_balance','response_date','created_at','updated_at','status','user_id'];
}
