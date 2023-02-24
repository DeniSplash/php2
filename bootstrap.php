<?php
use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\PostRepoInterfaces;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\SqlitePostRepo;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\SqliteUsersRepo;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Dotenv\Dotenv;
use GeekBrains\LevelTwo\Blog\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Blog\Http\Auth\JsonBodyUuidIdentification;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$logger = (new Logger('blog'));

if ('yes' === $_ENV['LOG_TO_FILES']) {
    $logger
        ->pushHandler(
            new StreamHandler(
                __DIR__ . '/logs/blog.log'
            )
        )
        ->pushHandler(
            new StreamHandler(
                __DIR__ . '/logs/blog.error.log',
                level: Logger::ERROR,
                bubble: false,
            )
        );
}

if ('yes' === $_ENV['LOG_TO_CONSOLE']) {
    $logger
        ->pushHandler(
            new StreamHandler("php://stdout")
        );
}

$container->bind(
    LoggerInterface::class,
    $logger
);

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/' . $_ENV['SQLITE_DB_PATH'])
);

$container->bind(
    PostRepoInterfaces::class,
    SqlitePostRepo::class
);

$container->bind(
    UserRepoInterfaces::class,
    SqliteUsersRepo::class
);

$container->bind(
    IdentificationInterface::class,
    JsonBodyUuidIdentification::class
);

return $container;