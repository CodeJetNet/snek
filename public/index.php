<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->get(
    '/',
    function (ServerRequestInterface $request, ResponseInterface $response) {
        return $response->withStatus(200)->withJson(
            [
                'apiversion' => '1',
                'author' => 'phptek16battlesnake',
                'color' => '#39FF33',
                'header' => 'default',
                'tail' => 'default',
                'version' => '0.0.1-beta',
            ]
        );
    }
);

$app->post(
    '/start',
    function (ServerRequestInterface $request, ResponseInterface $response) {
        return $response->withStatus(200);
    }
);

function can_move_here($x, $y, $board)
{
    if ($x < 0 || $x >= count($board)) {
        return false;
    }

    if ($y < 0 || $y >= count($board[0])) {
        return false;
    }

    if (!empty($board[$x][$y])) {
        return false;
    }

    return true;
}

function possible_moves($x, $y, $board): array
{
    $possible_moves = [];
    if (can_move_here($x + 1, $y, $board)) {
        error_log($x + 1 . ' ' . $y . ' is a possible move');
        $possible_moves[] = ['x' => $x + 1, 'y' => $y];
    }
    if (can_move_here($x - 1, $y, $board)) {
        $possible_moves[] = ['x' => $x - 1, 'y' => $y];
        error_log($x - 1 . ' ' . $y . ' is a possible move');
    }
    if (can_move_here($x, $y + 1, $board)) {
        $possible_moves[] = ['x' => $x, 'y' => $y + 1];
        error_log($x . ' ' . $y + 1 . ' is a possible move');
    }
    if (can_move_here($x, $y - 1, $board)) {
        $possible_moves[] = ['x' => $x, 'y' => $y - 1];
        error_log($x . ' ' . $y - 1 . ' is a possible move');
    }
    return $possible_moves;
}

function translate_coordinates_to_move($x, $y, $new_x, $new_y): string
{
    if ($new_x > $x) {
        return 'right';
    }
    if ($new_x < $x) {
        return 'left';
    }
    if ($new_y > $y) {
        return 'up';
    }
    if ($new_y < $y) {
        return 'down';
    }

    return '';
}

$app->post('/move', function (ServerRequestInterface $request, ResponseInterface $response) {
    $requestData = $request->getParsedBody();

    $board = [];

    for ($x = 0; $x < $requestData['board']['width']; $x++) {
        for ($y = 0; $y < $requestData['board']['height']; $y++) {
            $board[$x][$y] = '';
        }
    }

    foreach ($requestData['board']['snakes'] as $snake) {
        foreach ($snake['body'] as $location) {
            $board[$location['x']][$location['y']] = 's';
        }
    }

    foreach ($requestData['you']['body'] as $location) {
        $board[$location['x']][$location['y']] = 's';
    }

    $board[$requestData['you']['head']['x']][$requestData['you']['head']['y']] = 'h';

    $where_am_i_going = possible_moves($requestData['you']['head']['x'], $requestData['you']['head']['y'], $board);

    if (empty($where_am_i_going)) {
        // Nowhere to go.  *cries*
        return $response->withStatus(200);
    }

    $where_am_i_going_for_real_this_time = translate_coordinates_to_move(
        $requestData['you']['head']['x'],
        $requestData['you']['head']['y'],
        $where_am_i_going[0]['x'],
        $where_am_i_going[0]['y']
    );

    return $response->withStatus(200)->withJson(['move' => $where_am_i_going_for_real_this_time]);
});

$app->post(
    '/end',
    function (ServerRequestInterface $request, ResponseInterface $response) {
        return $response->withStatus(200);
    }
);

$app->run();
