<?php

declare(strict_types=1);

namespace Eva\HttpKernel;

use Eva\DependencyInjection\ContainerInterface;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\HttpRequestParser;

class Router implements RouterInterface
{
    protected array $routes = [];

    public function __construct(protected ContainerInterface $container) {}

    public function execute(RequestInterface $request): ResponseInterface
    {
        [$class, $method, $args] = $this->findRoute($request);

        return $this->container->get($class)->{$method}($request, ...$args);
    }

    public function findRoute(RequestInterface $request): array
    {
        $uri = HttpRequestParser::parseUri($request);

        foreach ($this->routes as $route => $routeConfig) {
            $routePattern = '/^' . addcslashes(preg_replace(
                '/\/:(\w+)/',
                '/(\w+)',
                $routeConfig['uri']
            ), '/') . '$/';
            $foundCount = preg_match($routePattern, $uri->getPath(), $matches);
            unset($matches[0]);

            if ($request->getMethod()->value === $routeConfig['method'] && $foundCount === 1) {
                $route = explode('::', $routeConfig['handler']);
                $route[] = $matches;

                return $route;
            }
        }

        throw new \RuntimeException('Route not found', 404);
    }

    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }
}
