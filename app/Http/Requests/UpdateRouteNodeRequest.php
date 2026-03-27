<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteNodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'station_id'           => 'sometimes|exists:stations,id',
            'stop_sequence'        => 'sometimes|integer|min:1',
            'distance_from_origin' => 'sometimes|numeric|min:0',
            'is_active'            => 'sometimes|boolean',
        ];
    }
}
