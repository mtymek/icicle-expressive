<?php

namespace App\Action;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PingAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        return new JsonResponse(
            ['ack' => time(), 'memory_usage' => memory_get_usage(true), 'headers' => $request->getHeaders()],
            200,
            [],
            JsonResponse::DEFAULT_JSON_FLAGS | JSON_PRETTY_PRINT
        );
    }
}
