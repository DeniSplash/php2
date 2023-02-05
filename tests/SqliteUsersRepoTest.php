<?php

namespace GeekBrains\LevelTwo\Tests;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UserRepository\SqliteUsersRepo;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use GeekBrains\LevelTwo\Blog\User;

class SqliteUsersRepoTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    { 
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);

        $repo = new SqliteUsersRepo($connectionMock);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Пользователь не найден: Ivan');

        $repo->getUserByName('Ivan');
    }

    public function testItSavesUserToDatabase(): void
    {
    
        $connectionStub = $this->createStub(PDO::class);
    
        $statementMock = $this->createMock(PDOStatement::class);
    
        $statementMock
        ->expects($this->once()) 
        ->method('execute') 
        ->with([ 
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':username' => 'ivan123',
            ':first_name' => 'Ivan',
            ':last_name' => 'Nikitin',
        ]);
    
        $connectionStub->method('prepare')->willReturn($statementMock);
    
        $repository = new SqliteUsersRepo($connectionStub);

        $repository->saveUser(
            new User( 
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                'ivan123',
                'Ivan', 
                'Nikitin')
        );
    }
}