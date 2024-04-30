<?php

namespace App;

class Task
{
    private $id;
    private $title;
    private $description;
    private $email;
    private $createdAt;

    public function __construct($id, $title, $description, $email, $createdAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->email = $email;
        $this->createdAt = $createdAt;
    }

    // Геттеры для доступа к свойствам
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    // Сеттеры для изменения свойств
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
}