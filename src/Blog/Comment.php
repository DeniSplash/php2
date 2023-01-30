<?php

namespace GeekBrains\LevelTwo\Blog;

class Comment 
{
    private int $id;
    private int $idUser;
    private int $idPost;
    private string $text;

    public function __construct(int $id, int $idUser, int $idPost, string $text)
    {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->idPost = $idPost;
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

	public function getIdPost(): int {
		return $this->idPost;
	}
	
	public function setIdPost(int $idPost): self {
		$this->idPost = $idPost;
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
        return "Комментарий $this->id, к посту $this->idPost, текст $this->text" . PHP_EOL;
    }
}