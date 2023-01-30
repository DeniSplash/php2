<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentRepository;

use GeekBrains\LevelTwo\Blog\Comment; 
use GeekBrains\LevelTwo\Blog\UUID;

interface CommentRepoInterfaces
{
    public function saveComment(Comment $comment): void;
    public function getCommentByUuid(UUID $uuid): Comment;

}