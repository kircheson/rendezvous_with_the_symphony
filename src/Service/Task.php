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

    public function create(TaskDto $taskDto): Task
    {
        $task = new Task();
        $task->setTitle($taskDto->title);
        $task->setDescription($taskDto->description);
        $task->setEmail($taskDto->email);

        return $task;
    }

    public function getAll(): array
    {
        return $this->em->getRepository(Task::class)->findAll();
    }

    public function get(int $id): ?Task
    {
        $task = $this->em->getRepository(Task::class)->find($id);

        if (!$task) {
            throw new NotFoundException('Задача с ID ' . $id . ' не найдена');
        }

        return $task;
    }

    public function update(int $id, TaskDto $updateTaskDto): Task
    {
        $task = $this->get($id);

        if ($task->getTitle() !== $updateTaskDto->title) {
            $task->setTitle($updateTaskDto->title);
        }

        if ($task->getDescription() !== $updateTaskDto->description) {
            $task->setDescription($updateTaskDto->description);
        }

        if ($task->getEmail() !== $updateTaskDto->email) {
            $task->setEmail($updateTaskDto->email);
        }

        return $task;
    }

    public function delete(int $id): Task
    {
        return $this->get($id);
    }
}