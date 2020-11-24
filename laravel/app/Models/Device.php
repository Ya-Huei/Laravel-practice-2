<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    public $timestamps = true;

    protected $appends = ['status'];

    private static $statusLists =  [
        '1' => 'normal',
        '2' => 'repair',
        '3' => 'under repair',
        '4' => 'no use',
    ];

    public function getStatusAttribute()
    {
        return self::$statusLists[$this->attributes['status']];
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public static function getStatusLists(){
        return self::$statusLists;
    }
}
