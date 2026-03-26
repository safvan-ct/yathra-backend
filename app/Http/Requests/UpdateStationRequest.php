<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on auth logic
    }

    public function rules(): array
    {
        return [
            'city_id'     => 'sometimes|exists:cities,id',
            'name'        => 'sometimes|string|max:255',
            'code'        => 'sometimes|string|max:10|unique:stations,code,' . $this->route('station'),
            'locale_name' => 'nullable|string|max:255',
            'lat'         => 'sometimes|numeric|between:-90,90',
            'long'        => 'sometimes|numeric|between:-180,180',
            'type'        => 'sometimes|in:Hub,Stop,Terminal',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}
