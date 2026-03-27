<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteNodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'route_id'             => $this->route_id,
            'station_id'           => $this->station_id,
            'stop_sequence'        => $this->stop_sequence,
            'distance_from_origin' => (float) $this->distance_from_origin,
            'is_active'            => $this->is_active,
            'station'              => new StationResource($this->whenLoaded('station')),
        ];
    }
}
