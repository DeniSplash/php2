<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UserRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\PostRepoInterfaces;
use GeekBrains\LevelTwo\Blog\UUID;

class InMemoryPostsRepo implements PostRepoInterfaces
{
    private array $posts = [];

    public function savePost(Post $post): void
    {
        $this->posts[] = $post;
    }

    public function getPostByUuid(UUID $uuid): Post
    {
        foreach ($this->posts as $post) {
            if ($post->getUuid() === $uuid) {
                return $post; 
            }
        }

        throw new PostNotFoundException("Пост не найден $uuid"); 
    } 

    public function delete (UUID $uuid): void
    {
        
    }


}