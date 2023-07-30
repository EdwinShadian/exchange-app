<?php

declare(strict_types=1);

namespace Api\V1\User;

use App\Http\Resources\Api\V1\UserResource;
use App\Models\City;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    private const PASSWORD = 'oldPassword';

    private User $user;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->user, $this->token] = $this->auth([
            'password' => Hash::make(self::PASSWORD),
        ]);
    }

    public function testResetPassword(): void
    {
        $newPassword = 'newPassword';

        $response = $this->post('/api/v1/user/reset-password', [
            'old_password' => self::PASSWORD,
            'new_password' => $newPassword,
        ], [
            'Authorization' => $this->token,
        ]);
        $response->assertOk();

        $user = User::find($this->user->id);

        $responseData = $response->json()['data'];
        $this->assertIsString($responseData['auth_token']);
        $this->assertNotEquals(Hash::make(self::PASSWORD), $user->password);
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function testResetPasswordWithOldPassword(): void
    {
        $olderPassword = 'olderPassword';
        $newPassword = 'olderPassword';

        DB::table('password_resets')->insert([
            'user_id' => $this->user->id,
            'last_password' => Hash::make($olderPassword),
            'created_at' => Carbon::now()->subDays(28),
        ]);

        $response = $this->post('/api/v1/user/reset-password', [
            'old_password' => self::PASSWORD,
            'new_password' => $newPassword,
        ], [
            'Authorization' => $this->token,
        ]);

        $response->assertBadRequest();
        $responseData = $response->json();

        $this->assertSame('The new password has been used within 30 days', $responseData['msg']);
        $this->assertSame(400, $responseData['status']);
    }

    public function testUpdate()
    {
        $name = fake()->name();
        $phone = fake()->phoneNumber();
        $city = City::factory()->createOne();

        $response = $this->put('/api/v1/user', [
            'name' => $name,
            'phone' => $phone,
            'city_id' => $city->id,
        ], [
            'Authorization' => $this->token,
        ]);
        $response->assertOk();

        $user = User::find($this->user->id);

        $responseData = $response->json()['data'];
        $this->assertSame($responseData['user'], (new UserResource($user))->toArray(null));
    }
}
