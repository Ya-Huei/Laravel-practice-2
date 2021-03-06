<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\Statuses;
use App\Enums\StatusTypes;
use App\Http\Requests\Devices\DeviceEditValidation;
use App\Http\Requests\Devices\DeviceUpdateFormValidation;
use App\Http\Requests\Devices\DeviceRepairValidation;
use App\Http\Requests\Devices\DeviceSaveRepairFormValidation;
use App\Models\Device;
use App\Models\Status;
use App\Models\RepairRecord;
use App\Services\LocationsService;
use App\Services\FirmsService;
use App\Services\StatusesService;
use App\Services\DevicesService;

class DevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = DevicesService::getDeviceInfo();
        return response()->json($devices);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DeviceEditValidation $request, Device $device)
    {
        LocationsService::getLocationInfo($device, $device->location_id);
        $locations = LocationsService::getLocationsOptions();
        $firms = FirmsService::getFirmsOptions();
        $status = StatusesService::getStatusesOptions(StatusTypes::DEVICE);
        return response()->json(compact('device', 'locations', 'firms', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceUpdateFormValidation $request, Device $device)
    {
        $validatedData = $request->validated();

        if (array_key_exists("country", $validatedData)) {
            if (!empty($validatedData['country'])) {
                $device->location_id = LocationsService::getLocationId($validatedData['country'], $validatedData['region'], $validatedData['city']);
            } else {
                $device->location_id = null;
            }
        }

        if (array_key_exists("firm", $validatedData) && isset($validatedData['firm'])) {
            if ($validatedData['firm'] === "0") {
                $device->firm_id = null;
            } else {
                $device->firm_id = $validatedData['firm'];
            }
        }

        $device->address = $validatedData['address'];
        $device->status_id = $validatedData['status'];

        if ($device->status_id == Statuses::ENABLE && !isset($device->enabled_at)) {
            $device->enabled_at = now();
        }

        $device->save();

        return response()->json(['status' => 'success']);
    }

    public function repair(DeviceRepairValidation $request, Device $device)
    {
        return response()->json($device);
    }

    public function saveRepair(DeviceSaveRepairFormValidation $request, Device $device)
    {
        $device->status_id = Statuses::REPAIR;
        $device->save();
    
        $record = new RepairRecord();
        $record->device_id = $device->id;
        $record->reason = $request->reason;
        $record->status_id = Statuses::REPAIR;
        $record->save();
    
        return response()->json(['status' => 'success']);
    }
}
