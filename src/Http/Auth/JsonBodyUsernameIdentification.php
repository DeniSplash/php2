<?php

namespace GeeGeekBrains\LevelTwo\Brains\Blog\Http\Auth;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Http\Auth\AuthException;
use GeekBrains\LevelTwo\Blog\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Http\Request;

class JsonBodyUsernameIdentification implements IdentificationInterface
{
    public function __construct(
        private UserRepoInterfaces $usersRepository
    )
    {
    }
    public function user(Request $request): User
    {
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            return $this->usersRepository->getUserByName($username);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
    }
}