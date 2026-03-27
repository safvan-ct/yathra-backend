<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'operator_id' => $this->operator_id,
            'bus_number'  => $this->bus_number,
            'category'    => $this->category,
            'bus_color'   => $this->bus_color,
            'total_seats' => $this->total_seats,
            'is_active'   => $this->is_active,
            'operator'    => new OperatorResource($this->whenLoaded('operator')),
        ];
    }
}
