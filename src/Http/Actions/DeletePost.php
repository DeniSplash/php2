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

class DeletePost implements ActionInterface
{
    public function __construct(private PostRepoInterfaces $postsRepository)
    {
    }
    public function handle(Request $request): Response
    {

        try {
            $postUuid = $request->query('uuid');
            $this->postsRepository->getPostByUuid(new Uuid($postUuid));

        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->delete(new UUID($postUuid));

        return new SuccessfulResponse(
            [
                'uuid' => $postUuid,
            ]
        );
    }
}