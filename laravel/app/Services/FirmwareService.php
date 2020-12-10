<?php

namespace App\Services;

use App\Models\Firmware;

class FirmwareService
{
    public static function getFirmwareOptions()
    {
        $firmware = Firmware::all()->toArray();
        $result = [];

        foreach ($firmware as $key => $value) {
            $result[$key]['value'] = $value['id'];
            $result[$key]['label'] = $value['version'];
        }
        return $result;
    }

    public static function getFirmwareInfo($firmware_id)
    {
        $firmware = Firmware::where('id', $firmware_id)->first();
        return $firmware;
    }
}
