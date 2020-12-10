<?php

namespace App\Services;

use App\Models\Firm;

class FirmsService
{
    public static function getFirmsOptions()
    {
        $firms = Firm::all()->toArray();
        $result = [];
        $result[0]['value'] = "0";
        $result[0]['label'] = "Not Belong Any Firm";
        foreach ($firms as $key => $value) {
            $result[$key + 1]['value'] = $value['id'];
            $result[$key + 1]['label'] = $value['name'];
        }
        return $result;
    }
}
