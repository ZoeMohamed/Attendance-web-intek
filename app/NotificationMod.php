<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationMod extends Model
{
    //
    protected $table = 'notification';
    protected $fillable = ['id', 'images','title','contents'];
}
