<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;

class InMemoryUsersRepo implements CommentRepoInterfaces
{
    private array $comments = [];

    public function saveComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    public function getCommentByUuid(UUID $uuid): Comment
    {
        foreach ($this->comments as $comment) {
            if ($comment->getUuid() === $uuid) {
                return $comment; 
            }
        }

        throw new CommentNotFoundException("Комментарий не найден $uuid"); 
    } 

}