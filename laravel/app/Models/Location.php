<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    
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
