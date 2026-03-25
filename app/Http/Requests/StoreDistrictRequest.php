<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistrictRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'state_id'   => 'required|exists:states,id',
            'name'       => 'required|string|max:255',
            'local_name' => 'sometimes|string|max:255',
            'code'       => 'required|string|max:10|unique:districts,code',
            'is_active'  => 'sometimes|boolean',
        ];
    }
}
