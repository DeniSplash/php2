<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UserRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;

class InMemoryUsersRepo implements UserRepoInterfaces
{
    private array $users = [];

    public function saveUser(User $user): void
    {
        $this->users[] = $user;
    }

    public function getUserByUuid(UUID $uuid): User
    {
        foreach ($this->users as $user) {
            if ($user->getUuid() === $uuid) {
                return $user; 
            }
        }

        throw new UserNotFoundException("Пользователь не найден $uuid"); 
    } 

    public function getUserByName(string $userName): User
    {
        foreach ($this->users as $user) {
            if ($user->getUserName() === $userName) {
                return $user; 
            }
        }

        throw new UserNotFoundException("Пользователь не найден $userName"); 
    } 
}