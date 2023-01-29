<?php

require_once __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Repositories\InMemoryUsersRepo;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;

// spl_autoload_register(function ($class) {

//     $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
//     $file = str_replace('GeekBrains', 'src', $file);
//     //print_r($file . PHP_EOL);
//     if (file_exists($file)) {
//         require_once $file;
//     }
// });

$faker = Faker\Factory::create('ru_RU');

$route = $argv[1] ?? null;

switch ($route) {
    case 'user':
        $user = new User($faker->randomDigit(), $faker->firstName(), $faker->lastName());
        echo $user;
        break;

    case 'post':
        $user = new User($faker->randomDigit(), $faker->firstName(), $faker->lastName());
        $post = new Post($faker->randomDigit(), $user->getId(), $faker->word(), $faker->sentence());
        echo $user;
        echo $post;
        break;

    case 'comment':
        $user = new User($faker->randomDigit(), $faker->firstName(), $faker->lastName());
        $post = new Post($faker->randomDigit(), $user->getId(), $faker->word(), $faker->sentence());
        $comment = new Comment($faker->randomDigit(), $user->getId(), $post->getId(), $faker->sentence());
        echo $user;
        echo $post;
        echo $comment;
        break;

    default:
        echo 'Введите параметр';
        break;
}

// $userRepo = new InMemoryUsersRepo();

// try {
//     $userRepo->getUser(99); 
// } catch (UserNotFoundException $exp) {
//     echo $exp->getMessage(); 
// }