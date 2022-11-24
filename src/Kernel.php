<?php

declare(strict_types=1);

namespace Eva\HttpKernel;

use Eva\DependencyInjection\ContainerInterface;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\HttpKernel\Events\ExceptionEvent;
use Eva\HttpKernel\Events\RequestEvent;
use Eva\HttpKernel\Events\ResponseEvent;
use Eva\HttpKernel\Events\TerminateEvent;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class Kernel implements KernelInterface
{
    public function __construct(protected ContainerInterface $container) {}

    public function handle(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->handleRaw($request);
        } catch (Throwable $e) {
            return $this->handleThrowable($e, $request);
        }
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    protected function handleRaw(RequestInterface $request): ResponseInterface
    {
        $this->container->get('eventDispatcher')->dispatch(new RequestEvent($request));
        /** @var RouterInterface $router */
        $router = $this->container->get('router');
        [$handlerClass, $method, $args] = $router->findRoute($request);
        $response = $this->container->get($handlerClass)->{$method}($request, ...$args);
        $this->container->get('eventDispatcher')->dispatch(new ResponseEvent($request, $response));

        return $response;
    }

    protected function handleThrowable(Throwable $e, RequestInterface $request): ResponseInterface
    {
        $event = new ExceptionEvent($request, $e);
        $event = $this->container->get('eventDispatcher')->dispatch($event);

        return $event->getResponse();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function terminate(RequestInterface $request, ResponseInterface $response): void
    {
        $event = new TerminateEvent($request, $response);
        $this->container->get('eventDispatcher')->dispatch($event);
    }
}
