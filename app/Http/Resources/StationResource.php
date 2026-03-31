<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->when(isset($this->id), $this->id),
            'city_id'     => $this->when(isset($this->city_id), $this->city_id),
            'name'        => $this->when(isset($this->name), $this->name),
            'code'        => $this->when(isset($this->code), $this->code),
            'locale_name' => $this->when(isset($this->locale_name), $this->locale_name),
            'lat'         => $this->when(isset($this->lat), $this->lat),
            'long'        => $this->when(isset($this->long), $this->long),
            'type'        => $this->when(isset($this->type), $this->type),
            'is_active'   => $this->when(isset($this->is_active), $this->is_active),
            'city'        => new CityResource($this->whenLoaded('city')),
        ];
    }
}
