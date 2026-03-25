<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'district_id' => 'required|exists:districts,id',
            'name'        => 'required|string|max:255',
            'local_name'  => 'sometimes|string|max:255',
            'code'        => 'required|string|max:10|unique:cities,code',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}
