<?php

namespace GeekBrains\LevelTwo\tests\Actions;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Http\Actions\FindByUsername;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\PostRepoInterfaces;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\UserRepoInterfaces;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use PHPUnit\Framework\TestCase;
class CreatePostAction extends TestCase
{
    private function postsRepository(): PostRepoInterfaces{

        return new class() implements PostRepoInterfaces {
        
            private bool $called = false;

            public function __construct()
            {
                
            }

            public function savePost(Post $post): void
            {
                $this->called = true;
            }

            public function getPostByUuid(UUID $uuid): Post
            {
                throw new PostNotFoundException('Not found');
            }

            public function delete(UUID $uuid): void
            {
                throw new PostNotFoundException('Not found');
            }

            public function getCalled(): bool
            {
                return $this->called;
            }


        };
    }

    private function userRepository(array $users) : UserRepoInterfaces
    {
        return new class($users) implements UserRepoInterfaces
        {

            public function __construct(private array $users)
            {
                
            }

            public function saveUser(User $user): void
            {
                
            }

            public function getUserByUuid(UUID $uuid): User
            {
                foreach ($this->users as $key => $user) {
                if ($user instanceof User && (string)$uuid == $user->getUuid()) {
                        return $user;
                }
                }

                throw new UserNotFoundException();
            }

            public function getUserByName(string $userName): User
            {
                throw new UserNotFoundException("No found");
            }

        };
    }

    public function tetstReturnSucesful(): void
    {
        $requset = new Request([], [], '{"author_uuid":"", "title":"title", "text":""tetx}');

        $postsRepo = $this->postsRepository();

    }
    
}