<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class TaskDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public ?string $title = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public ?string $description = null;

    #[Assert\NotNull]
    public ?int $userId = null;

    public ?\DateTime $createdAt = null;

    public function __construct(
        ?string $title = null,
        ?string $description = null,
        ?int $userId = null,
        ?\DateTime $createdAt = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
