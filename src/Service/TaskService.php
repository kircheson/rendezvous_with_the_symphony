<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
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
        $task->setUser($this->em->getReference(User::class, $taskDto->userId));
        $task->setCreatedAt(new \DateTime());

        $this->em->persist($task);

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

        if ($task->getUser()->getId() !== $updateTaskDto->userId) {
            $user = $this->em->getReference(User::class, $updateTaskDto->userId);
            $task->setUser($user);
        }

        $this->em->flush();

        return $task;
    }

    public function delete(int $id): Task
    {
        $task = $this->get($id);
        $this->em->remove($task);

        return $task;
    }
}