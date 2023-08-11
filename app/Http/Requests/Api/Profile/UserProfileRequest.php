<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Support\Facades\Auth;

class UserProfileRequest extends ApiMasterRequest
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

        $user = auth('api')->user();
            return [
                'full_name'              => 'string',
                'email'                 => 'regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|unique:users,email,' . auth()->user()->email . ',email',
                'phone_number'          => 'digits:12|regex:/^966[1-9][0-9]{8}$/|unique:users,phone_number,' . $user->phone_number . ',phone_number',
                'image'                  => 'image|mimes:png,jpg,jpeg,webp',
            ];
    }

    public function messages()
    {
        return [
            'full_name.required' => __('Full Name is required!'),
            'email.required' => __('email is required!'),
            'password.required' => __('password is required!'),
            'phone_number.required' => __('phone number is required!'),
            'phone_number.numeric' => __('Phone is number'),
            'phone_number.unique' => __('Phone already exists'),
            'phone_number.min' => __('This phone number must not be less than 11 number'),
            'phone_number.regex' => __('Phone number is not valid'),
            'phone_number.starts_with' => __('The phone must start with the number +966'),
            'email.email' => __('Email is not correct'),
            'email.unique'=>__('This email already exists')
        ];
    }
}
