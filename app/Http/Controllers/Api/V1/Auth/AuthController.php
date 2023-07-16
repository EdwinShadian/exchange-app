<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\Api\V1\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\AuthByTokenRequest;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Responses\Api\V1\ApiResponse;
use App\Models\User;
use App\Services\UserService;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            throw new UnauthorizedException('Invalid credentials');
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::json([
            'auth_token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::json([
            'auth_token' => $token,
            'user' => new UserResource($user),
        ], 201);
    }

    public function authByToken(AuthByTokenRequest $request)
    {
        $data = $request->validated();
        $authToken = $data['auth_token'];

        $token = PersonalAccessToken::findToken($authToken);
        $user = $token?->tokenable;

        if (null === $token || null === $user) {
            throw new UnauthorizedException('Invalid token');
        }

        $token->delete();

        $newToken = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::json([
            'auth_token' => $newToken,
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->header('Authorization');

        PersonalAccessToken::findToken($token)->delete();

        return ApiResponse::json([], 204);
    }

    public function logoutAll(UserService $userService)
    {
        $user = Auth::user();

        $userService->retrieveAllTokens($user);

        return ApiResponse::json([], 204);
    }
}
