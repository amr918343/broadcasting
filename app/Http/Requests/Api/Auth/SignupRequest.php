<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
class SignupRequest extends ApiMasterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $password_validation_in_production = null;
        if(isProduction()) {
            $password_validation_in_production = '|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/';
        }

        return [
            // Todo: image upon request
            'image'                 => 'image|mimes:jpg,png,jpeg,webp',
            'full_name'             => 'required|max:255|string',
            'email'                 => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|unique:users,email',
            'phone_number'          => 'required|digits:12|regex:/^966[1-9][0-9]{8}$/|unique:users,phone_number,' . auth('api')->id(),
            'identity_number'       => 'required|string|unique:users,identity_number',
            'hijri_date'            => 'required|date|before:' . now()->toHijri()->subYears(18)->format('Y-m') .  '|after_or_equal:' . now()->toHijri()->subYears(120)->format('Y-m'),
            'password'              => 'required|confirmed|string|min:6' . $password_validation_in_production,
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => __('Full Name is required'),
            'email.required' => __('Email is required'),
            'phone_number.numeric' => __('Phone is required'),
            'phone_number.unique' => __('This phone already exists'),
            'password.required' => __('Password is required'),
            'password.same' => __('Password Confirmation should match the Password'),
            'password.regex' => __('Password validation'),
            'email.unique'=>__('This email already exists')
        ];
    }
}
