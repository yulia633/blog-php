<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/../vendor/autoload.php';

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);

// Create app
$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) use ($twig) {
    $body = $twig->render('index.html.twig');
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function (Request $request, Response $response, $args) use ($twig) {
    $body = $twig->render('about.html.twig', [
        'name' => 'Test'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->run();
