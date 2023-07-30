<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonExceptionHandler extends ExceptionHandler
{
    public function render($request, $e): \Illuminate\Http\Response|JsonResponse|Response
    {
        return $this->prepareJsonResponse($request, $e);
    }

    protected function getStatusCode($exception): int
    {
        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse|Response
    {
        return response()->json([
            'status' => 401,
            'msg' => 'Unauthenticated',
            'reason' => $exception->getMessage(),
        ], 401);
    }

    protected function prepareJsonResponse($request, $e): JsonResponse
    {
        $statusCode = $this->getStatusCode($e);
        $message = $e->getMessage();
        $reason = $e->getPrevious()?->getMessage() ?? '';

        return response()->json([
            'status' => $statusCode,
            'msg' => $message,
            'reason' => $reason,
        ], $this->getStatusCode($e));
    }
}
