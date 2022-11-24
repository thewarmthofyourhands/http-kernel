<?php

declare(strict_types=1);

namespace Eva\HttpKernel\Exceptions;

use Throwable;

class NotFoundRouteException extends HttpException
{
    public function __construct(string $message, int $responseStatusCode = 404, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $responseStatusCode, $code, $previous);
    }
}
