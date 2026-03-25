<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'local_name' => 'sometimes|string|max:255',
            'code'       => 'required|string|max:10|unique:states,code',
            'is_active'  => 'sometimes|boolean',
        ];
    }
}
