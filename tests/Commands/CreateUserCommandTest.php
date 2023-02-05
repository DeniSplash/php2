<?php

namespace GeekBrains\Blog\UnitTests\Commands;

use GeekBrains\LevelTwo\Blog\Commands\Arguments; 

use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandExceptiom;
use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\DummyUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    { 
        $command = new CreateUserCommand(new DummyUsersRepository());
        $this->expectException(CommandExceptiom::class);
        $this->expectExceptionMessage('User already exists: Ivan');
        $command->handle(new Arguments(['username' => 'Ivan']));
    }

//================================================================================================
    public function testItRequiresFirstName(): void
    {
        $usersRepository = new class implements UserRepoInterfaces 
        {
            public function saveUser(User $user): void
            {
            }

            public function getUserByUuid(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getUserByName(string $userName): User
            {
                throw new UserNotFoundException("Not found");
            }
        };

        $command = new CreateUserCommand($usersRepository);
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('Аргумент не найден: first_name');
        $command->handle(new Arguments(['username' => 'Ivan']));
    }
    
//================================================================================================
    private function makeUsersRepository(): UserRepoInterfaces
    {
        return new class implements UserRepoInterfaces {
            public function saveUser(User $user): void
            {
            }

            public function getUserByUuid(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getUserByName(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }
        };
    }

    public function testItRequiresLastName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository());
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('Аргумент не найден: last_name');
        $command->handle(new Arguments([
        'username' => 'Ivan',
        'first_name' => 'Ivan',
        ]));
    } 

//================================================================================================
    public function testItSavesUserToRepository(): void
{
    $usersRepository = new class implements UserRepoInterfaces {

        private bool $called = false;

        public function saveUser(User $user): void
        {
            $this->called = true;
        }

        public function getUserByUuid(UUID $uuid): User
        {
            throw new UserNotFoundException("Not found");
        }

        public function getUserByName(string $username): User
        {
            throw new UserNotFoundException("Not found");
        }

        public function wasCalled(): bool
        {
            return $this->called;
        }
        };

        $command = new CreateUserCommand($usersRepository);

        $command->handle(new Arguments([
        'username' => 'Ivan',
        'first_name' => 'Ivan',
        'last_name' => 'Nikitin',
        ]));

        $this->assertTrue($usersRepository->wasCalled());
    }

//================================================================================================


}