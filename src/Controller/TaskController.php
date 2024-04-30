<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Task;

class TaskController extends AbstractController
{
    private $tasks = [];

    public function __construct()
    {
        $this->tasks[] = new Task(1, 'Задание 1', 'Описание задания 1', 'user1@example.com', new \DateTime());
        $this->tasks[] = new Task(2, 'Задание 2', 'Описание задания 2', 'user2@example.com', new \DateTime());
    }

    #[Route('/tasks', name: 'task_list')]
    public function list()
    {
        $tasks = $this->tasks;
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/edit/{id}', name: 'task_edit', requirements: ['id' => '\d+'])]
    public function edit(int $id)
    {
        $task = $this->tasks[0]; // В данном случае мы редактируем только первое задание
        return $this->render('task/edit.html.twig', ['task' => $task]);
    }
}