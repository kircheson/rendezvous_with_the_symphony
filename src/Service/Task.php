<?php

namespace App\Service;

use App\Entity\Task;
use App\Model\TaskDto;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createTask(TaskDto $taskDto): Task
    {
        $task = new Task();
        $task->setTitle($taskDto->title);
        $task->setDescription($taskDto->description);
        $task->setEmail($taskDto->email);

        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

    public function getTasks(): array
    {
        return $this->em->getRepository(Task::class)->findAll();
    }

    public function getTaskById(int $id): ?Task
    {
        return $this->em->getRepository(Task::class)->find($id);
    }

    public function updateTask(Task $task, TaskDto $updateTaskDto): Task
    {
        if ($task->getTitle() !== $updateTaskDto->title) {
            $task->setTitle($updateTaskDto->title);
        }

        if ($task->getDescription() !== $updateTaskDto->description) {
            $task->setDescription($updateTaskDto->description);
        }

        if ($task->getEmail() !== $updateTaskDto->email) {
            $task->setEmail($updateTaskDto->email);
        }

        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $this->em->remove($task);
        $this->em->flush();
    }
}

