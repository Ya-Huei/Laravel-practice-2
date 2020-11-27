<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\OtaRecord;
use App\Models\Firmware;
use App\Models\Recipe;
use App\Enums\Statuses;
use App\Enums\OtaTypes;
use App\Services\DevicesService;
use App\Services\FirmwareService;
use App\Services\RecipesService;
use App\Http\Requests\DeviceUpdateOtaFormValidation;

class OtaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = OtaRecord::with('device', 'status')->orderBy('id', 'desc')->get();
        $ota = $this->formatOta($data);
        return response()->json($ota);
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
        $ota = OtaRecord::with('device', 'status')->where('id', $id)->first();
        $this->getTypeIdDetail($ota);
        $ota->updated = isset($ota->updated_at) ? $ota->updated_at->format('Y-m-d H:i:s') : "";
        $ota->registered = isset($ota->created_at) ? $ota->created_at->format('Y-m-d H:i:s') : "";
        return response()->json($ota);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    private function formatOta($data)
    {
        $ota = [];
        foreach ($data as $item) {
            $tmp = [];
            $tmp['id'] = $item->id;
            $tmp['serial_no'] = $item->device->serial_no;
            $tmp['type'] = $item->type;
            $tmp['type_id'] = $item->type_id;
            $tmp['status'] = $item->status;
            $tmp['updated'] = isset($item->updated_at) ? $item->updated_at->format('Y-m-d H:i:s') : "";
            $tmp['registered'] = isset($item->created_at) ? $item->created_at->format('Y-m-d H:i:s') : "";
            array_push($ota, $tmp);
        }

        return $ota;
    }

    private function getTypeIdDetail(&$ota)
    {
        switch ($ota->type) {
            case OtaTypes::FIRMWARE:
                $ota->detail = FirmwareService::getFirmwareInfo($ota->type_id)->version;
                break;
            case OtaTypes::RECIPE:
                $ota->detail = RecipesService::geRecipeInfo($ota->type_id)->recipe;
                break;
        }
    }

    public function getOtaUpdateInfo()
    {
        $devices = DevicesService::getDeviceInfo();
        $firmware = FirmwareService::getAllFirmwareVersion();
        $recipe = RecipesService::getAllRecipe();
        return response()->json(compact('devices', 'firmware', 'recipe'));
    }

    public function saveOtaUpdate(DeviceUpdateOtaFormValidation $request)
    {
        $type = OtaRecord::getDefineTypeKey($request->type);
        $type_id = $this->getTypeId($request->type, $request->name);
        foreach ($request->devices as $key => $value) {
            $record = new OtaRecord();
            $record->device_id = $value;
            $record->type = $type;
            $record->type_id = $type_id;
            $record->status_id = Statuses::UPDATING;
            $record->save();
        }
        return response()->json(['status' => 'success']);
    }

    private function getTypeId($type, $name)
    {
        $result_id = "";
        switch ($type) {
            case OtaTypes::FIRMWARE:
                $result_id = FirmwareService::getFirmwareId($name);
                break;
            case OtaTypes::RECIPE:
                $result_id = RecipesService::getRecipeId($name);
                break;
        }
        return $result_id;
    }
}
