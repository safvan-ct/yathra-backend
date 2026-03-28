<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name ?? 'User',
            'phone'              => $this->phone,

            'trust_score'        => $this->trust_score,
            'trust_level'        => $this->trust_level,

            'contribution_count' => $this->whenCounted('suggestions') ?? 0,
            'total_points'       => $this->whenAggregated('rewards', 'points', 'sum') ?? 0,

            'is_active'          => $this->is_active,
        ];
    }
}
