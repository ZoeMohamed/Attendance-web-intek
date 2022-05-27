<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfficeLocat extends Model
{
    //
    protected $table="office_location";
    protected $fillable = ['id', 'name_office','lat','long', 'address', 'status', 'created_at','updated_at', 'radius_allow'];
}
