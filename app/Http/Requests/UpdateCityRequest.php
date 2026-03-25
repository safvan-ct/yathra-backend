<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'district_id' => 'sometimes|exists:districts,id',
            'name'        => 'sometimes|string|max:255',
            'local_name'  => 'sometimes|string|max:255',
            'code'        => ['sometimes', 'string', 'max:10', Rule::unique('cities', 'code')->ignore($this->route('city'))],
            'is_active'   => 'sometimes|boolean',
        ];
    }
}
