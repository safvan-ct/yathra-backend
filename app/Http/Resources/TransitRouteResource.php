<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransitRouteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->when(isset($this->id), $this->id),
            'origin_id'      => $this->when(isset($this->origin_id), $this->origin_id),
            'destination_id' => $this->when(isset($this->destination_id), $this->destination_id),
            'route_code'     => $this->when(isset($this->route_code), $this->route_code),
            'path_signature' => $this->when(isset($this->path_signature), $this->path_signature),
            'distance'       => $this->when(isset($this->distance), (float) $this->distance),
            'is_active'      => $this->when(isset($this->is_active), $this->is_active),
            'origin'         => new StationResource($this->whenLoaded('origin')),
            'destination'    => new StationResource($this->whenLoaded('destination')),
            'nodes'          => RouteNodeResource::collection($this->whenLoaded('nodes')),
        ];
    }
}
