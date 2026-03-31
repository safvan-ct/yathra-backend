<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RewardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'points'        => $this->points,
            //'activity_type' => $this->activity_type->value,
            'source_id'     => $this->source_id,
            'created_at'    => $this->created_at,
            //'suggestion'    => new SuggestionResource($this->whenLoaded('suggestion')),
        ];
    }
}
