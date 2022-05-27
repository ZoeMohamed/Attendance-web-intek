<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MappingModLoc extends Model
{
    //
    protected $table = 'maping_userloc';
    protected $fillable = ['user_id','office_id','lat','long', 'radius_allow'];
}
