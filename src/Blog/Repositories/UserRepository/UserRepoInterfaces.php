<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UserRepository;

use GeekBrains\LevelTwo\Blog\User; 
use GeekBrains\LevelTwo\Blog\UUID;

interface UserRepoInterfaces
{
    public function saveUser(User $user): void;
    public function getUserByUuid(UUID $uuid): User;
    public function getUserByName(string $userName): User;
}