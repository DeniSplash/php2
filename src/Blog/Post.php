<?php

namespace GeekBrains\LevelTwo\Blog;

class Post

{
    private UUID $uuid;
    private UUID $uuidUser;
    private string $article;
    private string $text;

    public function __construct(UUID $uuid, UUID $uuidUser, string $article, string $text)
    {
        $this->uuid = $uuid;
        $this->uuidUser = $uuidUser;
        $this->article = $article;
        $this->text = $text;
    }

	public function getUuid(): UUID {
		return $this->uuid;
	}

	public function setUuid(UUID $uuid): self {
		$this->uuid = $uuid;
		return $this;
	}

    public function getUuidUser(): UUID {
		return $this->uuidUser;
	}

	public function setIdUser(UUID $uuidUser): self {
		$this->uuidUser = $uuidUser;
		return $this;
	}

	public function getArticle(): string {
		return $this->article;
	}
	
	public function setArticle(string $article): self {
		$this->article = $article;
		return $this;
	}

	public function getText(): string {
		return $this->text;
	}
	
	public function setText(string $text): self {
		$this->text = $text;
		return $this;
	}

    public function __toString(): string
    {
        return "Пост $this->uuid, пользователя $this->uuidUser, загаловок $this->article, текст $this->text" . PHP_EOL;
    }
}