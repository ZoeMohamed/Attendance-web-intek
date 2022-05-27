<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MLogging extends Model
{
    //
    protected $table = 'm_loggings';
    protected $fillable = ['mprof_id', 'name', 'log_time','sunrise','status','created_at'];
}
