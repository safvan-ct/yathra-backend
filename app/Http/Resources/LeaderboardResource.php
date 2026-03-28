<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id'      => $this->id,
            'phone'        => substr($this->phone, 0, 4) . '****' . substr($this->phone, -2), // mask phone
            'total_points' => (int) $this->total_points,
        ];
    }
}
