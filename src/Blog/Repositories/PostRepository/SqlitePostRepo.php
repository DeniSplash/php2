<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use \PDO;

class SqlitePostRepo implements PostRepoInterfaces
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function savePost(Post $post): void
    {

        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
            VALUES (:uuid, :author_uuid, :title, :text)'
        );

        $statement->execute([
            ':uuid' => (string) $post->getUuid(),
            ':author_uuid' => $post->getUuidUser(),
            ':title' => $post->getArticle(),
            ':text' => $post->getText(),
        ]);
    }

    public function getPostByUuid(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = ?'
        );

        $statement->execute([(string) $uuid]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new PostNotFoundException(
                "Пост не найден: $uuid"
            );
        }

        return new Post(
            new UUID($result['uuid']),
            $result['author_uuid'],
            $result['title'],
            $result['text']
        );
    }

    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM posts Where posts.uuid=:uuid;'
        );

        $statement->execute(
            [
                'uuid' => $uuid,
            ]
        );
    }
}