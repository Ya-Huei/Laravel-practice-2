<?php

namespace App\Services;

use App\Models\Status;

class StatusesService
{
    // public static function getStatusesOptions($type)
    // {
    //     $statuses = Status::select('name')->where('type', $type)->get();
    //     $result = $statuses->pluck("name")->toArray();
    //     array_unshift($result, "");
    //     return $result;
    // }

    public static function getStatusesOptions($type)
    {
        $statuses = Status::where('type', $type)->get()->toArray();
        $result = [];
        foreach ($statuses as $key => $value) {
            $result[$key]['value'] = $value['id'];
            $result[$key]['label'] = $value['name'];
        }
        return $result;
    }
}
