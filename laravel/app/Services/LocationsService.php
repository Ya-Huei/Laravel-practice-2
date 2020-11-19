<?php

namespace App\Services;

use App\Models\Location;
use Illuminate\Support\Facades\Log;

class LocationsService
{
    public static function getLocationsCategory()
    {
        $result = [];
        $result['country'] = [];
        $locations = Location::all();
        foreach ($locations as $key => $value) {
            self::category($result, $value);
        }
        return $result;
    }

    public static function category(&$result, $value){
        $country = $value->country;
        if(!in_array($country, $result['country'])){
            array_push($result['country'], $country);
            $result[$country] = [];
        }

        $region = $value->region;
        if(!in_array($region, $result[$country])){
            array_push($result[$country], $region);
            $result[$country][$region] = [];
        }

        $city = $value->city;
        if(!in_array($city, $result[$country][$region])){
            array_push($result[$country][$region], $city);
        }
    }
}
