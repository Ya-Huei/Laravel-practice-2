<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairRecord extends Model
{
    protected $table = 'repair_records';
    public $timestamps = true;

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
