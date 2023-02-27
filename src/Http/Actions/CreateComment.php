<?php

namespace GeekBrains\LevelTwo\Http\Actions;

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\CommentRepository\CommentRepoInterfaces;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\PostRepoInterfaces;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CommentRepoInterfaces $commentRepoInterfaces,
        private LoggerInterface $logger,
        )
    {
    }
    public function handle(Request $request): Response
    {
        $newCommentUuid = UUID::random();
        try {

            $comment = new Comment(
                $newCommentUuid,
                $request->jsonBodyField('uuid_post'),
                $request->jsonBodyField('uuid_user'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            $this->logger->warning("Comment already exists: $newCommentUuid");
            return new ErrorResponse($e->getMessage());
        }

        $this->commentRepoInterfaces->saveComment($comment);
        $this->logger->info("Comment created: $newCommentUuid");

        return new SuccessfulResponse([
            'uuid' => (string) $newCommentUuid,
        ]);
    }
}