<?php

namespace GeekBrains\LevelTwo\Commands;

use GeekBrains\Blog\UnitTests\DummyLogger;
use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\DummyUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{

    public function testItRequiresPassword(): void
    {
        $command = new CreateUserCommand(
            $this->makeUsersRepository(),
            new DummyLogger()
        );
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: password');
        $command->handle(new Arguments([
            'username' => 'Ivan',
        ]));
    }

    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        $command = new CreateUserCommand(new DummyUsersRepository(), new DummyLogger());

        $this->expectException(CommandException::class);

        $this->expectExceptionMessage('User already exists: Ivan');

        $command->handle(new Arguments([
            'username' => 'Ivan',
            'password' => '123',
        ]));
    }


    public function testItRequiresFirstName(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface
        {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {

                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {


                throw new UserNotFoundException("Not found");
            }
        };

        $command = new CreateUserCommand($usersRepository, new DummyLogger());

        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: first_name');

        $command->handle(new Arguments(['username' => 'Ivan']));
    }

    public function testItRequiresLastName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository(), new DummyLogger());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: last_name');
        $command->handle(new Arguments([
            'username' => 'Ivan',
            'first_name' => 'Ivan',
        ]));
    }

    private function makeUsersRepository(): UsersRepositoryInterface
    {
        return new class implements UsersRepositoryInterface
        {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User

            {
                throw new UserNotFoundException("Not found");
            }
        };
    }


    public function testItSavesUserToRepository(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface
        {

            private bool $called = false;

            public function save(User $user): void
            {
                $this->called = true;
            }

            public function get(UUID $uuid): User
            {

                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        $command = new CreateUserCommand($usersRepository, new DummyLogger());

        $command->handle(new Arguments([
            'username' => 'Ivan',
            'first_name' => 'Ivan',
            'last_name' => 'Nikitin',
        ]));

        $this->assertTrue($usersRepository->wasCalled());
    }
}
