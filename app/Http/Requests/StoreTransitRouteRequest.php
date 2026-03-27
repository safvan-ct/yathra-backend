<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransitRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin_id'                    => 'required|exists:stations,id|different:destination_id',
            'destination_id'               => 'required|exists:stations,id|different:origin_id',
            'variant'                      => 'required|string|max:100',
            'distance'                     => 'sometimes|numeric|min:0',
            'is_active'                    => 'sometimes|boolean',
            'nodes'                        => 'required|array|min:2',
            'nodes.*.station_id'           => 'required|exists:stations,id',
            'nodes.*.stop_sequence'        => 'required|integer|min:1',
            'nodes.*.distance_from_origin' => 'required|numeric|min:0',
            'nodes.*.is_active'            => 'sometimes|boolean',
        ];
    }
}
