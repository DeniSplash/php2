<?php

namespace GeekBrains\LevelTwo\tests\Actions;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Http\Actions\FindByUsername;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\PostRepoInterfaces;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\CreatePost;
use PHPUnit\Framework\TestCase;

class CreatePostAction extends TestCase
{
    private function postsRepository(): PostRepoInterfaces
    {

        return new class () implements PostRepoInterfaces {

            private bool $called = false;

            public function __construct()
            {

            }

            public function savePost(Post $post): void
            {
                $this->called = true;
            }

            public function getPostByUuid(UUID $uuid): Post
            {
                throw new PostNotFoundException('Not found');
            }

            public function delete(UUID $uuid): void
            {
                throw new PostNotFoundException('Not found');
            }

            public function getCalled(): bool
            {
                return $this->called;
            }


        };
    }

    private function userRepository(array $users): UserRepoInterfaces
    {
        return new class ($users) implements UserRepoInterfaces {

            public function __construct(private array $users)
            {

            }

            public function saveUser(User $user): void
            {

            }

            public function getUserByUuid(UUID $uuid): User
            {
                foreach ($this->users as $key => $user) {
                    if ($user instanceof User && (string) $uuid == $user->getUuid()) {
                        return $user;
                    }
                }

                throw new UserNotFoundException();
            }

            public function getUserByName(string $userName): User
            {
                throw new UserNotFoundException("No found");
            }

        };
    }

    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request([], [], '{"author_uuid":"10373537-0805-4d7a-830e-22b481b4859c","title":"title","text":"text"}');

        $postsRepository = $this->postsRepository();

        $usersRepository = $this->userRepository([
            new User(
                new UUID('10373537-0805-4d7a-830e-22b481b4859c'),
                'name',
                'surname',
                'username',

            ),
        ]);

        $action = new CreatePost($postsRepository, $usersRepository, );

        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);

        $this->setOutputCallback(function ($data) {
            $dataDecode = json_decode(
                $data,
            associative: true,
            flags: JSON_THROW_ON_ERROR
            );

            $dataDecode['data']['uuid'] = "351739ab-fc33-49ae-a62d-b606b7038c87";
            return json_encode(
                $dataDecode,
                JSON_THROW_ON_ERROR
            );
        });

        $this->expectOutputString('{"success":true,"data":{"uuid":"351739ab-fc33-49ae-a62d-b606b7038c87"}}');


        $response->send();
    }


    public function testItReturnsErrorResponseIfNotFoundUser(): void
    {
        $request = new Request([], [], '{"author_uuid":"10373537-0805-4d7a-830e-22b481b4859c","title":"title","text":"text"}');

        $postsRepository = $this->postsRepository();
        $usersRepository = $this->userRepository([]);

        $action = new CreatePost($postsRepository, $usersRepository, );

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"Cannot find user: 10373537-0805-4d7a-830e-22b481b4859c"}');

        $response->send();
    }


    public function testItReturnsErrorResponseIfNoTextProvided(): void
    {
        $request = new Request([], [], '{"author_uuid":"10373537-0805-4d7a-830e-22b481b4859c","title":"title"}');

        $postsRepository = $this->postsRepository([]);
        $usersRepository = $this->userRepository([
            new User(
                new UUID('10373537-0805-4d7a-830e-22b481b4859c'),
                'Ivan',
                'Nikitin',
                'ivan',
            ),
        ]);

        $action = new CreatePost($postsRepository, $usersRepository, );


        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"No such field: text"}');

        $response->send();
    }
}