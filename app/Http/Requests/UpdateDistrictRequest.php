<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDistrictRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'state_id'   => 'sometimes|exists:states,id',
            'name'       => 'sometimes|string|max:255',
            'local_name' => 'sometimes|string|max:255',
            'code'       => ['sometimes', 'string', 'max:10', Rule::unique('districts', 'code')->ignore($this->route('district'))],
            'is_active'  => 'sometimes|boolean',
        ];
    }
}
