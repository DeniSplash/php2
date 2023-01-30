<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Repositories\CommentRepository\CommentRepoInterfaces;
use GeekBrains\LevelTwo\Blog\UUID;
use \PDO;

class SqliteUsersRepo implements CommentRepoInterfaces
{ 
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    } 
    public function saveComment(Comment $comment): void
    {

        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, post_uuid, author_uuid, text)
            VALUES (:uuid, :post_uuid, :author_uuid, :text)'
            );
            
            $statement->execute([
            ':uuid' => (string)$comment->getUuid(),
            ':post_uuid' => $comment->getUuidPost(),
            ':author_uuid' => $comment->getUuidUser(),
            ':text' => $comment->getText(),
            ]);
    }

    public function getCommentByUuid(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
        'SELECT * FROM comments WHERE uuid = ?'
        );

        $statement->execute([(string)$uuid]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
    
        if (false === $result) {
            throw new CommentNotFoundException(
            "Коммент не найден: $uuid"
            );
        }
        
        return new Comment(
        new UUID($result['uuid']),
            $result['post_uuid'], 
            $result['author_uuid'], 
            $result['text']
        );
    }

}