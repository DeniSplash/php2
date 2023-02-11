<?php

use GeekBrains\LevelTwo\Blog\Exceptions\AppException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Repositories\CommentRepository\SqliteCommentsRepo;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\SqlitePostRepo;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\SqliteUsersRepo;
use GeekBrains\LevelTwo\Http\Actions\CreatePost;
use GeekBrains\LevelTwo\Http\Actions\CreateComment;
use GeekBrains\LevelTwo\Http\Actions\CreateUser;
use GeekBrains\LevelTwo\Http\Actions\FindByUsername;
use GeekBrains\LevelTwo\Http\Actions\DeletePost;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'),);


$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
    );
    
    try {
        $path = $request->path();
    } catch (HttpException) {
        (new ErrorResponse)->send();
        return;
    }

    try {

    $method = $request->method();
    } catch (HttpException) {

    (new ErrorResponse)->send();
    return;
    }
    $routes = [

        'GET' => [
            '/users/show' => new FindByUsername(
                new SqliteUsersRepo(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
                )
            ),
        ],

        'POST' => [
            '/user/create' => new CreateUser(
                new SqliteUsersRepo(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
            ), 

            '/posts/create' => new CreatePost(
                new SqlitePostRepo(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
                ),
                new SqliteUsersRepo(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
                )
                ),

            '/posts/comment' => new CreateComment(
                new SqliteCommentsRepo(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
                )
            ),
        ],

        'DELETE' => [
            '/posts' => new DeletePost(
                new SqlitePostRepo(
                    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
                    ) 
                ),
                
        ],

];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}

$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();