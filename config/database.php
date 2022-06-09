<?php

declare(strict_types=1);

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

return [
    'username' => $_ENV['MYSQL_USER'],
    'password' => $_ENV['MYSQL_ROOT_PASSWORD'],
    'host' => $_ENV['MYSQL_HOST'],
    'dsn' => $_ENV['MYSQL_DATABASE'],
    "char" => 'utf8'
];
