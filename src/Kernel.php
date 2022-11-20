<?php

declare(strict_types=1);

namespace Eva\HttpKernel;

use Eva\DependencyInjection\ContainerInterface;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\HttpKernel\Events\ExceptionEvent;
use Eva\HttpKernel\Events\RequestEvent;
use Eva\HttpKernel\Events\ResponseEvent;

class Kernel
{
    public function __construct(protected ContainerInterface $container) {}

    public function handle(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->handleRaw($request);
        } catch (\Throwable $e) {
            return $this->handleThrowable($e, $request);
        }
    }

    protected function handleRaw(RequestInterface $request): ResponseInterface
    {
        $this->container->get('eventDispatcher')->dispatch(new RequestEvent($request));
        [$class, $method, $args] = $this->container->get('router')->findRoute($request);
        $response = $this->container->get($class)->{$method}($request, ...$args);
        $this->container->get('eventDispatcher')->dispatch(new ResponseEvent($request, $response));

        return $response;
    }

    protected function handleThrowable(\Throwable $e, RequestInterface $request): ResponseInterface
    {
        $event = new ExceptionEvent($request, $e);
        $event = $this->container->get('eventDispatcher')->dispatch($event);

        return $event->getResponse();
    }
}
