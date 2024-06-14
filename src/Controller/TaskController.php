<?php

namespace App\Controller;

use App\Model\TaskDto;
use App\Service\TaskService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/create_task', name: 'task_create_get', methods: ['GET'])]
    public function createTaskGet(): Response
    {
        return $this->render('task/_task_create.html.twig');
    }

    #[Route('/create_task', name: 'task_create_post', methods: ['POST'])]
    public function createTaskPost(#[MapRequestPayload] TaskDto $createTaskDto, TaskService $taskService): Response
    {
        $task = $taskService->createTask($createTaskDto);

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks', name: 'task_list')]
    public function list(TaskService $taskService): Response
    {
        $tasks = $taskService->getTasks();

        return $this->render('task/_task_list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/edit/{id}', name: 'task_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function edit(int $id, TaskService $taskService): Response
    {
        $task = $taskService->getTaskById($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
        }

        return $this->render('task/_task_edit.html.twig', ['task' => $task]);
    }

    #[Route('/tasks/update/{id}', name: 'task_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function update(int $id, #[MapRequestPayload] TaskDto $updateTaskDto, TaskService $taskService): Response
    {
        $task = $taskService->getTaskById($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
        }
        $taskService->updateTask($task, $updateTaskDto);

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/delete/{id}', name: 'task_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, TaskService $taskService): Response
    {
        $task = $taskService->getTaskById($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача не найдена');
        }

        $taskService->deleteTask($task);

        return $this->redirectToRoute('task_list');
    }
}
