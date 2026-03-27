<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bus_id'         => 'required|exists:buses,id',
            'route_id'       => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time'   => 'required|date_format:H:i',
            'days_of_week'   => 'required|array|size:7',
            'days_of_week.*' => 'required|boolean',
            'status'         => 'sometimes|in:Active,Cancelled,Delayed',
        ];
    }
}
