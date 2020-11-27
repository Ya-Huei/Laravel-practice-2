<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';
    public $timestamps = false;

    public function devices()
    {
        $this->hasMany(Device::class);
    }

    public function repairRecords()
    {
        $this->hasMany(RepairRecord::class);
    }

    public function otaRecords()
    {
        $this->hasMany(OtaRecord::class);
    }
}
