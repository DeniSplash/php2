<?php

use GeekBrains\LevelTwo\Blog\Commands\CreateUser;
use GeekBrains\LevelTwo\Blog\Commands\DeletePost;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/bootstrap.php';

$application = new Application();

$commandsClasses = [
    CreateUser::class,
    DeletePost::class,
    UpdateUser::class,
    PopulateDB::class
];
foreach ($commandsClasses as $commandClass) {

    $command = $container->get($commandClass);
    $application->add($command);
}

$application->run();
