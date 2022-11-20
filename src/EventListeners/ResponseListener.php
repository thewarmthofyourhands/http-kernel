<?php

declare(strict_types=1);

namespace Eva\HttpKernel\EventListeners;

use Eva\HttpKernel\Events\ResponseEvent;

class ResponseListener
{
    public function __invoke(ResponseEvent $exceptionEvent): void
    {
//        if ($exceptionEvent->getResponse() === null) {
//            $exceptionEvent->setResponse();
//        }
    }
}
