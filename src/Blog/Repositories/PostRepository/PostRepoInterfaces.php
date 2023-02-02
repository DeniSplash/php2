<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostRepository;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;

interface PostRepoInterfaces
{
    public function savePost(Post $post): void;
    public function getPostByUuid(UUID $uuid): Post;

}