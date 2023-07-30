<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Api\V1\BadRequestException;
use App\Models\City;
use App\Models\User;
use Hash;

class UserService
{
    public function retrieveAllTokens(User $user): void
    {
        $user->tokens()->each(function ($token) {
            $token->delete();
        });
    }

    public function resetPassword(User $user, array $data): void
    {
        if (! Hash::check($data['old_password'], $user->password)) {
            throw new BadRequestException('Current password is incorrect');
        }

        if (! $user->canResetPassword($data['new_password'])) {
            throw new BadRequestException('The new password has been used within 30 days');
        }

        $user->addResetPassword();

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        $this->retrieveAllTokens($user);
    }

    public function update(User $user, array $data): User
    {
        if (! City::find($data['city_id'])->exists()) {
            throw new BadRequestException("City with id={$data['city_id']} does not exist");
        }

        $user->update($data);

        return $user;
    }

    public function create(array $data): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }
}
