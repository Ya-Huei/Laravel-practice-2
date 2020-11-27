<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\RepairRecord;
use App\Enums\StatusTypes;
use App\Services\StatusesService;
use App\Http\Requests\RepairUpdateFormValidation;

class RepairsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = RepairRecord::with('device', 'status')->orderBy('id', 'desc')->get();
        $repairs = $this->formatRepairs($data);
        return response()->json($repairs);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RepairRecord $repair)
    {
        $device = Device::where("id", $repair->device_id)->first();
        StatusesService::getStatusInfo($repair, $repair->status_id);
        $status = StatusesService::getStatusesNameByType(StatusTypes::DEVICE);
        return response()->json(compact('repair', 'device', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RepairUpdateFormValidation $request, RepairRecord $repair)
    {
        $repair->reason = $request->input('reason');
        $repair->worker = $request->input('worker');

        $status = $request->input('status');
        $status_id = StatusesService::getStatusId($status, StatusTypes::DEVICE);
        $repair->status_id = $status_id;
        $repair->save();

        $device_id = $request->input('device_id');
        $device = Device::where('id', $device_id)->first();
        $device->status_id = $status_id;
        $device->save();

        return response()->json(['status' => 'success']);
    }

    private function formatRepairs($data)
    {
        $repairs = [];
        foreach ($data as $item) {
            $repair = [];
            $repair['id'] = $item->id;
            $repair['serial_no'] = $item->device->serial_no;
            $repair['reason'] = $item->reason;
            $repair['worker'] = $item->worker;
            $repair['status'] = $item->status;
            $repair['updated'] = isset($item->updated_at) ? $item->updated_at->format('Y-m-d H:i:s') : "";
            $repair['registered'] = isset($item->created_at) ? $item->created_at->format('Y-m-d H:i:s') : "";
            array_push($repairs, $repair);
        }

        return $repairs;
    }
}
