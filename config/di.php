<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function DI\autowire;
use function DI\get;
use Blog\Database;
use Blog\Twig\AssetExtension;

return [
    'server.params' => $_SERVER,
    FilesystemLoader::class => autowire()
        ->constructorParameter('paths', '../templates'),

    Environment::class => autowire()
        ->constructorParameter('loader', get(FilesystemLoader::class))
        ->method('addExtension', get(AssetExtension::class)),

    Database::class => autowire()
        ->constructorParameter('connection', get(PDO::class)),

    PDO::class => autowire()
        ->constructor(
            $_ENV['MYSQL_DSN'],
            $_ENV['MYSQL_USER'],
            $_ENV['MYSQL_ROOT_PASSWORD'],
            []
        )
        ->method('setAttribute', PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
        ->method('setAttribute', PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC),

    AssetExtension::class => autowire()
        ->constructorParameter('serverParams', get('server.params')),
];
