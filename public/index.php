<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return $response->withStatus(200)->withJson([
        'apiversion' => '1',
        'author' => 'phptek16battlesnake',
        'color' => '#39FF33',
        'header' => 'default',
        'tail' => 'default',
        'version' => '0.0.1-beta',
    ]);
});

$app->post('/start', function (ServerRequestInterface $request, ResponseInterface $response) {
    return $response->withStatus(200)->withJson([]);
});

$app->post('/move', function (ServerRequestInterface $request, ResponseInterface $response) {
    return $response->withStatus(200)->withJson([]);


});

$app->post('/end', function (ServerRequestInterface $request, ResponseInterface $response) {
    return $response->withStatus(200);
});

$app->run();
