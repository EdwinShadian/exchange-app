<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function auth(array $criteria = []): array
    {
        $user = User::factory()->createOne($criteria);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [$user, $token];
    }
}
