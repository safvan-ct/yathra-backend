<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'type'      => $this->type,
            'phone'     => $this->phone,
            'email'     => $this->email,
            'address'   => $this->address,
            'is_public' => $this->is_public,
            'is_active' => $this->is_active,
            'buses'     => BusResource::collection($this->whenLoaded('buses')),
        ];
    }
}
