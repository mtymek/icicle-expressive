#!/usr/bin/env php
<?php
chdir(dirname(__DIR__));
error_reporting(E_ALL);
require 'vendor/autoload.php';

use Icicle\Http\Message\RequestInterface as IcicleRequest;
use Icicle\Http\Message\Response as IcicleResponse;
use Icicle\Http\Server\Server;
use Icicle\Loop;
use Icicle\Psr7Bridge\MessageFactory;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Zend\Diactoros\Response as PsrResponse;

$messageFactory = new MessageFactory();

$middleware = function (PsrRequestInterface $request, PsrResponseInterface $response) {
    $response->getBody()->write("Hello!");
    $response = $response->withHeader('content-type', 'text/html');
    return $response;
};

$server = new Server(function (IcicleRequest $request) use ($messageFactory, $middleware) {
    $psrRequest = $messageFactory->createServerRequest($request);
    $psrResponse = new PsrResponse();
    $psrResponse = $middleware($psrRequest, $psrResponse);

    $response = new IcicleResponse(200, $psrResponse->getHeaders());
    yield $response->getBody()->end($psrResponse->getBody());
    yield $response;
});

$port = 8000;
$server->listen($port);
echo "Server running at http://127.0.0.1:$port\n";

Loop\run();
