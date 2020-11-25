<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\Status;
use App\Services\LocationsService;
use App\Services\FirmsService;
use App\Services\StatusesService;
use App\Http\Requests\DeviceUpdateFormValidation;

class DevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Device::with('location', 'firm', 'status')->orderBy('id', 'asc')->get();
        $devices = $this->formatDevices($data);
        return response()->json($devices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Device $device)
    {
        LocationsService::getLocationInfo($device, $device->location_id);
        FirmsService::getFirmInfo($device, $device->firm_id);
        StatusesService::getStatusInfo($device, $device->status_id);
        $locations = LocationsService::getLocationsCategory();
        $firms = FirmsService::getAllFirmsName();
        $status = StatusesService::getAllStatusesName();
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
        $country = $request->input('country');
        $region = $request->input('region');
        $city = $request->input('city');
        $device->location_id = !empty($country) ? LocationsService::getLocationId($country, $region, $city) : null;

        $firmName = $request->input('firm');
        $device->firm_id = !empty($firmName) ? FirmsService::getFirmId($firmName) : null;

        $device->address = $request->input('address');

        $status = $request->input('status');
        $device->status_id = StatusesService::getStatusId($status);

        if($status == "Enable" && !isset($device->enabled_at)){
            $device->enabled_at = now();
        }

        $device->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function formatDevices($data)
    {
        $devices = [];
        foreach ($data as $item) {
            $device = [];
            $device['id'] = $item->id;
            $device['serial_no'] = $item->serial_no;
            $device['region'] = isset($item->location) ? LocationsService::format($item->location) : "";
            $device['address'] = $item->address;
            $device['firm'] = isset($item->firm->name) ? $item->firm->name : "";
            $device['status'] = $item->status;
            $device['enabled'] = isset($item->enabled_at) ? $item->enabled_at->format('Y-m-d H:i:s') : "";
            $device['updated'] = isset($item->updated_at) ? $item->updated_at->format('Y-m-d H:i:s') : "";
            $device['registered'] = isset($item->created_at) ? $item->created_at->format('Y-m-d H:i:s') : "";
            array_push($devices, $device);
        }
        return $devices;
    }
}
