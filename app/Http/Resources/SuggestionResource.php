<?php
namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuggestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'user_id'          => $this->user_id,
            'suggestable_id'   => $this->suggestable_id,
            'proposed_for'     => $this->proposed_for,
            'type'             => $this->type->value,
            'status'           => $this->status->value,
            'review_note'      => $this->review_note,
            'admin_id'         => $this->admin_id,
            'created_at'       => $this->created_at,
            'created_time'     => Carbon::parse($this->created_at)->format('m/d/Y g:i A'),
            'created_relative' => Carbon::parse($this->created_at)->diffForHumans(['short' => true, 'parts' => 1]),
            'proposed_data'    => $this->proposed_data,
            'user'             => new UserResource($this->whenLoaded('user')),
            'reward'           => new RewardResource($this->whenLoaded('reward')),

            // optionally load the specific model via morphological relation
            $this->mergeWhen($this->relationLoaded('suggestable'), ['suggestable_data' => $this->suggestable]),
        ];
    }
}
