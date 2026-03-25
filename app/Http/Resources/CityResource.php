<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'local_name' => $this->local_name,
            'code'       => $this->code,
            'is_active'  => $this->is_active,
            'district'   => $this->whenLoaded('district', new DistrictResource($this->district)),
        ];
    }
}
