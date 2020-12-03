<?php

namespace App\Services;

use App\Http\Resources\DeviceCollection;
use App\Models\Device;

class DevicesService
{
    public static function getDeviceInfo()
    {
        $data = Device::with('location', 'firm', 'status')->orderBy('id', 'asc')->ofFirmId(auth()->user()->firm_id)->ofLocationId(auth()->user()->location_id)->get();
        $devices = new DeviceCollection($data);
        return $devices;
    }
}
