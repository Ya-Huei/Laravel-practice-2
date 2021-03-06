<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OtaTypes;

class OtaRecord extends Model
{
    protected $table = 'ota_records';
    public $timestamps = true;

    protected $append = ['type'];

    private static $typeLists =  [
        '1' => OtaTypes::FIRMWARE,
        '2' => OtaTypes::RECIPE,
    ];

    public function getTypeAttribute()
    {
        return self::$typeLists[$this->attributes['type']];
    }

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
