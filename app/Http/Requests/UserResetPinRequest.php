<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserResetPinRequest extends FormRequest
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
            'phone'       => 'required|string|exists:users,phone|regex:/^[0-9]{10,15}$/',
            'new_pin'     => 'required|string|min:4|max:8|regex:/^\d+$/',
            'confirm_pin' => 'required|string|same:new_pin',
            'otp'         => 'nullable|string|size:6',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'phone.required'       => 'Phone number is required.',
            'phone.exists'         => 'Phone number not found.',
            'phone.regex'          => 'Phone number must be 10-15 digits.',
            'new_pin.required'     => 'New PIN is required.',
            'new_pin.min'          => 'New PIN must be at least 4 digits.',
            'new_pin.max'          => 'New PIN must not exceed 8 digits.',
            'new_pin.regex'        => 'New PIN must contain only digits.',
            'confirm_pin.required' => 'Confirm PIN is required.',
            'confirm_pin.same'     => 'PIN and confirm PIN must match.',
            'otp.size'             => 'OTP must be 6 digits.',
        ];
    }
}
