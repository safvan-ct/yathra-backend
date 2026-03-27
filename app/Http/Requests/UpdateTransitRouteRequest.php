<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransitRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin_id'      => 'sometimes|exists:stations,id|different:destination_id',
            'destination_id' => 'sometimes|exists:stations,id|different:origin_id',
            'variant'        => 'sometimes|string|max:100',
            'distance'       => 'sometimes|numeric|min:0',
            'is_active'      => 'sometimes|boolean',
        ];
    }
}
