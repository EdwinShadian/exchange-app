<?php

declare(strict_types=1);

namespace App\Exceptions\Api\V1;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ForbiddenException extends HttpException
{
    public function __construct($message = null, \Throwable $previous = null, array $headers = [], $code = 0)
    {
        parent::__construct(403, $message, $previous, $headers, $code);
    }
}
