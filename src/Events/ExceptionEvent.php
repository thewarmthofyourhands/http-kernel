<?php

declare(strict_types=1);

namespace Eva\HttpKernel\Events;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;

class ExceptionEvent
{
    public function __construct(
        protected RequestInterface $request,
        protected \Throwable $throwable,
        protected null|ResponseInterface $response = null,
    ) {}

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getThrowable(): \Throwable
    {
        return $this->throwable;
    }

    public function setThrowable(\Throwable $throwable): void
    {
        $this->throwable = $throwable;
    }

    public function getResponse(): null|ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(null|ResponseInterface $response): void
    {
        $this->response = $response;
    }
}
