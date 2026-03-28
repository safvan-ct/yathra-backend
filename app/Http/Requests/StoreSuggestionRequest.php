<?php
namespace App\Http\Requests;

use App\Enums\SuggestionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'type'                         => ['required', 'string', Rule::in(array_column(SuggestionType::cases(), 'value'))],
            'proposed_data'                => ['required', 'array'],
            'proposed_data.departure_time' => 'sometimes|date_format:H:i',
            'proposed_data.arrival_time'   => 'sometimes|date_format:H:i',
        ];
    }
}
