<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'pin'   => 'required|string|min:4|max:8|regex:/^\d+$/',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.regex'    => 'Phone number must be 10-15 digits.',
            'pin.required'   => 'PIN is required.',
            'pin.min'        => 'PIN must be at least 4 digits.',
            'pin.max'        => 'PIN must not exceed 8 digits.',
            'pin.regex'      => 'PIN must contain only digits.',
        ];
    }
}
