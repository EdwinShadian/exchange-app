<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Api\V1\UnauthorizedException;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    public function authByToken(array $data): User
    {
        $authToken = $data['auth_token'];

        $token = PersonalAccessToken::findToken($authToken);
        $user = $token?->tokenable;

        if (null === $token || null === $user) {
            throw new UnauthorizedException('Invalid token');
        }

        $token->delete();

        return $user;
    }
}
