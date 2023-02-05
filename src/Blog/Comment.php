<?php

namespace GeekBrains\LevelTwo\Blog;

class Comment 
{
    private UUID $uuid;
    private UUID $uuidUser;
    private UUID $uuidPost;
    private string $text;

    public function __construct(UUID $uuid, UUID $uuidUser, UUID $uuidPost, string $text)
    {
        $this->uuid = $uuid;
		$this->uuidPost = $uuidPost;
        $this->uuidUser = $uuidUser;
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
	
	public function setUuidUser(UUID $uuidUser): self {
		$this->uuidUser = $uuidUser;
		return $this;
	}

	public function getUuidPost(): UUID {
		return $this->uuidPost;
	}
	
	public function setIdPost(UUID $uuidPost): self {
		$this->uuidPost = $uuidPost;
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
        return "Комментарий $this->uuid, к посту $this->uuidPost, текст $this->text" . PHP_EOL;
    }
}