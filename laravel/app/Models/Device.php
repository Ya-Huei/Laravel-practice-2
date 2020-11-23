<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    public $timestamps = true;

    protected $appends = ['status'];

    private $statusLists =  [
        '1' => 'normal',
        '2' => 'repaire',
        '3' => 'under repair',
        '4' => 'no use',
    ];

    public function getStatusAttribute()
    {
        return $this->statusLists[$this->attributes['status']];
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }
}
