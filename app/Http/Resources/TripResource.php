<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'bus_id'         => $this->bus_id,
            'route_id'       => $this->route_id,
            'departure_time' => $this->departure_time,
            'arrival_time'   => $this->arrival_time,
            'days_of_week'   => $this->days_of_week,
            'status'         => $this->status,
            'bus'            => new BusResource($this->whenLoaded('bus')),
            'route'          => new TransitRouteResource($this->whenLoaded('route')),
        ];
    }
}
