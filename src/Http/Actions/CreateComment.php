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

class CreateComment implements ActionInterface
{
    public function __construct(private CommentRepoInterfaces $commentRepoInterfaces)
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
            return new ErrorResponse($e->getMessage());
        }

        $this->commentRepoInterfaces->saveComment($comment);

        return new SuccessfulResponse([
            'uuid' => (string) $newCommentUuid,
        ]);
    }
}