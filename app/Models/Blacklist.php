<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    public $table = 'blacklist';
    public $guarded = [];
    public $timestamps = false;

}
