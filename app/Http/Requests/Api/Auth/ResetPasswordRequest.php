<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends ApiMasterRequest
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

        $code = 'required|' . Rule::exists('users', 'reset_code')->where(function ($query) {
            return $query->where('email', $this->email);
        });
        return [
            'email' => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|exists:users,email',
            'code' => $code,
            'new_password' => 'required|confirmed|string|min:6' . $password_validation_in_production,
        ];
    }

    public function messages(): array{
        return [
            'email.required' => __('Email is required'),
            'email.regex' => __('The email is not valid'),
            'email.exists' => __('Email not found'),
            'code.required' => __('Code is required'),
            'code.exists' => __('Code not found'),
        ];
    }
}
