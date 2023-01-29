<?php

namespace GeekBrains\LevelTwo\Blog\Repositories;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\User;

class InMemoryUsersRepo
{
    private array $users = [];

    public function saveUser(User $user): void
    {
        $this->users[] = $user;
    }

    public function getUser(int $id): User
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user; 
            }
        }

        throw new UserNotFoundException("Пользователь не найден $id"); 
    } 
}