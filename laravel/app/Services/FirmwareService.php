<?php

namespace App\Services;

use App\Models\Firmware;

class FirmwareService
{
    public static function getAllFirmwareVersion()
    {
        $result = [];
        $defaultOption = "";
        array_push($result, $defaultOption);
        $firmware = Firmware::select('version')->get();
        foreach ($firmware as $item) {
            array_push($result, $item->version);
        }
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
