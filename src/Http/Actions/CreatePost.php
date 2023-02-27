<?php

namespace GeekBrains\LevelTwo\Http\Actions;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\PostRepoInterfaces;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostRepoInterfaces $postsRepository,
        private IdentificationInterface $identification,
        private LoggerInterface $logger,
    )
    {
    }
    public function handle(Request $request): Response
    {
        $author = $this->identification->user($request);

        $newPostUuid = UUID::random();

        try {

            $post = new Post(
                $newPostUuid,
                $author->getUuid(),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            $this->logger->warning("Post already exists: $newPostUuid");
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->savePost($post);

        $this->logger->info("Post created: $newPostUuid");

        return new SuccessfulResponse([
            'uuid' => (string) $newPostUuid,
        ]);
    }
}