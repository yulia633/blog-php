<?php

declare(strict_types=1);

use Blog\LatestPosts;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Blog\PostMapper;
use Blog\Slim\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);

$config = include '../config/database.php';
$dsn = "mysql:host={$config['host']};dbname={$config['dsn']};char={$config['char']}";
$username = $config['username'];
$password = $config['password'];

try {
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    echo "Database error:  {$exception->getMessage()}";
    die();
}

// Create app
$app = AppFactory::create();

$app->add(new TwigMiddleware($twig));

$app->get('/', function (Request $request, Response $response) use ($twig, $connection) {
    $latestPosts = new LatestPosts($connection);
    $posts = $latestPosts->get(3);

    $body = $twig->render('index.html.twig', [
        'posts' => $posts
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function (Request $request, Response $response) use ($twig) {
    $body = $twig->render('about.html.twig', [
        'name' => 'Test'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/blog[/{page}]', function (Request $request, Response $response, $args) use ($twig, $connection) {
    $postMapper = new PostMapper($connection);

    $page = isset($args['page']) ? (int) $args['page'] : 1;
    $limit = 2;
    $posts = $postMapper->getList($page, $limit, 'DESC');

    $body = $twig->render('blog.html.twig', [
        'posts' => $posts
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/{url_key}', function (Request $request, Response $response, $args) use ($twig, $connection) {
    $postMapper = new PostMapper($connection);
    $post = $postMapper->getByUrlKey((string) $args['url_key']);

    if (empty($post)) {
        $body = $twig->render('not-found.html.twig');
    } else {
        $body = $twig->render('post.html.twig', [
            'post' => $post
        ]);
    }

    $response->getBody()->write($body);
    return $response;
});

$app->run();
