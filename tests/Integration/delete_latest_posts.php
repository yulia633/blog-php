<?php

declare(strict_types=1);

use Blog\Test\Integration\ContainerProvider;
use Blog\Database;

/** @var ContainerProvider $container */
$container = ContainerProvider::getContainer();

/** @var PDO $connection */
$connection = $container->get(Database::class)->getConnection();

$statement = $connection->prepare('DELETE FROM post WHERE title LIKE (:title)');

$title = 'Test Post 1';
$statement->bindParam(':title', $title);
$statement->execute();

