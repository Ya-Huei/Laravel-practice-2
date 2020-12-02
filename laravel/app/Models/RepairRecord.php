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
    
    public function scopeOfFirmId($query, $firmId)
    {
        if ($firmId !== null) {
            return $query->whereHas('device', function ($query) use ($firmId) {
                $query->where('firm_id', $firmId);
            });
        }
    }

    public function scopeOfLocationId($query, $locationId)
    {
        if ($locationId !== null) {
            return $query->whereHas('device', function ($query) use ($locationId) {
                $query->where('location_id', $locationId);
            });
        }
    }
}
