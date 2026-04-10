<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->when(isset($this->id), $this->id),
            'city_id'      => $this->when(isset($this->city_id), $this->city_id),
            'parent_id'    => $this->when(isset($this->parent_id), $this->parent_id),
            'name'         => $this->when(isset($this->name), $this->name),
            'display_name' => $this->getDisplayName(),
            'code'         => $this->when(isset($this->code), $this->code),
            'local_name'   => $this->when(isset($this->local_name), $this->local_name),
            'lat'          => $this->when(isset($this->lat), $this->lat),
            'long'         => $this->when(isset($this->long), $this->long),
            'type'         => $this->when(isset($this->type), $this->type),
            'is_active'    => $this->when(isset($this->is_active), $this->is_active),
            'city'         => new CityResource($this->whenLoaded('city')),
            'local_body'   => new CityResource($this->whenLoaded('localBody')),
        ];
    }

    private function getDisplayName(): string
    {
        $parts = [];

        // local body
        if ($this->relationLoaded('localBody') && $this->localBody?->name) {
            if (strcasecmp($this->localBody->name, $this->name) !== 0) {
                $parts[] = $this->localBody->name;
            }
        }

        if (count($parts)) {
            return ' (' . implode(', ', $parts) . ')';
        }

        // city
        if ($this->relationLoaded('city') && $this->city?->name) {
            if (
                strcasecmp($this->city->name, $this->name) !== 0 &&
                ! in_array($this->city->name, $parts)
            ) {
                $parts[] = $this->city->name;
            }
        }

        return count($parts) ? ' (' . implode(', ', $parts) . ')' : '';
    }
}
