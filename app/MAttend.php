<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MAttend extends Model
{
    //
    protected $table = 'm_attends';
    protected $primaryKey = 'id';
    protected $fillable = ['date','mprof_id','tmtable_id','in_time', 'in_tolerance_time', 'out_time','over_time', 'late_time', 'first_attend', 'last_attend','created_at', 'updated_at','type_data','noted','status_employee','machine_id','lat_attend', 'lon_attend','out_location', 'out_face','status_sync','status_sync_in'];
}
