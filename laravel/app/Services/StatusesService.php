<?php

namespace App\Services;

use App\Models\Status;

class StatusesService
{
    public static function getStatusesOptions($type)
    {
        $result = [];
        $defaultOption = "";
        array_push($result, $defaultOption);
        $statuses = Status::select('name')->where('type', $type)->get();
        foreach ($statuses as $item) {
            array_push($result, $item->name);
        }
        return $result;
    }

    public static function getStatusId($statusName, $type)
    {
        $status = Status::where('name', $statusName)->where('type', $type)->first();
        return $status->id;
    }

    public static function getStatusInfo(&$object, $status_id)
    {
        if (empty($status_id)) {
            $object->status = "";
        } else {
            $status = Status::where('id', $status_id)->first();
            $object->status = $status->name;
        }
    }
}
