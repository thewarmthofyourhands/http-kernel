<?php

declare(strict_types=1);

namespace Eva\HttpKernel;

use Eva\Http\Message\RequestInterface;

interface RouterInterface
{
    public function findRoute(RequestInterface $request): array;
    public function setRoutes(array $routes): void;
}
