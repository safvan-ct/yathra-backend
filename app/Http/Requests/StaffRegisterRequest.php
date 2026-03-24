<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRegisterRequest extends FormRequest
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:staff,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|string|in:admin,manager,staff',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'Name is required.',
            'name.string'       => 'Name must be a string.',
            'name.max'          => 'Name must not exceed 255 characters.',
            'email.required'    => 'Email is required.',
            'email.email'       => 'Email must be a valid email address.',
            'email.unique'      => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 8 characters.',
            'role.required'     => 'Role is required.',
            'role.in'           => 'Role must be admin, manager, or staff.',
        ];
    }
}
