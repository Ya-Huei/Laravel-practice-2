<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    protected $table = 'firms';
    public $timestamps = true;

    public function users(){
        $this->hasMany('App\User');
    }

    public function devices(){
        $this->hasMany(Device::class);
    }
}
