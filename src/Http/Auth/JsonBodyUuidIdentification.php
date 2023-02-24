<?php
namespace GeekBrains\LevelTwo\Blog\Http\Auth;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Request;
use InvalidArgumentException;

class JsonBodyUuidIdentification implements IdentificationInterface
{
    public function __construct(
        private UserRepoInterfaces $usersRepository
    )
    {
    }
    public function user(Request $request): User
    {
        try {
            $userUuid = new UUID($request->jsonBodyField('user_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {

            throw new AuthException($e->getMessage());
        }
        try {

            return $this->usersRepository->getUserByUuid($userUuid);
        } catch (UserNotFoundException $e) {
 
            throw new AuthException($e->getMessage());
        }
    }
}