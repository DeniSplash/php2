<?php

namespace GeekBrains\LevelTwo\Http\Actions;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class FindByUsername implements ActionInterface
{

    public function __construct(private UserRepoInterfaces $usersRepository)
    {
    }
    public function handle(Request $request): Response
    {
        try {
            $username = $request->query('username');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            
            $user = $this->usersRepository->getUserByName($username);
        } catch (UserNotFoundException $e) {
    
            return new ErrorResponse($e->getMessage());
        }
        
        return new SuccessfulResponse([
            'username' => $user->getUserName(),
            'name' => $user->getFirstName() . ' ' . $user->getLastName(),
        ]);
    }
}