<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Blog\Route\AboutPage;
use Blog\Route\HomePage;
use Blog\Route\BlogPage;
use Blog\Route\PostPage;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions('../config/di.php');

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = $builder->build();

AppFactory::setContainer($container);

// Create app
$app = AppFactory::create();

$app->get('/', HomePage::class . ':execute');
$app->get('/about', AboutPage::class);
$app->get('/blog[/{page}]', BlogPage::class);
$app->get('/{url_key}', PostPage::class);

$app->run();
