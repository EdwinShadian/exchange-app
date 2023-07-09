<?php

namespace App\Http\Middleware\Api\V1;

use App\Exceptions\Api\V1\ForbiddenException;
use App\Models\User;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $authToken = $request->header('Authorization');
        $token = PersonalAccessToken::findToken($authToken);

        if (null === $token) {
            throw new ForbiddenException('Auth token not found');
        }

        /** @var User $user */
        $user = $token->tokenable;

        Auth::setUser($user);

        return $next($request);
    }
}
