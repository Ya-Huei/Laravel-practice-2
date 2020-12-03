<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Firmware extends JsonResource
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
            'id'         => $this->id,
            'version'    => $this->version,
            'registered' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
