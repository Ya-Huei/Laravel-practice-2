<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Enums\StatusTypes;
use App\Http\Requests\Repairs\RepairEditValidation;
use App\Http\Requests\Repairs\RepairUpdateFormValidation;
use App\Http\Resources\RepairRecordCollection;
use App\Models\Device;
use App\Models\RepairRecord;
use App\Services\StatusesService;

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
        $repairs = new RepairRecordCollection($data);
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
        $repair->status_id = $request->status;
        $repair->save();

        $device = $repair->device()->first();
        $device->status_id = $request->status;
        $device->save();

        return response()->json(['status' => 'success']);
    }
}
