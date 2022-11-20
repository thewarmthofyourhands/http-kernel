<?php

declare(strict_types=1);

namespace Eva\HttpKernel\Events;

use Eva\Http\Message\RequestInterface;
use Eva\Http\PSR7\Interfaces\ResponseInterface;

class TerminateEvent
{
    public function __construct(
        protected RequestInterface $request,
        protected ResponseInterface $response,
    ) {}

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
}
