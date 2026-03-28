<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuggestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'user_id'          => $this->user_id,
            'suggestable_type' => $this->suggestable_type,
            'suggestable_id'   => $this->suggestable_id,
            'type'             => $this->type->value,
            'proposed_data'    => $this->proposed_data,
            'status'           => $this->status->value,
            'review_note'      => $this->review_note,
            'admin_id'         => $this->admin_id,
            'user'             => new UserResource($this->whenLoaded('user')),
            // optionally load the specific model via morphological relation
            $this->mergeWhen($this->relationLoaded('suggestable'), ['suggestable_data' => $this->suggestable]),
        ];
    }
}
