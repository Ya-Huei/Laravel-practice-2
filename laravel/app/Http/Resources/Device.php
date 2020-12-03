<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\LocationsService;

class Device extends JsonResource
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
            'serial_no'     => $this->serial_no,
            'region'        => LocationsService::format($this->location),
            'address'       => $this->address,
            'firm'          => isset($this->firm) ? $this->firm->name : "",
            'status'        => $this->status,
            'enabled'       => isset($this->enabled_at) ? $this->enabled_at->format('Y-m-d H:i:s') : "",
            'updated'       => $this->updated_at->format('Y-m-d H:i:s'),
            'registered'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
