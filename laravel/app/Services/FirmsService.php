<?php

namespace App\Services;

use App\Models\Firm;

class FirmsService
{
    public static function getFirmsOptions()
    {
        $firms = Firm::select('name')->get();
        $result = $firms->pluck("name")->toArray();
        array_unshift($result, "");
        return $result;
    }

    public static function getFirmId($firmName)
    {
        $firm = Firm::where('name', $firmName)->first();
        return $firm->id;
    }

    public static function getFirmInfo(&$object, $firm_id)
    {
        if (empty($firm_id)) {
            $object->firm = "";
        } else {
            $firm = Firm::where('id', $firm_id)->first();
            $object->firm = $firm->name;
        }
    }
}
