<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Auth;

use App\Http\Resources\Api\V1\UserResource;
use App\Models\City;
use App\Models\User;
use Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private const PASSWORD = 'password';

    public function testLogin(): void
    {
        $city = City::factory()->createOne();
        $user = User::factory()->createOne([
            'password' => Hash::make(self::PASSWORD),
            'city_id' => $city->id,
        ]);

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => self::PASSWORD,
        ]);
        $response->assertOk();

        $responseData = $response->json()['data'];
        $this->assertIsString($responseData['auth_token']);
        $this->assertSame($responseData['user'], (new UserResource($user))->toArray(null));
    }

    public function testRegister(): void
    {
        $user = User::factory()->makeOne([
            'password' => Hash::make(self::PASSWORD),
        ]);

        $response = $this->post('/api/v1/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => self::PASSWORD,
        ]);
        $response->assertCreated();

        $registeredUser = User::where(['name' => $user->name])->first();

        $responseData = $response->json()['data'];

        $this->assertIsString($responseData['auth_token']);
        $this->assertSame($responseData['user'], (new UserResource($registeredUser))->toArray(null));
    }

    public function testAuthByToken(): void
    {
        $city = City::factory()->createOne();

        [$user, $token] = $this->auth([
            'password' => Hash::make(self::PASSWORD),
            'city_id' => $city->id,
        ]);

        $response = $this->post('/api/v1/auth-by-token', [
            'auth_token' => $token,
        ]);
        $response->assertOk();

        $responseData = $response->json()['data'];
        $this->assertIsString($responseData['auth_token']);
        $this->assertSame($responseData['user'], (new UserResource($user))->toArray(null));
    }

    public function testLogout(): void
    {
        [$user, $token] = $this->auth();

        $response = $this->post('/api/v1/logout', [], [
            'Authorization' => $token,
        ]);
        $response->assertNoContent();

        $this->assertCount(0, $user->tokens);
    }

    public function testLogoutAll(): void
    {
        [$user, $token] = $this->auth();

        $tokens = [
            $token,
            $user->createToken('auth_token')->plainTextToken,
            $user->createToken('auth_token')->plainTextToken,
        ];

        $response = $this->post('/api/v1/logout-all', [], [
            'Authorization' => $tokens[0],
        ]);

        $response->assertNoContent();

        $this->assertCount(0, $user->tokens);
    }
}
