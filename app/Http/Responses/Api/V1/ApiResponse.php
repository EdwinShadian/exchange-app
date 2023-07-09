<?php

namespace App\Http\Responses\Api\V1;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function json($data, int $code = 200): JsonResponse
    {
        return new JsonResponse([
            'data' => $data,
            'status' => $code,
        ], $code);
    }
}
