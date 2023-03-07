<?php

namespace Actions;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\http\Actions\Users\FindByUsername;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\SuccessfulResponse;
use GeekBrains\LevelTwo\Person\Name;
use PHPUnit\Framework\TestCase;

class FindByUsernameActionTest extends TestCase
{

    public function testItReturnsErrorResponseIfNoUsernameProvided(): void
    {

        $request = new Request([], [], "");
        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository);
        $response = $action->handle($request);
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"No such query param in the request: username"}');
        $response->send();
    }

    public function testItReturnsErrorResponseIfUserNotFound(): void
    {
        $request = new Request(['username' => 'ivan'], [], '');
        $usersRepository = $this->usersRepository([]);
        $action = new FindByUsername($usersRepository);
        $response = $action->handle($request);
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"Not found"}');
        $response->send();
    }

    public function testItReturnsSuccessfulResponse(): void

    {
        $request = new Request(['username' => 'ivan'], [], '');
        $usersRepository = $this->usersRepository([
            new User(
                UUID::random(),
                new Name('Ivan', 'Nikitin'),
                'ivan',
                'password'

            ),
        ]);
        $action = new FindByUsername($usersRepository);
        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        $this->expectOutputString('{"success":true,"data":{"username":"ivan","name":"Ivan Nikitin"}}');
        $response->send();
    }


    private function usersRepository(array $users): UsersRepositoryInterface
    {

        return new class($users) implements UsersRepositoryInterface
        {
            public function __construct(
                private array $users
            ) {
            }

            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $username === $user->username()) {
                        return $user;
                    }
                }
                throw new UserNotFoundException("Not found");
            }
        };
    }
}
