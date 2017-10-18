<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public $table = 'channel';
    public $guarded = [];

    public function user(){

        return $this->hasOne('App\Models\User','id','user_id');

    }

}
