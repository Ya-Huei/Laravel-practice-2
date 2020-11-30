<?php

namespace App\Services;

use App\Models\Device;

class DevicesService
{
    public static function getDeviceInfo()
    {
        $data = Device::with('location', 'firm', 'status')->orderBy('id', 'asc')->get();
        $devices = self::formatDevices($data);
        return $devices;
    }

    private static function formatDevices($data)
    {
        $devices = [];
        foreach ($data as $item) {
            $device = [];
            $device['id'] = $item->id;
            $device['serial_no'] = $item->serial_no;
            $device['region'] = isset($item->location) ? LocationsService::format($item->location) : "";
            $device['address'] = $item->address;
            $device['firm'] = isset($item->firm->name) ? $item->firm->name : "";
            $device['status'] = $item->status;
            $device['enabled'] = isset($item->enabled_at) ? $item->enabled_at->format('Y-m-d H:i:s') : "";
            $device['updated'] = isset($item->updated_at) ? $item->updated_at->format('Y-m-d H:i:s') : "";
            $device['registered'] = isset($item->created_at) ? $item->created_at->format('Y-m-d H:i:s') : "";
            array_push($devices, $device);
        }
        return $devices;
    }
}
