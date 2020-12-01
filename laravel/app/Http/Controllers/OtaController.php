<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\OtaRecord;
use App\Models\Firmware;
use App\Models\Recipe;
use App\Models\Device;
use App\Enums\Statuses;
use App\Enums\OtaTypes;
use App\Services\DevicesService;
use App\Services\FirmwareService;
use App\Services\RecipesService;
use App\Http\Requests\Otas\OtaShowValidation;
use App\Http\Requests\Otas\DeviceUpdateOtaFormValidation;

class OtaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = OtaRecord::with('device', 'status')->orderBy('id', 'desc')->ofFirmId(auth()->user()->firm_id)->get();
        $ota = $this->formatOta($data);
        return response()->json($ota);
    }

    /**
     * Display the specified resource.
     *
     * @param  OtaShowValidation  $request
     * @param  OtaRecord  $ota
     * @return \Illuminate\Http\Response
     */
    public function show(OtaShowValidation $request, OtaRecord $ota)
    {
        $ota->status = $ota->status;
        $ota->device = $ota->device;
        $this->getTypeIdDetail($ota);
        $ota->updated = isset($ota->updated_at) ? $ota->updated_at->format('Y-m-d H:i:s') : "";
        $ota->registered = isset($ota->created_at) ? $ota->created_at->format('Y-m-d H:i:s') : "";
        
        return response()->json($ota);
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
            $firm_id = Device::where('id', $value)->first()->firm_id;
            if ($firm_id !== auth()->user()->firm_id) {
                continue;
            }
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
