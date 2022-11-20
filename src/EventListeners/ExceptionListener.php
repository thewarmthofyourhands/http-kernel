<?php

declare(strict_types=1);

namespace Eva\HttpKernel\EventListeners;

use Eva\Env\Env;
use Eva\Http\Message\Response;
use Eva\HttpKernel\Events\ExceptionEvent;

class ExceptionListener
{
    public function __construct(protected Env $env) {}

    public function __invoke(ExceptionEvent $exceptionEvent): ExceptionEvent
    {
        $message = $exceptionEvent->getThrowable()->getMessage();

        if ($this->env->get('APP_DEV') === 'dev') {
            $message =  'error: ' . $exceptionEvent->getThrowable()->getTraceAsString() . $message;
        }

        $response = new Response($exceptionEvent->getThrowable()->getCode(), [], $message);
        $exceptionEvent->setResponse($response);

        return $exceptionEvent;
    }
}
