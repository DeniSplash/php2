<?php

namespace GeekBrains\LevelTwo\Http\Actions;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Uuid;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\PostRepoInterfaces;
use Psr\Log\LoggerInterface;

class DeletePost implements ActionInterface
{
    public function __construct(
        private PostRepoInterfaces $postsRepository, 
        private LoggerInterface $logger,
        )
    {
    }
    public function handle(Request $request): Response
    {

        try {
            $postUuid = $request->query('uuid');
            $this->postsRepository->getPostByUuid(new Uuid($postUuid));

        } catch (PostNotFoundException $e) {
            $this->logger->warning("Post already exists: $postUuid");
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->delete(new UUID($postUuid));
        $this->logger->info("Post dell: $postUuid");

        return new SuccessfulResponse(
            [
                'uuid' => $postUuid,
            ]
        );
    }
}