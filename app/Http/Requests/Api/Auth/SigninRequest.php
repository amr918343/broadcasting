<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class SigninRequest extends ApiMasterRequest
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
        $username = null;
        $regex = null;
        switch ($this->identifier) {
            case filter_var($this->identifier, FILTER_VALIDATE_EMAIL):
                $username = 'email';
                break;
            default:
                $username = 'phone';
                break;
        }

        if($username == 'email') {
            $regex = 'regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|exists:users,email';
        } else {
            $regex = 'digits:12|regex:/^966[1-9][0-9]{8}$/|exists:users,phone_number';
        }

        return [
            'identifier' =>  'required|' . $regex,
            'password' =>  ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'identifier.required' => __('Email or phone is required'),
            'identifier.regex' => __('Credintials  is not valid'),
            'identifier.exists' => __('Credintials  is not valid'),
            'password.required' =>__('Password is required'),
        ];
    }
}
