<?php

namespace GeekBrains\LevelTwo\tests\Actions;


use GeekBrains\LevelTwo\Http\Actions\FindByUsername;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use PHPUnit\Framework\TestCase;

class FindByUsernameActionTest extends TestCase
{

    public function testItReturnsErrorResponseIfNoUsernameProvided(): void
    {

        $request = new Request([], [], '');

        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"No such query param
in the request: username"}');

        $response->send();
    }

    public function testItReturnsErrorResponseIfUserNotFound(): void
    {

        $request = new Request(['username' => 'user'], [], '');

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
                'ivan',
                'Ivan',
                'Nikitin'
            ),
        ]);
        $action = new FindByUsername($usersRepository);
        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        $this->expectOutputString('{"success":true,"data":{"username":"ivan","name":"Iva
    n Nikitin"}}');
        $response->send();
    }

    private function usersRepository(array $users): UserRepoInterfaces
    {

        return new class ($users) implements UserRepoInterfaces {
            public function __construct(
                private array $users
            )
            {
            }
            public function saveUser(User $user): void
            {
            }
            public function getUserByUuid(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getUserByName(string $username): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $username === $user->getUserName()) {
                        return $user;
                    }
                }
                throw new UserNotFoundException("Not found");
            }
        };
    }
}