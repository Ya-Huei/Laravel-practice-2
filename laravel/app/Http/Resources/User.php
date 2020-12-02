<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\LocationsService;
use Illuminate\Support\Facades\Log;

class User extends JsonResource
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
            'name'          => $this->name,
            'email'         => $this->email,
            'roles'         => $this->roles->pluck("name"),
            'region'        => LocationsService::format($this->location),
            'firm'          => isset($this->firm) ? $this->firm->name : "",
            'updated'       => $this->updated_at->format('Y-m-d H:i:s'),
            'registered'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
