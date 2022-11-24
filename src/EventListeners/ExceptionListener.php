<?php

declare(strict_types=1);

namespace Eva\HttpKernel\EventListeners;

use Eva\Env\Env;
use Eva\Http\Message\Response;
use Eva\HttpKernel\Events\ExceptionEvent;
use Eva\HttpKernel\Exceptions\HttpException;

class ExceptionListener
{
    public function __construct(protected Env $env) {}

    public function __invoke(ExceptionEvent $exceptionEvent): ExceptionEvent
    {
        $throwable = $exceptionEvent->getThrowable();

        $message = sprintf(
            'error code: %s, message: %s, file: %s, line: %s, trace: %s',
            $throwable->getCode(),
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            $throwable->getTraceAsString(),
        );

        if ($throwable instanceof HttpException) {
            $response = new Response($throwable->getResponseStatusCode(), [], $message);
        } else {
            $response = new Response(500, [], $message);
        }

        $exceptionEvent->setResponse($response);

        return $exceptionEvent;
    }
}
