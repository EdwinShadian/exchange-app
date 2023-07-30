<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\ResetPasswordRequest;
use App\Http\Requests\Api\V1\User\UserUpdateRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Responses\Api\V1\ApiResponse;
use App\Services\UserService;
use Auth;

class UserController extends Controller
{
    public function resetPassword(ResetPasswordRequest $request, UserService $userService)
    {
        $data = $request->validated();
        $user = Auth::user();

        $userService->resetPassword($user, $data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::json([
            'auth_token' => $token,
        ]);
    }

    public function update(UserUpdateRequest $request, UserService $userService)
    {
        $data = $request->validated();
        $user = Auth::user();

        $user = $userService->update($user, $data);

        return ApiResponse::json([
            'user' => new UserResource($user),
        ]);
    }
}
