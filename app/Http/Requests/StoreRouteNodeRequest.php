<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteNodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'station_id'           => 'required|exists:stations,id',
            'stop_sequence'        => 'required|integer|min:1',
            'distance_from_origin' => 'required|numeric|min:0',
            'is_active'            => 'sometimes|boolean',
        ];
    }
}
