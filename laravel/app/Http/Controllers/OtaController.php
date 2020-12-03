<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Enums\OtaTypes;
use App\Enums\RoleNames;
use App\Enums\Statuses;
use App\Http\Requests\Otas\DeviceUpdateOtaFormValidation;
use App\Http\Requests\Otas\OtaShowValidation;
use App\Http\Resources\OtaRecord as OtaRecordResource;
use App\Http\Resources\OtaRecordCollection;
use App\Http\Resources\ShowOtaRecord;
use App\Models\Device;
use App\Models\Firmware;
use App\Models\OtaRecord;
use App\Models\Recipe;
use App\Services\DevicesService;
use App\Services\FirmwareService;
use App\Services\RecipesService;

class OtaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = OtaRecord::with('device', 'status')->orderBy('id', 'desc')->ofFirmId(auth()->user()->firm_id)->ofLocationId(auth()->user()->location_id)->get();
        $ota = new OtaRecordCollection($data);
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
        return response()->json(new ShowOtaRecord($ota));
    }

    public function getOtaUpdateInfo()
    {
        $devices = DevicesService::getDeviceInfo();
        $firmware = FirmwareService::getFirmwareOptions();
        $recipe = RecipesService::getRecipesOptions();
        return response()->json(compact('devices', 'firmware', 'recipe'));
    }

    public function saveOtaUpdate(DeviceUpdateOtaFormValidation $request)
    {
        $type = OtaRecord::getDefineTypeKey($request->type);
        $typeId = $this->getTypeId($request->type, $request->name);
        foreach ($request->devices as $key => $value) {
            $deviceInfo = Device::where('id', $value)->first();
            if (!$this->checkAuth($deviceInfo)) {
                continue;
            }
            $record = new OtaRecord();
            $record->device_id = $value;
            $record->type = $type;
            $record->type_id = $typeId;
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

    private function checkAuth($device)
    {
        if (auth()->user()->hasRole(RoleNames::ADMIN)) {
            return true;
        }
        if (auth()->user()->hasRole(RoleNames::FIRM) &&
            auth()->user()->firm_id === $device->firm_id) {
            return true;
        }
        if (auth()->user()->hasRole(RoleNames::LOCATION) &&
            auth()->user()->firm_id === $device->firm_id &&
            auth()->user()->location_id === $device->location_id) {
            return true;
        }
        return false;
    }
}
