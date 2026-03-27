<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bus_id'         => 'sometimes|exists:buses,id',
            'route_id'       => 'sometimes|exists:routes,id',
            'departure_time' => 'sometimes|date_format:H:i',
            'arrival_time'   => 'sometimes|date_format:H:i',
            'days_of_week'   => 'sometimes|array|size:7',
            'days_of_week.*' => 'required_with:days_of_week|boolean',
            'status'         => 'sometimes|in:Active,Cancelled,Delayed',
        ];
    }
}
