<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class TaskDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $title,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $description,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[Assert\Email(['message' => 'Email "{{ value }}" некорректный'])]
        public string $email,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
