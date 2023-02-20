<?php

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\PostRepoInterfaces;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\SqlitePostRepo;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\SqliteUsersRepo;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
);

$container->bind(
    PostRepoInterfaces::class,
    SqlitePostRepo::class
);

$container->bind(
    UserRepoInterfaces::class,
    SqliteUsersRepo::class
);

return $container;