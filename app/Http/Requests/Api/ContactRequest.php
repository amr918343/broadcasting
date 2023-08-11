<?php

namespace App\Http\Requests\Api;

class ContactRequest extends ApiMasterRequest
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
        return [
            'user_type' => ['required', 'in:client,real_estate_developer'],
            'name' => ['required', 'string', 'between:3,255'],
            'email' => ['required', 'regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/'],
            'phone' => 'digits:12|regex:/^966[1-9][0-9]{8}$/',
            'message' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'email'     => __('Email'),
            'name'  => __('Name'),
            'message'  => __('Message'),
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('Email is required'),
            'name.required' => __('Name is required'),
            'message.required' => __('Message is required'),
            'user_type.required' => __('User type is required'),
            'user_type.in' => __('User type must be client or real estate developer'),
        ];
    }
}
