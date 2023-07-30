<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\City;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\City\CitySearchRequest;
use App\Http\Responses\Api\V1\ApiResponse;
use App\Models\City;

class CityController extends Controller
{
    public function index(CitySearchRequest $request)
    {
        $data = $request->validated();

        $cities = City::search('name', $data['q'] ?? null, $request->getLimit());

        return ApiResponse::json([
            'cities' => $cities,
        ]);
    }
}
