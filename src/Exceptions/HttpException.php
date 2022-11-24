<?php

declare(strict_types=1);

namespace Eva\HttpKernel\Exceptions;

use Exception;
use Throwable;

class HttpException extends Exception
{
    public function __construct(string $message = "", protected int $responseStatusCode = 500, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getResponseStatusCode(): int
    {
        return $this->responseStatusCode;
    }
}
