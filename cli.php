<?php

require_once __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\SqliteUsersRepo;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$userRepo = new SqliteUsersRepo($connection);

$user = new User(UUID::random(), 'user', 'Petr', "Petrov");

$command = new CreateUserCommand($userRepo);
//$userRepo->saveUser($user);
try {
    $command->handle(Arguments::fromArgv($argv));
    //echo $userRepo->getUserByUuid(new UUID("93257c3f-e5fd-40ce-9aee-6b4599bce2d0"));
    //echo $userRepo->getUserByName('user1');
} catch (Exception $th) {
    echo $th->getMessage();
}