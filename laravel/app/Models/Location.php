<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    public $timestamps = false;

    public function users()
    {
        $this->hasMany('App\User');
    }

    public function devices()
    {
        $this->hasMany(Device::class);
    }
}
