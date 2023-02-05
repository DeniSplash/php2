<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UserRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
// Dummy - чучуло, манекен
class DummyUsersRepository implements UserRepoInterfaces
{
    public function saveUser(User $user): void
    {
    // Ничего не делаем
    }
    public function getUserByUuid(UUID $uuid): User
    {
        // И здесь ничего не делаем
        throw new UserNotFoundException("Not found");
    }

    public function getUserByName(string $userName): User
    {
        return new User(UUID::random(), "user123", "first", "last");
    }


}