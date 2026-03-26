<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStationRequest extends FormRequest
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
            'city_id'     => 'required|exists:cities,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:10|unique:stations,code',
            'locale_name' => 'nullable|string|max:255',
            'lat'         => 'required|numeric|between:-90,90',
            'long'        => 'required|numeric|between:-180,180',
            'type'        => 'required|in:Hub,Stop,Terminal',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}
