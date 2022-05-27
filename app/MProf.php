<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MProf extends Model
{
    //
    protected $table="m_profs";
    protected $fillable = ['id','name','position','user_id','phone_number', 'division_id', 'status'];
}
