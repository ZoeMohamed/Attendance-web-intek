<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tmtable extends Model
{
    protected $fillable = ['id','day', 'type', 'start_at', 'end_at', 'role'];
}
