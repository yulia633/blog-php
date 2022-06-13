<?php

declare(strict_types=1);

use Blog\LatestPosts;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Blog\PostMapper;
use Blog\Slim\TwigMiddleware;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Blog\Database;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions('../config/di.php');

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = $builder->build();

AppFactory::setContainer($container);

// Create app
$app = AppFactory::create();

$twig = $container->get(Environment::class);
$app->add(new TwigMiddleware($twig));

$connection = $container->get(Database::class)->getConnection();

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

    $totalCount = $postMapper->getTotalCount();
    $body = $twig->render('blog.html.twig', [
        'posts' => $posts,
        'pagination' => [
            'current' => $page,
            'paging' => ceil($totalCount / $limit),
        ],
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
