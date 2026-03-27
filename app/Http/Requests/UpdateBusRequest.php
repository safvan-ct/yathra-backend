<?php
namespace App\Http\Requests;

use App\Services\BusService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $busId = $this->route('bus');

        return [
            'operator_id' => 'sometimes|exists:operators,id',
            'bus_number'  => [
                'sometimes', 'string', 'max:50',
                Rule::unique('buses', 'bus_number')->ignore($busId),
                'regex:' . BusService::INDIAN_BUS_NUMBER_REGEX,
            ],
            'category'    => 'sometimes|in:Sleeper,Seater,AC,Ordinary',
            'bus_color'   => 'nullable|string|max:50',
            'total_seats' => 'sometimes|integer|min:1',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}
