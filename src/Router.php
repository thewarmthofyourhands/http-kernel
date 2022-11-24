<?php

declare(strict_types=1);

namespace Eva\HttpKernel;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Parser\HttpRequestParser;
use Eva\HttpKernel\Exceptions\NotFoundRouteException;

class Router implements RouterInterface
{
    protected array $routes = [];

    /**
     * @throws NotFoundRouteException
     */
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

            if ($foundCount === 1 && $request->getMethod()->value === $routeConfig['method']) {
                $route = explode('::', $routeConfig['handler']);
                $route[] = $matches;

                return $route;
            }
        }

        throw new NotFoundRouteException('Route is not found');
    }

    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }
}
