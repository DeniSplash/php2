<?php

namespace GeekBrains\LevelTwo\Tests;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\PostRepository\SqlitePostRepo;
use GeekBrains\LevelTwo\Blog\UUID;
use \PDO;
use \PDOStatement;
use PHPUnit\Framework\TestCase;
use GeekBrains\LevelTwo\Blog\Post;

class SqlitePostsRepoTest extends TestCase
{
    public function testItThrowsAnExceptionWhenPostAlreadyExists(): void
    { 
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);

        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);

        $repo = new SqlitePostRepo($connectionMock);

        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Пост не найден: 123e4567-e89b-12d3-a456-426614174000');

        $repo->getPostByUuid(new UUID('123e4567-e89b-12d3-a456-426614174000'));

    }

    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
    
        $statementMock
        ->expects($this->once()) 
        ->method('execute') 
        ->with([ 
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':author_uuid' => '123e4567-e89b-12d3-a456-426614174001',
            ':title' => 'Article',
            ':text' => 'Text',
        ]);
    
        $connectionStub->method('prepare')->willReturn($statementMock);
    
        $repository = new SqlitePostRepo($connectionStub);

        $repository->savePost(
            new Post( 
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                new UUID('123e4567-e89b-12d3-a456-426614174001'),
                'Article', 
                'Text')
        );

    }

    public function testItGetPostByUuid(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock->method('fetch')->willReturn([
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'author_uuid' => new UUID('123e4567-e89b-12d3-a456-426614174002'),
            'title' => 'article',
            'text' => 'text',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqlitePostRepo($connectionStub);
        $post = $repository->getPostByUuid(new UUID('123e4567-e89b-12d3-a456-426614174000'));

        $this->assertSame('123e4567-e89b-12d3-a456-426614174000', (string)$post->getUuid());
    }
}