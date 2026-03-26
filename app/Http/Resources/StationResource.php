<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'city_id'     => $this->city_id,
            'name'        => $this->name,
            'code'        => $this->code,
            'locale_name' => $this->locale_name,
            'lat'         => $this->lat,
            'long'        => $this->long,
            'type'        => $this->type,
            'is_active'   => $this->is_active,
            'city'        => new CityResource($this->whenLoaded('city')),
        ];
    }
}
