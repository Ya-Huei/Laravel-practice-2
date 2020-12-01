<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    public $timestamps = true;

    protected $dates = ['enabled_at'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function repairRecords()
    {
        $this->hasMany(RepairRecord::class);
    }

    public function otaRecords()
    {
        $this->hasMany(OtaRecord::class);
    }

    public function scopeOfFirmId($query, $firmId)
    {
        if ($firmId !== null) {
            return $query->where('firm_id', $firmId);
        }
    }

    public function scopeOfLocationId($query, $locationId)
    {
        if ($locationId !== null) {
            return $query->where('location_id', $locationId);
        }
    }
}
