<?php

namespace App\Services;

use App\Models\Location;

class LocationsService
{
    public static function getLocationsOptions()
    {
        $result = [];
        $result['country'] = [];
        $defaultOption = "";
        array_push($result['country'], $defaultOption);
        $locations = Location::all();
        foreach ($locations as $key => $value) {
            self::category($result, $value);
        }

        return $result;
    }

    private static function category(&$result, $value)
    {
        $country = $value->country;
        if (!in_array($country, $result['country'])) {
            array_push($result['country'], $country);
            $result[$country]['region'] = [];
        }

        $region = $value->region;
        if (!in_array($region, $result[$country]['region'])) {
            array_push($result[$country]['region'], $region);
            $result[$country][$region]['city'] = [];
        }

        $city = $value->city;
        if (!in_array($city, $result[$country][$region]['city'])) {
            array_push($result[$country][$region]['city'], $city);
        }
    }

    public static function format($location)
    {
        return $location->country . ' ' . $location->region . ' ' . $location->city;
    }

    public static function getLocationId($country, $region, $city)
    {
        $location = Location::where('country', $country)
            ->where('region', $region)
            ->where('city', $city)
            ->first();

        return $location->id;
    }

    public static function getLocationInfo(&$object, $location_id)
    {
        if (empty($location_id)) {
            $object->country = "";
            $object->region = "";
            $object->city = "";
        } else {
            $location = Location::where('id', $location_id)->first();
            $object->country = $location->country;
            $object->region = $location->region;
            $object->city = $location->city;
        }
    }
}
