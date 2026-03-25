<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'sometimes|string|max:255',
            'local_name' => 'sometimes|string|max:255',
            'code'       => ['sometimes', 'string', 'max:10', Rule::unique('states', 'code')->ignore($this->route('state'))],
            'is_active'  => 'sometimes|boolean',
        ];
    }
}
