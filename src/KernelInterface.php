<?php

declare(strict_types=1);

namespace Eva\HttpKernel;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;

interface KernelInterface
{
    public function handle(RequestInterface $request): ResponseInterface;
}
