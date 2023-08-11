<?php

namespace App\Http\Controllers\Api\Profile;

use App\Enums\Api\ResponseMethodEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdatePasswordRequest;
use App\Http\Requests\Api\Profile\UserProfileRequest;
use App\Http\Resources\Api\User\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function userProfile()
    {
        $user = auth('api')->user();
        if ($user) {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM_SINGLE, resource: UserResource::class, data_passed: $user);
        } else {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('user not found'), custom_status_msg: 'fail', custom_status: 404);
        }

    }

    public function userProfileUpdate(UserProfileRequest $request)
    {
        $user = auth('api')->user();
        $user->update(array_except($request->validated(), ['image']));

        // upload image
        if($request->image) {
            $user->update(['image' => Storage::disk('public')->putFile('images/users', $request->image)]);
        }

        return generalApiResponse(method: ResponseMethodEnum::CUSTOM_SINGLE, resource: UserResource::class, data_passed: $user, custom_message: __('Updated Successfully'));
    }

    public function UserProfileDelete()
    {
        $user = auth('api')->user();

        if ($user->delete()) {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Deleted Successfully'), custom_status_msg: 'success', custom_status: 200);
        } else {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('user not found'), custom_status_msg: 'fail', custom_status: 404);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth('api')->user();
        $old_password = $request->old_password;
        if (!Hash::check($old_password, $user->password)) {
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Old password does not match'), custom_status_msg: 'fail', custom_status: 401);
        }
        $user->update(['password' => $request->password]);

        return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Password updated successfully'), custom_status_msg: 'success', custom_status: 200);

    }
}
