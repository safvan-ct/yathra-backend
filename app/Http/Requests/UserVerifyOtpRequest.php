<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserVerifyOtpRequest extends FormRequest
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
            'phone' => 'required|string|exists:users,phone|regex:/^[0-9]{10,15}$/',
            'otp'   => 'nullable|string|size:6',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.exists'   => 'Phone number not found.',
            'phone.regex'    => 'Phone number must be 10-15 digits.',
            'otp.size'       => 'OTP must be 6 digits.',
        ];
    }
}
