<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'local_name' => $this->local_name,
            'code'       => $this->code,
            'is_active'  => $this->is_active,
            'state'      => new StateResource($this->whenLoaded('state')),
            'cities'     => CityResource::collection($this->whenLoaded('cities')),
        ];
    }
}
