<?php

namespace App\Http\Controllers\Api;

use App\Enums\Api\ResponseMethodEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\CheckResetCodeRequest;
use App\Http\Requests\Api\Auth\CheckVerificationCodeRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\SendResetPasswordCodeRequest;
use App\Http\Requests\Api\Auth\SigninRequest;
use App\Http\Requests\Api\Auth\SignupRequest;
use App\Http\Resources\Api\User\SimpleUserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function signin(SigninRequest $request)
    {
        if (Auth::attempt($this->getCredentials($request))) {
            $user = auth('api')->user();
            if($user->verification_code !== null) {
                return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('You should activate your email to signin'), custom_status: 401, custom_msg: __(), additional_data: ['has_verified_account' => $user->verification_code == null]);
            }
            if(auth('api')->user()->is_banned) {
                auth('api')->user()->tokens()->delete();
                return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('You are banned from the adminstration for the following reason: ') . auth('api')->user()->ban_reason, custom_status: 401 );
            }

            $token = $user->createToken('api_token')->plainTextToken;
            data_set($user, 'token', $token);
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM_SINGLE, resource: SimpleUserResource::class, data_passed: $user, custom_message: __('success login'),  additional_data: ['has_verified_account' => $user->verification_code == null]);
        }
        return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('unauthorized'), custom_status_msg: 'fail', custom_status: 401);
    }

    public function signup(SignupRequest $request)
    {
        $user = User::create(array_except($request->validated(), ['image']));

        // upload image
        if($request->image) {
            $user->update(['image' => Storage::disk('public')->putFile('images/users', $request->image)]);
        }

        $code = 1111;

        if (nova_get_setting('use_mail_service') == true) {
            $code = mt_rand(1111, 9999);
            // $user->notify();
        }

        $user->update(['verification_code' => $code]);

        // $user->notify(new ResetPasswordNotification($user));
        $token = $user->createToken('api_token')->plainTextToken;

        data_set($user, 'token', $token);

        return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('We have sent a verification code to your provided email'));
    }

    public function verifyAccount(CheckVerificationCodeRequest $request)
    {
        $user = User::where(['verification_code' => $request->code, 'email' => $request->email])->first();
        if (!$user) {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Email is not valid'), custom_status_msg: 'fail', custom_status: 422);
        }

        $user->update(['verification_code' => null]);

        $token = $user->createToken('api_token')->plainTextToken;
        data_set($user, 'token', $token);
        return generalApiResponse(method: ResponseMethodEnum::CUSTOM_SINGLE, resource: SimpleUserResource::class, data_passed: $user, custom_message: __('success login'));
    }

    public function logout(Request $request)
    {
        $user = auth('api')->user();
        $user->tokens()->delete();
        return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('success logout'));
    }

    ########################### Start steps for reseting password #########################
    /**
     * Step 1
     * Send reset code to email
     **/
    public function sendResetCode(SendResetPasswordCodeRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Email is not valid'), custom_status_msg: 'fail', custom_status: 422);
        }
        //  Check user if banned
        if($user->is_banned) {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('You are banned from the adminstration for the following reason: ') . auth('api')->user()->ban_reason, custom_status: 401 );
        }

        $code = 1111;
        try {
            if (nova_get_setting('use_mail_service') == true) {
                $code = mt_rand(1111, 9999);
                // Todo : otp_sent_at field to check after nova_get_setting('waiting_resending_otp') is less than now
                // $user->notify(new VerifyApiMail());
            }

            $user->update(['reset_code' => $code]);

            // $user->notify(new ResetPasswordNotification($user));

            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('We have sent a reset code to your provided email'));
        } catch (\Exception $e) {
            \Log::error($e);
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Some error occured please try again!'), custom_status_msg: 'fail', custom_status: 500);
        }
    }

    /**
     * Step 2
     * check if reset password correct as send to email
     **/
    public function checkResetPassword(CheckResetCodeRequest $request)
    {
        $user = User::where(['reset_code' => $request->code, 'email' => $request->email])->first();
        if (!$user) {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Email is not valid'), custom_status_msg: 'fail', custom_status: 422);
        }

        return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Code is Correct'));
    }

    /**
     * Step 3
     * reset password
     **/
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where(['reset_code' => $request->code, 'email' => $request->email])->first();
        if (!$user) {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Email is not valid'), custom_status_msg: 'fail', custom_status: 422);
        }
        $token = $user->createToken('api_token')->plainTextToken;
        $user->update(['email_verified_at' => now(), 'password' => $request->new_password, 'reset_code' => null]);
        // if ($request->device_token) {
        //     $user->devices()->firstOrCreate($request->only(['device_token', 'type']));
        // }
        data_set($user, 'token', $token);
        return generalApiResponse(method: ResponseMethodEnum::CUSTOM_SINGLE, resource: SimpleUserResource::class, data_passed: $user);

    }

    ########################### End steps for reseting password #########################
    private function getCredentials(Request $request)
    {
        $username = $request->identifier;

        $credentials = [];

        switch ($username) {
            case filter_var($username, FILTER_VALIDATE_EMAIL):
                $username = 'email';
                break;
            case is_numeric($username):
                $username = 'phone';
                break;
            default:
                $username = 'email';
                break;
        }
        if($username == 'email') {
            $credentials['email'] = $request->identifier;
        } else {
            $credentials['phone_number'] = $request->identifier;
        }

        $credentials['password'] = $request->password;

        return $credentials;
    }
}
