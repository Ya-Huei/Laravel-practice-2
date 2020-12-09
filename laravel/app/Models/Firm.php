<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Firm extends Model
{
    use HasFactory;
    
    protected $table = 'firms';
    public $timestamps = true;

    public function users(){
        $this->hasMany('App\User');
    }

    public function devices(){
        $this->hasMany(Device::class);
    }

    public function recipes(){
        $this->hasMany(Recipe::class);
    }
}
