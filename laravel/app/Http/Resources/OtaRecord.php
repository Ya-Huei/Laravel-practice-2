<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
            'type_id'       => $this->type_id,
            'status'        => $this->status,
            'updated'       => $this->updated_at->format('Y-m-d H:i:s'),
            'registered'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
