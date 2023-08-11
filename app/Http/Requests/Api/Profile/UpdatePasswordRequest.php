<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $password_validation_in_production = null;
        if(isProduction()) {
            $password_validation_in_production = '|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/';
        }

        return [
            'old_password' => 'required',
            'password'              => 'required|confirmed|string|min:8' . $password_validation_in_production,
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => __('Old Password is required'),
            'password.required'     => __('Password is required'),
            'password.regex' => __('Password validation'),
            'password.same'         => __('Password Confirmation should match the Password'),
        ];
    }
}
