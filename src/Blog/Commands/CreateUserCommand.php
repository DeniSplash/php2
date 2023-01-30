<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandExceptiom;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;

class CreateUserCommand 
{
    public function __construct(private UserRepoInterfaces $usersRepository)
    {
        
    }

    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get('username');
        if ($this->userExists($username)) {
            throw new CommandExceptiom("User already exists: $username");
        }
        $this->usersRepository->saveUser(new User(
        UUID::random(),
        $username,
        $arguments->get('first_name'), 
        $arguments->get('last_name'))
        );
    }
    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getUserByName($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}