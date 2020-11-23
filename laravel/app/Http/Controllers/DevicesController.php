<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Services\LocationsService;
use App\Services\FirmsService;

class DevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Device::with('location', 'firm')->orderBy('id', 'asc')->get();
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
        $locations = LocationsService::getLocationsCategory();
        $firms = FirmsService::getAllFirmsName();
        $status = Device::getStatusLists();
        return response()->json(compact('device', 'locations', 'firms', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
            $device['updated'] = $item->updated_at->format('Y-m-d H:i:s');
            $device['registered'] = $item->created_at->format('Y-m-d H:i:s');
            array_push($devices, $device);
        }
        return $devices;
    }
}
