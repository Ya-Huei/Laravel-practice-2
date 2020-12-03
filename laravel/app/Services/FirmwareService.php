<?php

namespace App\Services;

use App\Models\Firmware;

class FirmwareService
{
    public static function getFirmwareOptions()
    {
        $firmware = Firmware::select('version')->get();
        $result = $firmware->pluck("version")->toArray();
        array_unshift($result, "");
        return $result;
    }

    public static function getFirmwareId($version)
    {
        $firmware = Firmware::where('version', $version)->first();
        return $firmware->id;
    }

    public static function getFirmwareInfo($firmware_id)
    {
        $firmware = Firmware::where('id', $firmware_id)->first();
        return $firmware;
    }
}
