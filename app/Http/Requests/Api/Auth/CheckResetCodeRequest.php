<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Validation\Rule;

class CheckResetCodeRequest extends ApiMasterRequest
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
        $code = 'required|' . Rule::exists('users', 'reset_code')->where(function ($query) {
            return $query->where('email', $this->email);
        });
        return [
            'email' => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|exists:users,email',
            'code' => $code,
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
