<?php

namespace GeekBrains\LevelTwo\Blog;

class User
{
    private int $id;
    private string $lastName;
    private string $firstName;

    public function __construct(int $id, string $lastName, string $firstName)
    {
        $this->id = $id;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function __toString(): string
    {
        return "Пользователь $this->id с именем $this->lastName и фамилией $this->firstName" . PHP_EOL;
    }
}