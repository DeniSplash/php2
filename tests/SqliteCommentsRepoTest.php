<?php

namespace GeekBrains\LevelTwo\Tests;

use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\CommentRepository\SqliteCommentsRepo;
use GeekBrains\LevelTwo\Blog\UUID;
use \PDO;
use \PDOStatement;
use PHPUnit\Framework\TestCase;
use GeekBrains\LevelTwo\Blog\Comment;

class SqliteCommentsRepoTest extends TestCase
{
    public function testItThrowsAnExceptionWhenCommentAlreadyExists(): void
    { 
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);

        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);

        $repo = new SqliteCommentsRepo($connectionMock);

        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Коммент не найден: 123e4567-e89b-12d3-a456-426614174000');

        $repo->getCommentByUuid(new UUID('123e4567-e89b-12d3-a456-426614174000'));

    }

    public function testItSavesCommentToDatabase(): void
    {

        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
    
        $statementMock
        ->expects($this->once()) 
        ->method('execute') 
        ->with([ 
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':post_uuid' => '123e4567-e89b-12d3-a456-426614174001',
            ':author_uuid' => '123e4567-e89b-12d3-a456-426614174002',
            ':text' => 'Text',
        ]);
    
        $connectionStub->method('prepare')->willReturn($statementMock);
    
        $repository = new SqliteCommentsRepo($connectionStub);

        $repository->saveComment(
            new Comment( 
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                new UUID('123e4567-e89b-12d3-a456-426614174002'),
                new UUID('123e4567-e89b-12d3-a456-426614174001'),
                'Text')
        );
    }

    public function testItGetCommentByUuid(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock->method('fetch')->willReturn([
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'post_uuid' => new UUID('123e4567-e89b-12d3-a456-426614174001'),
            'author_uuid' => new UUID('123e4567-e89b-12d3-a456-426614174002'),
            'text' => 'text',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteCommentsRepo($connectionStub);
        $post = $repository->getCommentByUuid(new UUID('123e4567-e89b-12d3-a456-426614174000'));

        $this->assertSame('123e4567-e89b-12d3-a456-426614174000', (string)$post->getUuid());
    }
}