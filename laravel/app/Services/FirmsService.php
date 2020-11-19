<?php

namespace App\Services;

use App\Models\Firm;
use Illuminate\Support\Facades\Log;

class FirmsService
{
    public static function getAllFirmsName()
    {
        $result = [];
        $firms = Firm::select('name')->get();
        foreach($firms as $item){
            array_push($result, $item->name);
        }
        return $result;
    }
}
