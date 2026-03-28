<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'suggestable_type'             => ['required', 'string', 'in:Trip,Route,Station,Bus,RouteNode'],
            'suggestable_id'               => ['nullable', 'integer'],
            'proposed_data'                => ['required', 'array'],
            'proposed_data.departure_time' => 'sometimes|date_format:H:i',
            'proposed_data.arrival_time'   => 'sometimes|date_format:H:i',
        ];
    }
}
