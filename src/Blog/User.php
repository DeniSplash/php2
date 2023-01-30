<?php

namespace GeekBrains\LevelTwo\Blog;

class User
{
    private UUID $uuid;

    private string $userName;
    private string $lastName;
    private string $firstName;

    public function __construct(UUID $uuid, string $userName, string $lastName, string $firstName)
    {
        $this->uuid = $uuid;
        $this->userName = $userName;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
    }

    public function getUuid(): UUID 
    {
        return $this->uuid;
    }

    public function setUuid(UUID $uuid): self
    {
        $this->uuid = $uuid;
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
        return "Пользователь $this->uuid с именем $this->lastName и фамилией $this->firstName" . PHP_EOL;
    }

	public function getUserName(): string {
		return $this->userName;
	}

	public function setUserName(string $userName): self {
		$this->userName = $userName;
		return $this;
	}
}