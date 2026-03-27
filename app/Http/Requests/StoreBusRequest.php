<?php
namespace App\Http\Requests;

use App\Services\BusService;
use Illuminate\Foundation\Http\FormRequest;

class StoreBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'operator_id' => 'required|exists:operators,id',
            'bus_number'  => ['required', 'string', 'max:50', 'unique:buses,bus_number', 'regex:' . BusService::INDIAN_BUS_NUMBER_REGEX],
            'category'    => 'sometimes|in:Sleeper,Seater,AC,Ordinary',
            'bus_color'   => 'nullable|string|max:50',
            'total_seats' => 'required|integer|min:1',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}
