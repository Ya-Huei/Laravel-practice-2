<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\Status;
use App\Models\RepairRecord;
use App\Enums\Statuses;
use App\Enums\StatusTypes;
use App\Services\LocationsService;
use App\Services\FirmsService;
use App\Services\StatusesService;
use App\Services\DevicesService;
use App\Http\Requests\Devices\DeviceEditValidation;
use App\Http\Requests\Devices\DeviceUpdateFormValidation;
use App\Http\Requests\Devices\DeviceRepairValidation;
use App\Http\Requests\Devices\DeviceSaveRepairFormValidation;

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
        FirmsService::getFirmInfo($device, $device->firm_id);
        StatusesService::getStatusInfo($device, $device->status_id);
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
        if (!empty($validatedData['country'])) {
            $device->location_id = LocationsService::getLocationId($validatedData['country'], $validatedData['region'], $validatedData['city']);
        }

        if (isset($validatedData['firm'])) {
            $device->firm_id = FirmsService::getFirmId($validatedData['firm']);
        }

        $device->address = $validatedData['address'];
        $device->status_id = StatusesService::getStatusId($validatedData['status'], StatusTypes::DEVICE);

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
