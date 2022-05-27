<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MappingMachineUserOri extends Model
{
    //
    protected $table='mapping_machine_and_userid';
    protected $fillable =['id', 'user_id', 'office_id','ori_user_id'];
}
