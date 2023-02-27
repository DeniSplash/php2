<?php

namespace GeekBrains\LevelTwo\Http\Actions;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class CreateUser implements ActionInterface
{

    public function __construct(private UserRepoInterfaces $usersRepository, 
    private LoggerInterface $logger,)
    {
    }
    public function handle(Request $request): Response
    {

        try {

            $newUserUuid = UUID::random();

            $user = new User(
                $newUserUuid,
                $request->jsonBodyField('username'),
                $request->jsonBodyField('last_name'),
                $request->jsonBodyField('first_name')

            );


        } catch (HttpException | InvalidArgumentException $e) {
            $this->logger->warning("User already exists: $newUserUuid");
            return new ErrorResponse($e->getMessage());
        }

        $this->usersRepository->saveUser($user);
        $this->logger->info("User created: $newUserUuid");
        

        return new SuccessfulResponse([
            'uuid' => (string) $newUserUuid,

        ]);

    }
}