#!/usr/bin/env php
<?php
chdir(dirname(__DIR__));
error_reporting(E_ALL);
require 'vendor/autoload.php';

use Icicle\Http\Message\RequestInterface as IcicleRequest;
use Icicle\Http\Message\Response as IcicleResponse;
use Icicle\Http\Server\Server;
use Icicle\Loop;
use Zend\Diactoros\Response as PsrResponse;

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
/** @var \Zend\Expressive\Application $app */
$app = $container->get('Zend\Expressive\Application');
$messageFactory = new \Icicle\Psr7Bridge\MessageFactory();

$server = new Server(function (IcicleRequest $request) use ($app, $messageFactory) {
    $psrRequest = $messageFactory->createServerRequest($request);
    $psrResponse = new PsrResponse();
    $psrResponse = $app($psrRequest, $psrResponse);

    $response = new IcicleResponse(200, $psrResponse->getHeaders());
    yield $response->getBody()->end($psrResponse->getBody());
    yield $response;
});

$server->listen(9000);

echo "Server running at http://127.0.0.1:9000\n";

Loop\run();
