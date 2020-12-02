<?php

namespace App\Services;

use App\Models\Status;

class StatusesService
{
    public static function getStatusesOptions($type)
    {
        $statuses = Status::select('name')->where('type', $type)->get();
        $result = $statuses->pluck("name")->toArray();
        array_unshift($result, "");
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
