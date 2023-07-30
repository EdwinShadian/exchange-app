<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = json_decode(file_get_contents(__DIR__.'/data/rs.json'), true);
        foreach ($cities as $city) {
            City::factory()->create([
                'name' => $city['city'],
            ]);
        }
    }
}
