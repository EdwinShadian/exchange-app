<?php

declare(strict_types=1);

namespace Api\V1\City;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    private string $token;

    private Collection $cities;

    protected function setUp(): void
    {
        parent::setUp();

        [, $this->token] = $this->auth();
        $this->cities = City::factory(15)->create();
    }

    public function testIndex(): void
    {
        $response = $this->get('/api/v1/city', [
            'Authorization' => $this->token,
        ]);
        $response->assertOk();

        $responseData = $response->json()['data'];
        $this->assertCount(10, $responseData['cities']);
        $this->assertEquals(
            $this->cities->slice(0, 10)
                ->toArray(),
            $responseData['cities']
        );
    }

    public function testIndexSearchAndLimit(): void
    {
        $city = $this->cities->first();
        $searchString = str_split($city->name, 3)[0];

        $response = $this->get("/api/v1/city?limit=3&q=$searchString", [
            'Authorization' => $this->token,
        ]);
        $response->assertOk();

        $responseData = $response->json()['data'];

        $this->assertLessThanOrEqual(3, count($responseData['cities']));
        $this->assertEquals($city->toArray(), $responseData['cities'][0]);
    }
}
