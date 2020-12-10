<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use App\Enums\OtaTypes;
use App\Services\FirmwareService;
use App\Services\RecipesService;

class OtaRecord extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'serial_no'     => $this->device->serial_no,
            'type'          => $this->type,
            'version'       => $this->getTypeDetail($this->type, $this->type_id),
            'status'        => $this->status,
            'updated'       => $this->updated_at->format('Y-m-d H:i:s'),
            'registered'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }

    private function getTypeDetail($type, $typeId)
    {
        $detail = null;
        switch ($type) {
            case OtaTypes::FIRMWARE:
                $detail = FirmwareService::getFirmwareInfo($typeId)->version;
                break;
            case OtaTypes::RECIPE:
                $detail = RecipesService::geRecipeInfo($typeId)->name;
                break;
        }
        return $detail;
    }
}
