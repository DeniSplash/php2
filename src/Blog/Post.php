<?php

namespace GeekBrains\LevelTwo\Blog;

class Post

{
    private int $id;
    private int $idUser;
    private string $article;
    private string $text;

    public function __construct(int $id, int $idUser, string $article, string $text)
    {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->article = $article;
        $this->text = $text;
    }

	public function getId(): int {
		return $this->id;
	}

	public function setId(int $id): self {
		$this->id = $id;
		return $this;
	}

    public function getIdUser(): int {
		return $this->idUser;
	}

	public function setIdUser(int $idUser): self {
		$this->idUser = $idUser;
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
        return "Пост $this->id, пользователя $this->idUser, загаловок $this->article, текст $this->text" . PHP_EOL;
    }
}