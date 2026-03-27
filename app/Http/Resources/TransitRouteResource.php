<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransitRouteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'origin_id'      => $this->origin_id,
            'destination_id' => $this->destination_id,
            'route_code'     => $this->route_code,
            'path_signature' => $this->path_signature,
            'variant'        => $this->path_signature,
            'distance'       => (float) $this->distance,
            'is_active'      => $this->is_active,
            'origin'         => new StationResource($this->whenLoaded('origin')),
            'destination'    => new StationResource($this->whenLoaded('destination')),
            'nodes'          => RouteNodeResource::collection($this->whenLoaded('nodes')),
        ];
    }
}
