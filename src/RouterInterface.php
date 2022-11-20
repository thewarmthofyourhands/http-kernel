<?php

declare(strict_types=1);

namespace Eva\HttpKernel;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;

interface RouterInterface
{
    public function findRoute(RequestInterface $request): array;
    public function execute(RequestInterface $request): ResponseInterface;
    public function setRoutes(array $routes): void;
}
