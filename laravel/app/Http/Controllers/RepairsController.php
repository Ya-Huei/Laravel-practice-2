<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\RepairRecord;
use App\Enums\StatusTypes;
use App\Services\StatusesService;
use App\Http\Requests\Repairs\RepairEditValidation;
use App\Http\Requests\Repairs\RepairUpdateFormValidation;

class RepairsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = RepairRecord::with('device', 'status')->orderBy('id', 'desc')->ofFirmId(auth()->user()->firm_id)->ofLocationId(auth()->user()->location_id)->get();
        $repairs = $this->formatRepairs($data);
        return response()->json($repairs);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RepairEditValidation $request, RepairRecord $repair)
    {
        $device = $repair->device()->first();
        StatusesService::getStatusInfo($repair, $repair->status_id);
        $status = StatusesService::getStatusesOptions(StatusTypes::DEVICE);
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
        $repair->reason = $request->reason;
        $repair->worker = $request->worker;
        $status_id = StatusesService::getStatusId($request->status, StatusTypes::DEVICE);
        $repair->status_id = $status_id;
        $repair->save();

        $device = $repair->device()->first();
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
