<?php

use Blog\Test\Integration\ContainerProvider;
use Dotenv\Dotenv;
use DI\ContainerBuilder;
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require __DIR__ . '/../../vendor/autoload.php';

$builder = new ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/../../config/di.php');

$container = $builder->build();

ContainerProvider::setContainer($container);