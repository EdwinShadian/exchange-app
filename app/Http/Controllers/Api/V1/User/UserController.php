<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Exceptions\Api\V1\BadRequestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\ResetPasswordRequest;
use App\Http\Requests\Api\V1\User\UserUpdateRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Responses\Api\V1\ApiResponse;
use App\Models\City;
use App\Services\UserService;
use Auth;
use Hash;

class UserController extends Controller
{
    public function resetPassword(ResetPasswordRequest $request, UserService $userService)
    {
        $data = $request->validated();

        $user = Auth::user();

        if (!$user->canResetPassword($data['new_password'])) {
            throw new BadRequestException('The new password has been used within 30 days');
        }

        $user->addResetPassword();

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        $userService->retrieveAllTokens($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::json([
            'auth_token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function update(UserUpdateRequest $request)
    {
        $data = $request->validated();

        if (!City::find($data['city_id'])->exists()) {
            throw new BadRequestException("City with id={$data['city_id']} does not exist");
        }

        $user = Auth::user();

        $user->update($data);

        return ApiResponse::json([
            'user' => new UserResource($user),
        ]);
    }
}
